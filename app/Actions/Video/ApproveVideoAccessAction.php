<?php

namespace App\Actions\Video;

use App\Models\VideoAccessRequestModel;
use App\Services\VideoDurationService;
use CodeIgniter\I18n\Time;

/**
 * ApproveVideoAccessAction
 * 
 * This action is responsible for approving a video access request by an admin.
 * It calculates the expiration time based on input and updates the request status.
 */
class ApproveVideoAccessAction
{
    protected $requestModel;
    protected $durationService;

    public function __construct()
    {
        $this->requestModel = new VideoAccessRequestModel();
        $this->durationService = new VideoDurationService();
    }

    /**
     * Finalizes the approval of a video request.
     *
     * @param int $requestId The ID of the pending request.
     * @param int $durationHours How many hours the access should be valid for.
     * @param int $durationMinutes Additional minutes for finer control.
     * @return bool Returns true if the update was successful, false otherwise.
     */
    public function execute(int $requestId, int $durationHours, int $durationMinutes = 0): bool
    {
        // Verify the request exists before proceeding.
        $request = $this->requestModel->find($requestId);

        if (!$request) {
            return false;
        }

        // We mark the exact moment of approval and then calculate the expiry time.
        // The expiry calculation is delegated to a Service to keep this action focused.
        $approvedAt = Time::now()->toDateTimeString();
        $expiredAt  = $this->durationService->calculateExpiry($approvedAt, $durationHours, $durationMinutes);

        $data = [
            'status'      => 'approved',
            'approved_at' => $approvedAt,
            'expired_at'  => $expiredAt,
        ];

        return $this->requestModel->update($requestId, $data);
    }
}
