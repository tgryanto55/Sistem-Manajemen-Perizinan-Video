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

        if ($action->execute($id, $durationH, $durationM)) {
            // Cek apakah request dari HTMX
            if ($this->request->hasHeader('HX-Request')) {
                $requestModel = new VideoAccessRequestModel();
                // Ambil data terbaru untuk baris tabel yang diupdate
                $data['requests'] = [$requestModel->getRequestWithDetails($id)];
                // Kirim balik partial view dan trigger toast notifikasi
                return $this->response
                    ->setHeader('HX-Trigger', json_encode(['showToast' => ['message' => 'Request approved successfully.', 'type' => 'success']]))
                    ->setBody(view('admin/requests/_rows', $data));
            }
            // Fallback untuk request biasa (non-HTMX)
            return redirect()->back()->with('success', 'Request approved successfully.');
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

        // Hitung ulang waktu expired menggunakan service
        $durationService = new \App\Services\VideoDurationService();
        $approvedAt = $request['approved_at'] ?? \CodeIgniter\I18n\Time::now()->toDateTimeString();
        $expiredAt  = $durationService->calculateExpiry($approvedAt, $durationH, $durationM);

        // Update data expired_at di database
        if ($requestModel->update($id, ['expired_at' => $expiredAt])) {
            // Cek apakah request dari HTMX
            if ($this->request->hasHeader('HX-Request')) {
                // Ambil data terbaru untuk update tampilan
                $data['requests'] = [$requestModel->getRequestWithDetails($id)];
                // Kirim response partial header dan body
                return $this->response
                    ->setHeader('HX-Trigger', json_encode(['showToast' => ['message' => 'Access duration updated.', 'type' => 'success']]))
                    ->setBody(view('admin/requests/_rows', $data));
            }
            return redirect()->back()->with('success', 'Access duration updated.');
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
            // Jika request HTMX, kirim respon kosong untuk menghapus elemen DOM
            if ($this->request->hasHeader('HX-Request')) {
                return $this->response
                    ->setHeader('HX-Trigger', json_encode(['showToast' => ['message' => 'Request removed.', 'type' => 'success']]))
                    ->setBody('');
            }
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

        if ($action->execute($id)) {
            // Jika HTMX, kirim update baris tabel dengan status 'rejected'
            if ($this->request->hasHeader('HX-Request')) {
                $requestModel = new VideoAccessRequestModel();
                $data['requests'] = [$requestModel->getRequestWithDetails($id)];
                return $this->response
                    ->setHeader('HX-Trigger', json_encode(['showToast' => ['message' => 'Request rejected.', 'type' => 'success']]))
                    ->setBody(view('admin/requests/_rows', $data));
            }
            return redirect()->back()->with('success', 'Request rejected.');
        }

        return redirect()->back()->with('error', 'Failed to reject request.');
    }

    /**
     * Helper list rows untuk refresh tabel via HTMX.
     */
    public function listRows()
    {
        $requestModel = new VideoAccessRequestModel();
        // Ambil semua data request untuk dirender ulang
        $data['requests'] = $requestModel->getRequestsWithDetails();
        return view('admin/requests/_rows', $data);
    }
}
