<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\VideoModel;
use App\Models\VideoAccessRequestModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();
        $videoModel = new VideoModel();
        $requestModel = new VideoAccessRequestModel();

        $data = [
            'totalCustomers' => $userModel->where('role', 'customer')->countAllResults(),
            'totalVideos'    => $videoModel->countAllResults(),
            'pendingRequests'=> $requestModel->where('status', 'pending')->countAllResults(),
            'totalRequests'  => $requestModel->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }
}
