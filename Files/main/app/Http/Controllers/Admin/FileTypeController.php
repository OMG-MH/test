<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileType;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FileTypeController extends Controller
{
    function index() {
        $pageTitle = 'File Types';
        $fileTypes = FileType::searchable(['type'])->latest()->paginate(getPaginate());

        return view('admin.page.fileTypes', compact('pageTitle', 'fileTypes'));
    }

    function store(int $id = 0) {
        $this->validate(request(), [
            'type' => 'required|string|max:40|unique:file_types,type,' . $id,
        ]);

        if ($id) {
            try {
                $fileType = FileType::findOrFail($id);
                $message  = 'File type successfully updated';
            } catch (ModelNotFoundException) {
                $toast[] = ['error', 'File type not found'];

                return back()->with('toasts', $toast);
            }
        } else {
            $fileType = new FileType();
            $message  = 'File type successfully added';
        }

        $fileType->type = request('type');
        $fileType->save();

        $toast[] = ['success', $message];

        return back()->with('toasts', $toast);
    }

    function status(int $id) {
        return FileType::changeStatus($id);
    }
}
