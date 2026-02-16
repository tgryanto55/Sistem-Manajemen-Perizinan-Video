<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VideoAccessRequestModel;
use App\Actions\Video\ApproveVideoAccessAction;
use App\Actions\Video\RejectVideoAccessAction;

/**
 * AccessRequestController (Admin)
 * 
 * Manages the workflow for approving or rejecting video access requests.
 * It integrates with ApproveVideoAccessAction and RejectVideoAccessAction 
 * for clean separation of business logic.
 */
class AccessRequestController extends BaseController
{
    /**
     * Lists all requests with detailed associations (User & Video names).
     */
    public function index()
    {
        $requestModel = new VideoAccessRequestModel();
        $data['requests'] = $requestModel->getRequestsWithDetails();
        return view('admin/requests/index', $data);
    }

    /**
     * Approves a request and sets the expiration time.
     */
    public function approve($id)
    {
        // Admins can specify hours and minutes of access. Defaults to 24h.
        $durationH = (int) ($this->request->getPost('duration_h') ?? 24);
        $durationM = (int) ($this->request->getPost('duration_m') ?? 0);
        
        $action = new ApproveVideoAccessAction();

        if ($action->execute($id, $durationH, $durationM)) {
            if ($this->request->hasHeader('HX-Request')) {
                $requestModel = new VideoAccessRequestModel();
                $data['requests'] = [$requestModel->getRequestWithDetails($id)];
                return $this->response
                    ->setHeader('HX-Trigger', json_encode(['showToast' => ['message' => 'Request approved successfully.', 'type' => 'success']]))
                    ->setBody(view('admin/requests/_rows', $data));
            }
            return redirect()->back()->with('success', 'Request approved successfully.');
        }

        return redirect()->back()->with('error', 'Failed to approve request.');
    }

    /**
     * Manually updates the duration of an already approved request.
     */
    public function update($id)
    {
        $durationH = (int) ($this->request->getPost('duration_h') ?? 0);
        $durationM = (int) ($this->request->getPost('duration_m') ?? 0);
        
        $requestModel = new VideoAccessRequestModel();
        $request = $requestModel->find($id);

        if (!$request) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // We use VideoDurationService to recalculate the new expiry date.
        $durationService = new \App\Services\VideoDurationService();
        $approvedAt = $request['approved_at'] ?? \CodeIgniter\I18n\Time::now()->toDateTimeString();
        $expiredAt  = $durationService->calculateExpiry($approvedAt, $durationH, $durationM);

        if ($requestModel->update($id, ['expired_at' => $expiredAt])) {
            if ($this->request->hasHeader('HX-Request')) {
                $data['requests'] = [$requestModel->getRequestWithDetails($id)];
                return $this->response
                    ->setHeader('HX-Trigger', json_encode(['showToast' => ['message' => 'Access duration updated.', 'type' => 'success']]))
                    ->setBody(view('admin/requests/_rows', $data));
            }
            return redirect()->back()->with('success', 'Access duration updated.');
        }

        return redirect()->back()->with('error', 'Failed to update duration.');
    }

    /**
     * Deletes a request record.
     */
    public function delete($id)
    {
        $requestModel = new VideoAccessRequestModel();
        if ($requestModel->delete($id)) {
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
     * Rejects a pending request.
     */
    public function reject($id)
    {
        $action = new RejectVideoAccessAction();

        if ($action->execute($id)) {
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
     * Helper for HTMX to refresh the entire request table.
     */
    public function listRows()
    {
        $requestModel = new VideoAccessRequestModel();
        $data['requests'] = $requestModel->getRequestsWithDetails();
        return view('admin/requests/_rows', $data);
    }
}
