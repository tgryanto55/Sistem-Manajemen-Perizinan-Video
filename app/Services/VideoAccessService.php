<?php

namespace App\Services;

use App\Models\VideoAccessRequestModel;
use App\Services\VideoDurationService;

/**
 * Service Akses Video
 * 
 * Menangani logika pengecekan apakah user boleh nonton video tertentu atau tidak.
 * Termasuk cek status approved dan apakah durasinya sudah expired.
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
     * Cek apakah user punya akses aktif ke video tertentu.
     * Syarat: Status approved DAN belum expired.
     */
    public function hasActiveAccess(int $userId, int $videoId): bool
    {
        // Cari request yang statusnya approved
        $request = $this->requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
            'status'   => 'approved',
        ])->first();

        // Kalau gak ada yang approved, tolak
        if (!$request) {
            return false;
        }

        // Kalau ada, pastikan belum kadaluarsa
        if ($request['expired_at'] && $this->durationService->isExpired($request['expired_at'])) {
            return false;
        }

        return true;
    }

    /**
     * Ambil status akses lengkap (none, pending, approved, rejected, expired).
     * Berguna untuk menampilkan status di UI card video.
     */
    public function getAccessStatus(int $userId, int $videoId): string
    {
        // Ambil request paling terakhir
        $request = $this->requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
        ])->orderBy('created_at', 'DESC')->first();

        // Belum pernah request sama sekali
        if (!$request) {
            return 'none';
        }
        
        // Kalau approved tapi udah lewat tanggal expired, return 'expired'
        if ($request['status'] === 'approved' && $request['expired_at'] && $this->durationService->isExpired($request['expired_at'])) {
             return 'expired';
        }

        // Return status aslinya (pending/approved/rejected)
        return $request['status'];
    }

    /**
     * Helper buat cek expired (wrapper function).
     */
    public function isExpired(string $expiredAt): bool
    {
        return $this->durationService->isExpired($expiredAt);
    }
}
