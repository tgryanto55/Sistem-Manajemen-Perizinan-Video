<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VideoModel;

/**
 * Controller untuk mengelola data video (Admin).
 */
class VideoController extends BaseController
{
    /**
     * Tampilkan halaman manajemen video.
     */
    public function index()
    {
        // Inisialisasi model video
        $videoModel = new VideoModel();
        // Ambil semua data video
        $data['videos'] = $videoModel->findAll();
        // Tampilkan view utama
        return view('admin/videos/index', $data);
    }

    /**
     * Tampilkan form pembuatan video (via modal).
     */
    public function create()
    {
        return view('admin/videos/create');
    }

    /**
     * Simpan data video baru (Create).
     * 
     * HTMX Support:
     * - Refresh parsial daftar video.
     * - Trigger toast 'Video added'.
     * - Trigger event 'videoSaved' buat nutup modal.
     */
    public function store()
    {
        // Jika ada ID, berarti update, lempar ke method update
        if ($this->request->getPost('id')) {
            return $this->update($this->request->getPost('id'));
        }

        // Aturan validasi
        $rules = [
            'title'       => 'required|min_length[3]',
            'description' => 'required',
        ];

        // Jalankan validasi
        if (!$this->validate($rules)) {
            // Jika gagal, kembalikan dengan error & buka modal create lagi
            return redirect()->to('/admin/videos')->withInput()->with('errors', $this->validator->getErrors())->with('show_create_modal', true);
        }

        // Validasi format URL video
        $videoPath = $this->request->getPost('video_url');
        if (!filter_var($videoPath, FILTER_VALIDATE_URL)) {
            return redirect()->to('/admin/videos')->withInput()->with('error', 'Invalid Video URL')->with('show_create_modal', true);
        }

        // Siapkan data simpan
        $videoModel = new VideoModel();
        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'video_path'  => $videoPath,
            'duration'    => 0 // Placeholder durasi, nanti bisa dihitung otomatis
        ];

        // Simpan ke database
        if ($videoModel->insert($data)) {
            return redirect()->to('/admin/videos')->with('success', 'Video added successfully.');
        }

        return redirect()->to('/admin/videos')->withInput()->with('error', 'Failed to add video.');
    }

    /**
     * Update data video.
     * HTMX Support: Refresh list & Notifikasi.
     */
    public function update($id)
    {
        $videoModel = new VideoModel();
        // Cari video berdasarkan ID
        $video = $videoModel->find($id);

        if (!$video) {
            return redirect()->to('/admin/videos')->with('error', 'Video not found.');
        }

        // Validasi update
        $rules = [
            'title'       => 'required|min_length[3]',
            'description' => 'required',
        ];

        if (!$this->validate($rules)) {
            // Jika gagal, kembalikan error & buka modal edit lagi
             return redirect()->to('/admin/videos')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('show_edit_modal', true)
                ->with('edit_video_id', $id);
        }

        // Siapkan data update
        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ];
        
        // Update URL video cuma kalau diisi
        if ($this->request->getPost('video_url')) {
             $data['video_path'] = $this->request->getPost('video_url');
        }

        // Proses update database
        if ($videoModel->update($id, $data)) {
            return redirect()->to('/admin/videos')->with('success', 'Video updated successfully.');
        }

        return redirect()->to('/admin/videos')->with('error', 'Failed to update video.');
    }

    /**
     * Hapus video.
     * HTMX Support: Hapus elemen tabel & Notifikasi.
     */
    public function delete($id)
    {
        $videoModel = new VideoModel();
        // Eksekusi delete
        if ($videoModel->delete($id)) {
            return redirect()->to('/admin/videos')->with('success', 'Video deleted successfully.');
        }
        return redirect()->to('/admin/videos')->with('error', 'Failed to delete video.');
    }


}
