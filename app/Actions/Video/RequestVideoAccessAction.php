<?php

namespace App\Actions\Video;

use App\Models\VideoAccessRequestModel;
use CodeIgniter\I18n\Time;

/**
 * Handle permintaan akses video oleh customer.
 */
class RequestVideoAccessAction
{
    protected $requestModel;

    public function __construct()
    {
        // Inisialisasi model request
        $this->requestModel = new VideoAccessRequestModel();
    }

    /**
     * Eksekusi request akses baru.
     * Cek duplikasi request sebelum buat baru.
     */
    public function execute(int $userId, int $videoId)
    {
        // Cek apakah sudah ada request pending yang sama
        $existing = $this->requestModel->where([
            'user_id'  => $userId,
            'video_id' => $videoId,
            'status'   => 'pending',
        ])->first();

        if ($existing) {
            // Kalau ada, balikin ID yang lama aja
            return $existing['id'];
        }

        // Kalau belum ada, buat request baru status 'pending'
        $data = [
            'user_id'      => $userId,
            'video_id'     => $videoId,
            'status'       => 'pending', 
            'requested_at' => Time::now(),
        ];

        return $this->requestModel->insert($data);
    }
}
