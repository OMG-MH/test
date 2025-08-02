<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ManageStatus;
use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rules\File;

class JobCategoryController extends Controller
{
    function index() {
        $pageTitle  = 'Job Categories';
        $categories = JobCategory::withCount(['jobs' => fn($query) => $query->approved()])
            ->searchable(['name'])
            ->latest()
            ->paginate(getPaginate());

        return view('admin.page.jobCategories', compact('pageTitle', 'categories'));
    }

    function store(int $id = 0) {
        $imageValidation = $id ? 'nullable' : 'required';

        $this->validate(request(), [
            'image'       => [$imageValidation, File::types(['png', 'jpg', 'jpeg'])],
            'name'        => 'required|string|max:40|unique:job_categories,name,' . $id,
            'description' => 'required|string',
        ]);

        if ($id) {
            try {
                $category = JobCategory::findOrFail($id);
                $message  = 'Category successfully updated';
            } catch (ModelNotFoundException) {
                $toast[] = ['error', 'Category not found'];

                return back()->with('toasts', $toast);
            }
        } else {
            $category = new JobCategory();
            $message  = 'Category successfully added';
        }

        if (request()->hasFile('image')) {
            try {
                $category->image = fileUploader(request()->file('image'), getFilePath('jobCategory'), null, @$category->image);
            } catch (Exception) {
                $toast[] = ['error', 'Image uploading process has failed'];

                return back()->with('toasts', $toast);
            }
        }

        $category->name        = request('name');
        $category->slug        = slug(request('name'));
        $category->description = request('description');
        $category->save();

        $toast[] = ['success', $message];

        return back()->with('toasts', $toast);
    }

    function status(int $id) {
        return JobCategory::changeStatus($id);
    }

    function updateFeatured(int $id) {
        try {
            $category              = JobCategory::findOrFail($id);
            $category->is_featured = $category->is_featured == ManageStatus::YES ? ManageStatus::NO : ManageStatus::YES;
            $category->save();

            $toast[] = ['success', 'Featured status has been changed successfully'];

            return back()->with('toasts', $toast);
        } catch (ModelNotFoundException) {
            $toast[] = ['error', 'Category not found'];

            return back()->with('toasts', $toast);
        }
    }
}
