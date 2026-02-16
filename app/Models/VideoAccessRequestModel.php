<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * VideoAccessRequestModel
 * 
 * Manages the joining table between Users and Videos.
 * Tracks the status of access (pending, approved, rejected) and the expiration time.
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

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Custom query to fetch requests along with User Names and Video Titles.
     * Used primarily by the Admin Dashboard to show meaningful data.
     */
    public function getRequestsWithDetails()
    {
        return $this->select('video_access_requests.*, users.name as user_name, videos.title as video_title')
                    ->join('users', 'users.id = video_access_requests.user_id')
                    ->join('videos', 'videos.id = video_access_requests.video_id')
                    ->orderBy('video_access_requests.created_at', 'DESC') // Newest first
                    ->findAll();
    }

    /**
     * Simple variation to fetch details for a single specific request.
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
