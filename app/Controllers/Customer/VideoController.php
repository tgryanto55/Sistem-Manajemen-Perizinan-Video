<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\VideoModel;
use App\Actions\Video\RequestVideoAccessAction;
use App\Services\VideoAccessService;
use App\Models\VideoAccessRequestModel;

/**
 * Controller untuk menangani fitur video di sisi Customer.
 * Termasuk browsing daftar video, request akses, dan streaming.
 */
class VideoController extends BaseController
{
    /**
     * Tampilkan daftar video beserta status aksesnya.
     */
    public function index()
    {
        helper(['video', 'url']);
        // Inisialisasi model dan service
        $videoModel = new VideoModel();
        $accessService = new VideoAccessService();
        $userId = session()->get('user_id');
        
        // Ambil data video sekaligus status akses user (biar gak N+1 query)
        // Parameter search dihapus karena fitur search sudah tidak digunakan
        $videos = $videoModel->getVideosWithAccess($userId);
        
        // Loop setiap video untuk cek status expire dan thumbnail
        foreach ($videos as &$video) {
            $status = $video['access_status'] ?? 'none';
            // Cek apakah akses 'approved' sudah lewat tanggal expired
            $isExpired = ($status === 'approved' && $video['expired_at'] && $accessService->isExpired($video['expired_at']));
            
            // Update status jadi 'expired' jika sudah kadaluarsa
            $video['access_status'] = $isExpired ? 'expired' : $status;
            // Flag helper untuk di view
            $video['has_active_access'] = ($status === 'approved' && !$isExpired);
            // Ambil thumbnail dari YouTube atau generate default
            $video['thumbnail_url'] = get_youtube_thumbnail($video['video_path']);
        }

        $data = [
            'videos' => $videos
        ];
        
        return view('customer/videos/index', $data);
    }

    /**
     * Handle request akses video oleh customer.
     */
    public function requestAccess($videoId)
    {
        $userId = session()->get('user_id');
        $action = new RequestVideoAccessAction();

        // Panggil Action untuk memproses request (cek duplikasi dll)
        if ($action->execute($userId, $videoId)) {
            // Berhasil request
            return redirect()->back()->with('success', 'Access requested successfully. Please wait for admin approval.');
        }

        // Gagal request
        return redirect()->back()->with('error', 'Failed to request access.');
    }

    /**
     * Halaman nonton video (Watch page).
     * Hanya bisa diakses jika punya izin aktif.
     */
    public function watch($videoId)
    {
        $userId = session()->get('user_id');
        $accessService = new VideoAccessService();

        // Cek keamanan: User HARUS punya akses aktif
        if (!$accessService->hasActiveAccess($userId, $videoId)) {
            return redirect()->to('/customer/videos')->with('error', 'You do not have active access to this video.');
        }

        $videoModel = new VideoModel();
        $video = $videoModel->find($videoId);

        if (!$video) {
            return redirect()->to('/customer/videos')->with('error', 'Video not found.');
        }

        // Ambil data request untuk mendapatkan tanggal expired yang akurat
        $requestModel = new VideoAccessRequestModel();
        $accessRequest = $requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
            'status'   => 'approved',
        ])->first();

        $data = [
            'video'      => $video,
            // Format tanggal expired ke ISO 8601 biar mudah diparsing JS (countdown timer)
            'expired_at' => $accessRequest ? date('c', strtotime($accessRequest['expired_at'])) : null
        ];

        return view('customer/videos/watch', $data);
    }

    /**
     * Endpoint streaming video.
     * Menghandle redirect ke URL eksternal (YouTube).
     */
    public function stream($videoId)
    {
        $userId = session()->get('user_id');
        $accessService = new VideoAccessService();

        // 1. Cek otorisasi ulang sebelum stream
        if (!$accessService->hasActiveAccess($userId, $videoId)) {
            return $this->response->setStatusCode(403)->setBody('Access Denied');
        }

        $videoModel = new VideoModel();
        $video = $videoModel->find($videoId);

        if (!$video) {
            return $this->response->setStatusCode(404)->setBody('Video not found');
        }

        // 2. Redirect ke URL video external
        if (filter_var($video['video_path'], FILTER_VALIDATE_URL)) {
             return redirect()->to($video['video_path']);
        }
        
        // Return 404 jika bukan URL.
        return $this->response->setStatusCode(404)->setBody('Video source not valid');
    }


}
