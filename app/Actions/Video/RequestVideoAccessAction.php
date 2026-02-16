<?php

namespace App\Actions\Video;

use App\Models\VideoAccessRequestModel;
use CodeIgniter\I18n\Time;

/**
 * RequestVideoAccessAction
 * 
 * This action manages the process of a customer requesting access to a video.
 * It prevents duplicate pending requests and handles the initial state of a request.
 */
class RequestVideoAccessAction
{
    protected $requestModel;

    public function __construct()
    {
        $this->requestModel = new VideoAccessRequestModel();
    }

    /**
     * Processes a new video access request.
     *
     * @param int $userId The ID of the customer making the request.
     * @param int $videoId The ID of the video being requested.
     * @return int|bool Returns the Request ID (existing or new) or false on failure.
     */
    public function execute(int $userId, int $videoId)
    {
        // First, check if there is already an active 'pending' request for this specific 
        // user and video. This prevents spamming and redundant entries.
        $existing = $this->requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
            'status'   => 'pending',
        ])->first();

        if ($existing) {
            // If it exists, we just return the ID of the existing request.
            return $existing['id'];
        }

        // Prepare the data for a fresh request entry.
        $data = [
            'user_id'      => $userId,
            'video_id'     => $videoId,
            'status'       => 'pending', // Initial status is always pending
            'requested_at' => Time::now(), // Use CI4 Time for consistency
        ];

        return $this->requestModel->insert($data);
    }
}
