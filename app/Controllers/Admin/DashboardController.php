<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VideoModel;
use App\Models\VideoAccessRequestModel;

/**
 * Controller untuk dashboard Admin.
 * Menampilkan ringkasan statistik aplikasi.
 */
class DashboardController extends BaseController
{
    public function index()
    {
        // Inisialisasi semua model yang dibutuhkan
        $userModel = new UserModel();
        $videoModel = new VideoModel();
        $requestModel = new VideoAccessRequestModel();

        // Hitung data statistik untuk ditampilkan di dashboard
        $data = [
            'totalCustomers' => $userModel->where('role', 'customer')->countAllResults(), // Total user yang rolenya 'customer'
            'totalVideos'    => $videoModel->countAllResults(), // Total semua video yang ada
            'pendingRequests'=> $requestModel->where('status', 'pending')->countAllResults(), // Total request yang statusnya masih 'pending'
            'totalRequests'  => $requestModel->countAllResults(), // Total seluruh request (pending, approved, rejected)
        ];

        // Tampilkan view dashboard dengan data statistik
        return view('admin/dashboard', $data);
    }
}
