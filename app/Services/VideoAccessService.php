<?php

namespace App\Services;

use App\Models\VideoAccessRequestModel;
use App\Services\VideoDurationService;

/**
 * VideoAccessService
 * 
 * This service provides methods to check the current access status 
 * for a specific user and video. 
 * It handles the logic for determining if access is active or has expired.
 */
class VideoAccessService
{
    protected $requestModel;
    protected $durationService;

    public function __construct()
    {
        $this->requestModel = new VideoAccessRequestModel();
        $this->durationService = new VideoDurationService();
    }

    /**
     * Checks if a user has currently active (approved and not expired) access.
     * 
     * @param int $userId
     * @param int $videoId
     * @return bool
     */
    public function hasActiveAccess(int $userId, int $videoId): bool
    {
        // Find an approved request for this specific combo.
        $request = $this->requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
            'status'   => 'approved',
        ])->first();

        if (!$request) {
            return false;
        }

        // Even if status is approved, we must double-check the time limit.
        if ($request['expired_at'] && $this->durationService->isExpired($request['expired_at'])) {
            return false;
        }

        return true;
    }

    /**
     * Gets the full access status (none, pending, approved, rejected, expired).
     * 
     * @param int $userId
     * @param int $videoId
     * @return string The status label.
     */
    public function getAccessStatus(int $userId, int $videoId): string
    {
        // Get the latest request from this user for this video.
        $request = $this->requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
        ])->orderBy('created_at', 'DESC')->first();

        if (!$request) {
            return 'none';
        }
        
        // If approved, check if it's already past the expiration date.
        if ($request['status'] === 'approved' && $request['expired_at'] && $this->durationService->isExpired($request['expired_at'])) {
             return 'expired';
        }

        return $request['status'];
    }

    /**
     * Helper to expose expiration checks to other classes.
     */
    public function isExpired(string $expiredAt): bool
    {
        return $this->durationService->isExpired($expiredAt);
    }
}
