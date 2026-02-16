<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\VideoModel;
use App\Models\VideoAccessRequestModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $videoModel = new VideoModel();
        $accessModel = new VideoAccessRequestModel();
        $userId = session('user_id');

        $data = [
            'totalVideos' => $videoModel->countAll(),
            'accessibleVideos' => $accessModel->where('user_id', $userId)->where('status', 'approved')->where('expired_at >=', date('Y-m-d H:i:s'))->countAllResults(),
            'pendingRequests' => $accessModel->where('user_id', $userId)->where('status', 'pending')->countAllResults(),
            'recentVideos' => $videoModel->orderBy('created_at', 'DESC')->findAll(3)
        ];

        return view('customer/dashboard', $data);
    }
}
