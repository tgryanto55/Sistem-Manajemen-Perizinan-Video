<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\VideoModel;
use App\Actions\Video\RequestVideoAccessAction;
use App\Services\VideoAccessService;

/**
 * VideoController (Customer)
 * 
 * Handles the video experience for customers, including browsing, 
 * requesting access, watching, and streaming file-based content.
 */
class VideoController extends BaseController
{
    /**
     * Lists all videos and shows the user's specific access status for each.
     * Uses optimized LEFT JOIN query to prevent N+1 performance issues.
     */
    public function index()
    {
        helper(['video', 'url']);
        $videoModel = new VideoModel();
        $accessService = new VideoAccessService();
        $userId = session()->get('user_id');

        $search = $this->request->getGet('q');
        // Fetch videos along with this user's latest request status in ONE query.
        $videos = $videoModel->getVideosWithAccess($userId, $search);
        
        foreach ($videos as &$video) {
            $status = $video['access_status'] ?? 'none';
            // Check if approval has already past its expiration date.
            $isExpired = ($status === 'approved' && $video['expired_at'] && $accessService->isExpired($video['expired_at']));
            
            $video['access_status'] = $isExpired ? 'expired' : $status;
            $video['has_active_access'] = ($status === 'approved' && !$isExpired);
            // Thumbnails extracted via video_helper.php
            $video['thumbnail_url'] = get_youtube_thumbnail($video['video_path']);
        }

        $data = [
            'videos' => $videos
        ];
        
        return view('customer/videos/index', $data);
    }

    /**
     * Triggers a new access request for a specific video.
     */
    public function requestAccess($videoId)
    {
        $userId = session()->get('user_id');
        $action = new RequestVideoAccessAction();

        // Business logic is encapsulated inside the Action class.
        if ($action->execute($userId, $videoId)) {
            return redirect()->back()->with('success', 'Access requested successfully. Please wait for admin approval.');
        }

        return redirect()->back()->with('error', 'Failed to request access.');
    }

    /**
     * The principal "Watch" page. Verified by active access status.
     */
    public function watch($videoId)
    {
        $userId = session()->get('user_id');
        $accessService = new VideoAccessService();

        // Security check: Customer MUST have active access to even view the page.
        if (!$accessService->hasActiveAccess($userId, $videoId)) {
            return redirect()->to('/customer/videos')->with('error', 'You do not have active access to this video.');
        }

        $videoModel = new VideoModel();
        $video = $videoModel->find($videoId);

        if (!$video) {
            return redirect()->to('/customer/videos')->with('error', 'Video not found.');
        }

        // Fetch the active request to pass the exact expiration timestamp for the countdown.
        $requestModel = new \App\Models\VideoAccessRequestModel();
        $accessRequest = $requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
            'status'   => 'approved',
        ])->first();

        $data = [
            'video'      => $video,
            // Format for JS-friendly ISO string.
            'expired_at' => $accessRequest ? date('c', strtotime($accessRequest['expired_at'])) : null
        ];

        return view('customer/videos/watch', $data);
    }

    /**
     * Secure Streaming Endpoint.
     * Prevents direct file access and handles both external URLs and local files.
     */
    public function stream($videoId)
    {
        $userId = session()->get('user_id');
        $accessService = new VideoAccessService();

        // 1. Authorization check before any file manipulation.
        if (!$accessService->hasActiveAccess($userId, $videoId)) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $videoModel = new VideoModel();
        $video = $videoModel->find($videoId);

        if (!$video) {
            return $this->response->setStatusCode(404)->setBody('Video not found');
        }

        // 2. Handle External URLs: Simply redirect to the source.
        if (filter_var($video['video_path'], FILTER_VALIDATE_URL)) {
             return redirect()->to($video['video_path']);
        }

        // 3. Handle Local Files: Ensure path is valid.
        $path = $video['video_path'];
        if (!is_file($path)) {
            $path = WRITEPATH . 'uploads/' . $video['video_path'];
        }

        if (!is_file($path)) {
            return $this->response->setStatusCode(404)->setBody('File not found');
        }

        $mime = mime_content_type($path);
        $size = filesize($path);
        
        // 4. Memory-Efficient Streaming: 
        // We bypass CI4's Response object slightly to use native PHP functions 
        // that stream the file line-by-line instead of loading 1GB into RAM.
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . $size);
        header('Accept-Ranges: bytes');
        header('Cache-Control: no-cache, must-revalidate');
        
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        
        readfile($path);
        exit;
    }

    /**
     * Helper for HTMX to refresh the video grid.
     */
    public function listRows()
    {
        helper(['video', 'url']);
        $videoModel = new VideoModel();
        $accessService = new VideoAccessService();
        $userId = session()->get('user_id');

        $search = $this->request->getGet('q');
        $videos = $videoModel->getVideosWithAccess($userId, $search);
        
        foreach ($videos as &$video) {
            $status = $video['access_status'] ?? 'none';
            $isExpired = ($status === 'approved' && $video['expired_at'] && $accessService->isExpired($video['expired_at']));
            
            $video['access_status'] = $isExpired ? 'expired' : $status;
            $video['has_active_access'] = ($status === 'approved' && !$isExpired);
            $video['thumbnail_url'] = get_youtube_thumbnail($video['video_path']);
        }

        $data = [
            'videos' => $videos
        ];
        
        return view('customer/videos/_rows', $data);
    }
}
