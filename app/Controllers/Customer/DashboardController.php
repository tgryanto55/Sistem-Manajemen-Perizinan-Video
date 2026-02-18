<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\VideoModel;
use App\Models\VideoAccessRequestModel;

/**
 * Controller untuk dashboard Customer.
 */
class DashboardController extends BaseController
{
    /**
     * Tampilkan halaman dashboard customer.
     */
    public function index()
    {
        // Inisialisasi model
        $videoModel = new VideoModel();
        $accessModel = new VideoAccessRequestModel();
        
        // Ambil ID user yang sedang login
        $userId = session('user_id');

        // Siapkan data statistik
        $data = [
            'totalVideos' => $videoModel->countAll(), // Total semua video di sistem
            'accessibleVideos' => $accessModel->where('user_id', $userId)->where('status', 'approved')->where('expired_at >=', date('Y-m-d H:i:s'))->countAllResults(), // Total video yang bisa ditonton (approved & belum expired)
            'pendingRequests' => $accessModel->where('user_id', $userId)->where('status', 'pending')->countAllResults(), // Request yang masih menunggu persetujuan
            'recentVideos' => $videoModel->orderBy('created_at', 'DESC')->findAll(3) // 3 video terbaru
        ];

        // Tampilkan view
        return view('customer/dashboard', $data);
    }
}
