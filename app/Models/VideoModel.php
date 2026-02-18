<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model Video
 * Mengelola tabel 'videos'.
 */
class VideoModel extends Model
{
    protected $table            = 'videos';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['title', 'description', 'video_path', 'duration']; 
    protected $updatedField     = 'updated_at';

    /**
     * Ambil data video sekaligus status akses-nya untuk user tertentu.
     * Menggunakan correlated subquery biar gak N+1 dan menghindari duplikasi row.
     */
    public function getVideosWithAccess(int $userId)
    {
        // Query Builder dimulai dari tabel videos
        // Kita select semua kolom video, plus status & expired_at dari request terakhir user ini.
        // Pake Correlated Subquery di SELECT biar efisien dan gak duplikat row kayak kalau pake JOIN biasan.
        $builder = $this->select('videos.*, 
            (SELECT status FROM video_access_requests WHERE video_id = videos.id AND user_id = ' . $this->db->escape($userId) . ' ORDER BY created_at DESC LIMIT 1) as access_status,
            (SELECT expired_at FROM video_access_requests WHERE video_id = videos.id AND user_id = ' . $this->db->escape($userId) . ' ORDER BY created_at DESC LIMIT 1) as expired_at
        ');

        return $builder->findAll();
    }
}
