<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\JobSubcategory;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rules\File;

class JobSubcategoryController extends Controller
{
    function index() {
        $pageTitle     = 'Job Subcategories';
        $categories    = JobCategory::active()->get();
        $subcategories = JobSubcategory::with('category')
            ->withCount(['jobs' => fn($query) => $query->approved()])
            ->searchable(['name', 'category:slug'])
            ->latest()
            ->paginate(getPaginate());

        return view('admin.page.jobSubcategories', compact('pageTitle', 'categories', 'subcategories'));
    }

    function store(int $id = 0) {
        $imageValidation = $id ? 'nullable' : 'required';

        $this->validate(request(), [
            'category_id' => 'required|integer|exists:job_categories,id',
            'image'       => [$imageValidation, File::types(['png', 'jpg', 'jpeg'])],
            'name'        => 'required|string|max:40|unique:job_subcategories,name,' . $id,
            'description' => 'required|string',
        ], [
            'category_id.required' => 'The category field is required.',
            'category_id.integer'  => 'The category must be an integer.',
        ]);

        if (!JobCategory::active()->find(request('category_id'))) {
            $toast[] = ['error', 'Category not found'];

            return back()->with('toasts', $toast);
        }

        if ($id) {
            try {
                $subcategory = JobSubcategory::findOrFail($id);
                $message     = 'Subcategory successfully updated';
            } catch (ModelNotFoundException) {
                $toast[] = ['error', 'Subcategory not found'];

                return back()->with('toasts', $toast);
            }
        } else {
            $subcategory = new JobSubcategory();
            $message     = 'Subcategory successfully added';
        }

        if (request()->hasFile('image')) {
            try {
                $subcategory->image = fileUploader(request()->file('image'), getFilePath('jobSubcategory'), null, @$subcategory->image);
            } catch (Exception) {
                $toast[] = ['error', 'Image uploading process has failed'];

                return back()->with('toasts', $toast);
            }
        }

        $subcategory->job_category_id = request('category_id');
        $subcategory->name            = request('name');
        $subcategory->slug            = slug(request('name'));
        $subcategory->description     = request('description');
        $subcategory->save();

        $toast[] = ['success', $message];

        return back()->with('toasts', $toast);
    }

    function status(int $id) {
        return JobSubcategory::changeStatus($id);
    }
}
