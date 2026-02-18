<?php

namespace App\Actions\Video;

use App\Models\VideoAccessRequestModel;

/**
 * Handle penolakan akses video.
 */
class RejectVideoAccessAction
{
    protected $requestModel;

    public function __construct()
    {
        // Inisialisasi model request
        $this->requestModel = new VideoAccessRequestModel();
    }

    /**
     * Eksekusi reject request.
     * Update status jadi 'rejected'.
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
