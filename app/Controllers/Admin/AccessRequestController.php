<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VideoAccessRequestModel;
use App\Actions\Video\ApproveVideoAccessAction;
use App\Actions\Video\RejectVideoAccessAction;

/**
 * Controller untuk mengelola request akses video (Admin).
 */
class AccessRequestController extends BaseController
{
    /**
     * Tampilkan semua request.
     */
    public function index()
    {
        // Siapkan model request
        $requestModel = new VideoAccessRequestModel();
        // Ambil data request beserta detail user dan videonya
        $data['requests'] = $requestModel->getRequestsWithDetails();
        // Tampilkan view
        return view('admin/requests/index', $data);
    }

    /**
     * Approve request dan set durasi akses.
     * 
     * HTMX Support:
     * - Refresh parsial (cuma baris tabel yang berubah).
     * - Trigger toast notifikasi sukses di frontend.
     */
    public function approve($id)
    {
        // Ambil input durasi dari form (default 24 jam)
        $durationH = (int) ($this->request->getPost('duration_h') ?? 24);
        $durationM = (int) ($this->request->getPost('duration_m') ?? 0);
        
        // Panggil action untuk proses approve
        $action = new ApproveVideoAccessAction();

        try {
            if ($action->execute($id, $durationH, $durationM)) {
                return redirect()->back()->with('success', 'Request approved successfully.');
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            log_message('error', '[ApproveAccess] Database error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Database error. Please try again.');
        } catch (\Exception $e) {
            log_message('error', '[ApproveAccess] General error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An unexpected error occurred.');
        }

        return redirect()->back()->with('error', 'Failed to approve request.');
    }

    /**
     * Update durasi akses yang sudah diapprove.
     * HTMX Support: Update baris tabel & notifikasi.
     */
    public function update($id)
    {
        // Ambil input durasi baru
        $durationH = (int) ($this->request->getPost('duration_h') ?? 0);
        $durationM = (int) ($this->request->getPost('duration_m') ?? 0);
        
        $requestModel = new VideoAccessRequestModel();
        // Cari data request berdasarkan ID
        $request = $requestModel->find($id);

        // Jika tidak ditemukan, kembalikan error
        if (!$request) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        try {
            // Hitung ulang waktu expired menggunakan service
            $durationService = new \App\Services\VideoDurationService();
            $approvedAt = $request['approved_at'] ?? \CodeIgniter\I18n\Time::now()->toDateTimeString();
            $expiredAt  = $durationService->calculateExpiry($approvedAt, $durationH, $durationM);

            // Update data expired_at di database
            if ($requestModel->update($id, ['expired_at' => $expiredAt])) {
                return redirect()->back()->with('success', 'Access duration updated.');
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
             log_message('error', '[UpdateAccess] Database error: ' . $e->getMessage());
             return redirect()->back()->with('error', 'Database error.');
        }

        return redirect()->back()->with('error', 'Failed to update duration.');
    }

    /**
     * Hapus record request.
     * HTMX Support: Hapus elemen dari tabel (return string kosong).
     */
    public function delete($id)
    {
        $requestModel = new VideoAccessRequestModel();
        // Jalankan perintah delete
        if ($requestModel->delete($id)) {
            return redirect()->back()->with('success', 'Request removed.');
        }

        return redirect()->back()->with('error', 'Failed to remove request.');
    }

    /**
     * Tolak request yang pending.
     * HTMX Support: Refresh status jadi rejected.
     */
    public function reject($id)
    {
        // Panggil action reject
        $action = new RejectVideoAccessAction();

        try {
            if ($action->execute($id)) {
                return redirect()->back()->with('success', 'Request rejected.');
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            log_message('error', '[RejectAccess] Database error: ' . $e->getMessage());
             return redirect()->back()->with('error', 'Database error.');
        }

        return redirect()->back()->with('error', 'Failed to reject request.');
    }


}
