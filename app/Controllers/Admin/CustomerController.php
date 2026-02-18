<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Actions\User\CreateCustomerAction;

/**
 * Controller untuk mengelola data customer (Admin).
 */
class CustomerController extends BaseController
{
    /**
     * Tampilkan daftar customer.
     */
    public function index()
    {
        // Inisialisasi model user
        $userModel = new UserModel();
        // Ambil semua user dengan role 'customer'
        $data['customers'] = $userModel->where('role', 'customer')->findAll();
        // Tampilkan view
        return view('admin/customers/index', $data);
    }

    /**
     * Simpan data customer baru (Create).
     * 
     * HTMX Support:
     * - Refresh parsial daftar customer.
     * - Trigger toast 'Customer created'.
     * - Trigger event 'customerSaved' buat nutup modal di frontend.
     */
    public function store()
    {
        // Jika ada ID, berarti ini update, alihkan ke method update
        if ($this->request->getPost('id')) {
            return $this->update($this->request->getPost('id'));
        }

        // Aturan validasi input
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        // Jalankan validasi
        if (!$this->validate($rules)) {
            // Jika gagal, kembalikan dengan error dan buka modal create lagi
            return redirect()->to('/admin/customers')->withInput()->with('errors', $this->validator->getErrors())->with('show_create_modal', true);
        }

        // Siapkan Action untuk logika pembuatan customer
        $action = new CreateCustomerAction();
        // Ambil data dari input form
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        // Eksekusi action
        if ($action->execute($data)) {
            // Cek apakah request dari HTMX
            if ($this->request->hasHeader('HX-Request')) {
                $userModel = new UserModel();
                // Siapkan data terbaru untuk dirender ulang (refresh list)
                $viewData['customers'] = $userModel->where('role', 'customer')->findAll();
                // Kirim balik partial view, trigger toast, dan event tutup modal
                return $this->response
                    ->setHeader('HX-Trigger', json_encode([
                        'showToast' => ['message' => 'Customer created successfully.', 'type' => 'success'],
                        'customerSaved' => true 
                    ]))
                    ->setBody(view('admin/customers/_rows', $viewData));
            }
            // Fallback untuk non-HTMX
            return redirect()->to('/admin/customers')->with('success', 'Customer created successfully.');
        }

        return redirect()->to('/admin/customers')->withInput()->with('error', 'Failed to create customer.');
    }

    /**
     * Update data customer.
     * HTMX Support: Refresh list & Notifikasi.
     */
    public function update($id)
    {
        $userModel = new UserModel();
        // Cari data customer
        $customer = $userModel->find($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found.');
        }

        // Aturan validasi update (email unik kecuali punya diri sendiri)
        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
        ];

        // Password opsional saat update, validasi cuma kalau diisi
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        // Jalankan validasi
        if (!$this->validate($rules)) {
             // Jika gagal, kembalikan error dan buka modal edit lagi
             return redirect()->to('/admin/customers')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('show_edit_modal', true)
                ->with('edit_customer_id', $id);
        }

        // Siapkan data update
        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        // Tambahkan password ke data update jika diisi
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        // Proses update ke database
        if ($userModel->update($id, $data)) {
            // Cek HTMX request
            if ($this->request->hasHeader('HX-Request')) {
                // Ambil data terbaru
                $viewData['customers'] = $userModel->where('role', 'customer')->findAll();
                // Kirim respon update tabel dan trigger
                return $this->response
                    ->setHeader('HX-Trigger', json_encode([
                        'showToast' => ['message' => 'Customer updated successfully.', 'type' => 'success'],
                        'customerSaved' => true
                    ]))
                    ->setBody(view('admin/customers/_rows', $viewData));
            }
            return redirect()->to('/admin/customers')->with('success', 'Customer updated successfully.');
        }

        return redirect()->to('/admin/customers')->with('error', 'Failed to update customer.');
    }

    /**
     * Hapus customer.
     * HTMX Support: Hapus elemen tabel & Notifikasi.
     */
    public function delete($id)
    {
        $userModel = new UserModel();
        // Eksekusi delete
        if ($userModel->delete($id)) {
            // Jika HTMX, kirim respon kosong untuk hapus elemen UI
            if ($this->request->hasHeader('HX-Request')) {
                return $this->response
                    ->setHeader('HX-Trigger', json_encode([
                        'showToast' => ['message' => 'Customer deleted successfully.', 'type' => 'success']
                    ]))
                    ->setBody('');
            }
            return redirect()->to('/admin/customers')->with('success', 'Customer deleted successfully.');
        }
        return redirect()->to('/admin/customers')->with('error', 'Failed to delete customer.');
    }

    /**
     * Helper list rows untuk refresh tabel via HTMX.
     */
    public function listRows()
    {
        $userModel = new UserModel();
        // Ambil data customer terbaru
        $data['customers'] = $userModel->where('role', 'customer')->findAll();
        return view('admin/customers/_rows', $data);
    }
}
