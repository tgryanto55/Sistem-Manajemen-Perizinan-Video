<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * VideoModel
 * 
 * Manages the 'videos' table.
 */
class VideoModel extends Model
{
    protected $table            = 'videos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'video_path', 'duration']; // Added missing allowedFields
    protected $updatedField     = 'updated_at';

    /**
     * Optimized query to fetch all videos along with a specific user's access status.
     * This avoids the N+1 problem where we would otherwise have to query 
     * the status for each video in a loop.
     * 
     * @param int $userId The current logged-in customer.
     * @param string|null $search Optional search filter for title or description.
     * @return array
     */
    public function getVideosWithAccess(int $userId, ?string $search = null)
    {
        // We use a LEFT JOIN so that videos without any request still appear in the list.
        $builder = $this->select('videos.*, r.status as access_status, r.expired_at')
                        ->join('video_access_requests r', "r.video_id = videos.id AND r.user_id = {$userId}", 'left');

        if ($search) {
            $builder->groupStart()
                    ->like('videos.title', $search)
                    ->orLike('videos.description', $search)
                    ->groupEnd();
        }

        return $builder->findAll();
    }
}
