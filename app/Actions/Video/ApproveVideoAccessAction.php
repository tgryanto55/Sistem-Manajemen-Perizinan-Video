<?php

namespace App\Actions\Video;

use App\Models\VideoAccessRequestModel;
use App\Services\VideoDurationService;
use CodeIgniter\I18n\Time;

/**
 * Handle persetujuan akses video oleh admin.
 */
class ApproveVideoAccessAction
{
    protected $requestModel;
    protected $durationService;

    public function __construct()
    {
        // Siapin model dan service durasi
        $this->requestModel = new VideoAccessRequestModel();
        $this->durationService = new VideoDurationService();
    }

    /**
     * Eksekusi approve request.
     * Hitung durasi akses dan update status jadi 'approved'.
     */
    public function execute(int $requestId, int $durationHours, int $durationMinutes = 0): bool
    {
        // Cek request ada atau nggak
        $request = $this->requestModel->find($requestId);

        if (!$request) {
            return false;
        }

        // Hitung waktu kadaluarsa via service
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
