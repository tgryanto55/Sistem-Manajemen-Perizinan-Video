<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model VideoAccessRequest
 * 
 * Mengelola tabel 'video_access_requests' (tabel penghubung antara User dan Video).
 * Mencatat status akses (pending, approved, rejected) dan waktu kadaluarsa.
 */
class VideoAccessRequestModel extends Model
{
    protected $table            = 'video_access_requests';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['user_id', 'video_id', 'status', 'requested_at', 'approved_at', 'expired_at'];

    // Konfigurasi Timestamp
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Ambil semua request beserta detail User dan Video-nya.
     * Dipakai di halaman Admin Dashboard biar datanya informatif.
     */
    public function getRequestsWithDetails()
    {
        return $this->select('video_access_requests.*, users.name as user_name, videos.title as video_title')
                    ->join('users', 'users.id = video_access_requests.user_id')
                    ->join('videos', 'videos.id = video_access_requests.video_id')
                    ->orderBy('video_access_requests.created_at', 'DESC') // Yang terbaru ditaruh paling atas
                    ->findAll();
    }

    /**
     * Ambil detail satu request spesifik beserta nama user dan judul videonya.
     */
    public function getRequestWithDetails($id)
    {
        return $this->select('video_access_requests.*, users.name as user_name, videos.title as video_title')
                    ->join('users', 'users.id = video_access_requests.user_id')
                    ->join('videos', 'videos.id = video_access_requests.video_id')
                    ->where('video_access_requests.id', $id)
                    ->first();
    }
}
