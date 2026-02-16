<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\VideoModel;

/**
 * VideoController (Admin)
 * 
 * Manages the video library from the administrator's perspective.
 * Handles listing, adding, updating, and deleting video entries.
 */
class VideoController extends BaseController
{
    /**
     * Display the main video management table.
     */
    public function index()
    {
        $videoModel = new VideoModel();
        $data['videos'] = $videoModel->findAll();
        // Renders the full page layout for standard requests.
        return view('admin/videos/index', $data);
    }

    /**
     * Show the creation form (usually rendered inside a modal).
     */
    public function create()
    {
        return view('admin/videos/create');
    }

    /**
     * Handles both creation and update logic based on the presence of an 'id'.
     */
    public function store()
    {
        // Basic validation for title and description.
        $rules = [
            'title'       => 'required|min_length[3]',
            'description' => 'required',
        ];

        // If 'id' is present, we pivot to the update method.
        if ($this->request->getPost('id')) {
            return $this->update($this->request->getPost('id'));
        }

        // Validate standard input.
        if (!$this->validate($rules)) {
            return redirect()->to('/admin/videos')->withInput()->with('errors', $this->validator->getErrors())->with('show_create_modal', true);
        }

        // Check if the provided video path is a valid URL (e.g., YouTube).
        $videoPath = $this->request->getPost('video_url');
        if (!filter_var($videoPath, FILTER_VALIDATE_URL)) {
            return redirect()->to('/admin/videos')->withInput()->with('error', 'Invalid Video URL')->with('show_create_modal', true);
        }

        $videoModel = new VideoModel();
        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'video_path'  => $videoPath,
            'duration'    => 0 // Placeholder for duration calculation
        ];

        if ($videoModel->insert($data)) {
            // HTMX Support: If it's an AJAX request, we return just the updated table rows 
            // and trigger a success toast via headers.
            if ($this->request->hasHeader('HX-Request')) {
                $viewData['videos'] = $videoModel->findAll();
                return $this->response
                    ->setHeader('HX-Trigger', json_encode([
                        'showToast' => ['message' => 'Video added successfully.', 'type' => 'success'],
                        'videoSaved' => true // Custom event for Alpine.js to close modals
                    ]))
                    ->setBody(view('admin/videos/_rows', $viewData));
            }
            return redirect()->to('/admin/videos')->with('success', 'Video added successfully.');
        }

        return redirect()->to('/admin/videos')->withInput()->with('error', 'Failed to add video.');
    }

    /**
     * Updates an existing video entry.
     */
    public function update($id)
    {
        $videoModel = new VideoModel();
        $video = $videoModel->find($id);

        if (!$video) {
            return redirect()->to('/admin/videos')->with('error', 'Video not found.');
        }

        $rules = [
            'title'       => 'required|min_length[3]',
            'description' => 'required',
        ];

        if (!$this->validate($rules)) {
             return redirect()->to('/admin/videos')
                ->withInput()
                ->with('errors', $this->validator->getErrors())
                ->with('show_edit_modal', true)
                ->with('edit_video_id', $id);
        }

        $data = [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ];
        
        if ($this->request->getPost('video_url')) {
             $data['video_path'] = $this->request->getPost('video_url');
        }

        if ($videoModel->update($id, $data)) {
            // HTMX Support: Returns only the table rows for a seamless partial update.
            if ($this->request->hasHeader('HX-Request')) {
                $viewData['videos'] = $videoModel->findAll();
                return $this->response
                    ->setHeader('HX-Trigger', json_encode([
                        'showToast' => ['message' => 'Video updated successfully.', 'type' => 'success'],
                        'videoSaved' => true
                    ]))
                    ->setBody(view('admin/videos/_rows', $viewData));
            }
            return redirect()->to('/admin/videos')->with('success', 'Video updated successfully.');
        }

        return redirect()->to('/admin/videos')->with('error', 'Failed to update video.');
    }

    /**
     * Deletes a video entry.
     */
    public function delete($id)
    {
        $videoModel = new VideoModel();
        if ($videoModel->delete($id)) {
            if ($this->request->hasHeader('HX-Request')) {
                return $this->response
                    ->setHeader('HX-Trigger', json_encode([
                        'showToast' => ['message' => 'Video deleted successfully.', 'type' => 'success']
                    ]))
                    ->setBody(''); // Return empty for HTMX to remove the row
            }
            return redirect()->to('/admin/videos')->with('success', 'Video deleted successfully.');
        }
        return redirect()->to('/admin/videos')->with('error', 'Failed to delete video.');
    }

    /**
     * Helper for HTMX to fetch only the table rows without the full layout.
     */
    public function listRows()
    {
        $videoModel = new VideoModel();
        $data['videos'] = $videoModel->findAll();
        return view('admin/videos/_rows', $data);
    }
}
