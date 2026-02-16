<?php

namespace App\Actions\Video;

use App\Models\VideoAccessRequestModel;

/**
 * RejectVideoAccessAction
 * 
 * Simple action to change the status of a request to 'rejected'.
 */
class RejectVideoAccessAction
{
    protected $requestModel;

    public function __construct()
    {
        $this->requestModel = new VideoAccessRequestModel();
    }

    /**
     * Updates the request status to rejected.
     *
     * @param int $requestId The ID of the request to reject.
     * @return bool
     */
    public function execute(int $requestId): bool
    {
        $request = $this->requestModel->find($requestId);

        if (!$request) {
            return false;
        }

        $data = [
            'status' => 'rejected',
        ];

        return $this->requestModel->update($requestId, $data);
    }
}
