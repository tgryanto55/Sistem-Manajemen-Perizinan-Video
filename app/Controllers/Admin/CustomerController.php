<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Actions\User\CreateCustomerAction;

/**
 * CustomerController (Admin)
 * 
 * Manages the list of customers from the administrator's perspective.
 * Uses CreateCustomerAction to encapsulate user creation logic.
 */
class CustomerController extends BaseController
{
    /**
     * Lists all users with the 'customer' role.
     */
    public function index()
    {
        $userModel = new UserModel();
        $data['customers'] = $userModel->where('role', 'customer')->findAll();
        return view('admin/customers/index', $data);
    }

    /**
     * Stores a new customer or updates an existing one if ID is present.
     */
    public function store()
    {
        if ($this->request->getPost('id')) {
            return $this->update($this->request->getPost('id'));
        }

        // Validation rules for customer registration.
        $rules = [
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/admin/customers')->withInput()->with('errors', $this->validator->getErrors())->with('show_create_modal', true);
        }

        // We use the specialized Action class here to handle the creation logic.
        $action = new CreateCustomerAction();
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
        ];

        if ($action->execute($data)) {
            // HTMX Support: seamless partial update for the customer list.
            if ($this->request->hasHeader('HX-Request')) {
                $userModel = new UserModel();
                $viewData['customers'] = $userModel->where('role', 'customer')->findAll();
                return $this->response
                    ->setHeader('HX-Trigger', json_encode([
                        'showToast' => ['message' => 'Customer created successfully.', 'type' => 'success'],
                        'customerSaved' => true
                    ]))
                    ->setBody(view('admin/customers/_rows', $viewData));
            }
            return redirect()->to('/admin/customers')->with('success', 'Customer created successfully.');
        }

        return redirect()->to('/admin/customers')->withInput()->with('error', 'Failed to create customer.');
    }

    /**
     * Updates an existing customer's basic info and optionally their password.
     */
    public function update($id)
    {
        $userModel = new UserModel();
        $customer = $userModel->find($id);

        if (!$customer) {
            return redirect()->to('/admin/customers')->with('error', 'Customer not found.');
        }

        $rules = [
            'name'  => 'required|min_length[3]',
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]", // Exclude self from unique check
        ];

        // Password is only validated if it's actually provided (optional update).
        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
        }

        if (!$this->validate($rules)) {
             return redirect()->to('/admin/customers')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('show_edit_modal', true)
                ->with('edit_customer_id', $id);
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }

        if ($userModel->update($id, $data)) {
            if ($this->request->hasHeader('HX-Request')) {
                $viewData['customers'] = $userModel->where('role', 'customer')->findAll();
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
     * Deletes a customer. Note that this might break foreign key constraints 
     * if they have access requests, so ideally use Soft Deletes or manual cleanup.
     */
    public function delete($id)
    {
        $userModel = new UserModel();
        if ($userModel->delete($id)) {
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
     * Helper for HTMX to fetch only the customer table rows.
     */
    public function listRows()
    {
        $userModel = new UserModel();
        $data['customers'] = $userModel->where('role', 'customer')->findAll();
        return view('admin/customers/_rows', $data);
    }
}
