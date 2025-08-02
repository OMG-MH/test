<?php

namespace App\Http\Controllers;

use App\Models\AssignedJob;
use App\Models\Contact;
use App\Models\JobApplication;
use App\Models\JobCategory;
use App\Models\JobPost;
use App\Models\JobSubcategory;
use App\Models\Language;
use App\Models\SiteData;
use App\Constants\ManageStatus;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\File;
use Symfony\Component\HttpFoundation\Response;

class WebsiteController extends Controller
{
    function home() {
        $pageTitle = 'Home';

        // Job Categories
        $categories = JobCategory::whereHas('subcategories', function (Builder $builder) {
            $builder->where('status', ManageStatus::ACTIVE);
        })
            ->active()
            ->orderBy('name')
            ->get();

        // Featured Job Categories
        $featuredCategories = JobCategory::active()
            ->featured()
            ->withCount(['jobs' => fn ($query) => $query->approved()])
            ->orderBy('name')
            ->get();

        // Latest Job Posts
        $latestJobs = JobPost::approved()->latest()->take(8)->get();

        // Top Freelancers
        $topFreelancers = $this->getFreelancers()->take(10)->get();

        return view($this->activeTheme . 'page.home', compact('pageTitle', 'categories', 'featuredCategories', 'latestJobs', 'topFreelancers'));
    }

    function jobCategories() {
        $pageTitle  = 'Job Categories';
        $categories = JobCategory::active()
            ->withCount(['jobs' => fn ($query) => $query->approved()])
            ->orderBy('name')
            ->paginate(getPaginate());

        return view($this->activeTheme . 'page.jobCategories', compact('pageTitle', 'categories'));
    }

    function jobs() {
        $pageTitle     = 'Job Listing';
        $category      = request('category', '');
        $subcategories = request('subcategories', '');
        $title         = request('title', '');
        $sortBy        = request('sort_by', '');
        $filterBy      = request('filter_by', '');

        $categories = JobCategory::whereHas('subcategories', function (Builder $builder) {
            $builder->where('status', ManageStatus::ACTIVE);
        })
            ->with(['subcategories' => fn ($query) => $query->active()->orderBy('name')])
            ->active()
            ->orderBy('name')
            ->get();

        $jobs = JobPost::when($category || $title, function (Builder $builder) use ($category, $title) {
            $builder->when($category, function (Builder $query) use ($category) {
                $categoryId = JobCategory::where('slug', $category)->pluck('id')->first();

                $query->where('category_id', $categoryId);
            })
                ->when($title, function (Builder $query) use ($title) {
                    $query->where('title', 'like', '%' . $title . '%');
                });
        })
            ->when($subcategories, function (Builder $query) use ($subcategories) {
                $subcategoryArray = explode(',', $subcategories);
                $subcategoryIds   = JobSubcategory::whereIn('slug', $subcategoryArray)->pluck('id')->toArray();

                $query->whereIn('subcategory_id', $subcategoryIds);
            })
            ->approved()
            ->when($filterBy, function (Builder $query) use ($filterBy) {
                if ($filterBy === 'today') {
                    $query->whereDate('created_at', today());
                } elseif ($filterBy === 'weekly') {
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($filterBy === 'monthly') {
                    $query->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                }
            })
            ->when($sortBy, function (Builder $query) use ($sortBy) {
                if ($sortBy === 'high-to-low') {
                    $query->orderByDesc('rate');
                } elseif ($sortBy === 'low-to-high') {
                    $query->orderBy('rate');
                }
            }, function (Builder $query) {
                $query->latest();
            })
            ->paginate(getPaginate());

        return view($this->activeTheme . 'page.jobs', compact('pageTitle', 'categories', 'jobs'));
    }

    function jobShow(JobPost $job) {
        $pageTitle = 'Job Details';

        if ($job->status != ManageStatus::JOB_APPROVED) {
            $toast[] = ['error', 'The job is no longer available'];

            return back()->with('toasts', $toast);
        }

        $jobApplicationExists = $job->applications()
            ->where('user_id', auth()->id())
            ->whereIn('status', [
                ManageStatus::JOB_APPLICATION_REJECTED,
                ManageStatus::JOB_APPLICATION_APPROVED,
                ManageStatus::JOB_APPLICATION_PENDING
            ])
            ->exists();

        $job->load('user');

        $seoContents['keywords']           = [];
        $seoContents['social_title']       = $job->title;
        $seoContents['description']        = strLimit($job->description, 150);
        $seoContents['social_description'] = strLimit($job->description, 150);
        $imageSize                         = getFileSize('job');
        $seoContents['image']              = getImage(getFilePath('job') . '/' . $job->image, $imageSize);
        $seoContents['image_size']         = $imageSize;

        return view($this->activeTheme . 'page.jobShow', compact('pageTitle', 'job', 'seoContents', 'jobApplicationExists'));
    }

    function jobApply(JobPost $job) {
        $response = Gate::inspect('apply', [JobApplication::class, $job]);

        if ($response->denied()) {
            $toast[] = ['error', $response->message()];

            return back()->with('toasts', $toast);
        }

        $rules = [
            'applicant_bio' => 'required|string|min:30',
        ];

        if ($job->has_job_proof == ManageStatus::JOB_PROOF_REQUIRED) {
            $extensions = $job->file_types;
            $extensions = array_map(function ($extension) {
                return ltrim($extension, '.');
            }, $extensions);

            $rules['job_proof'] = ['required', File::types($extensions), 'max:5120'];
        }

        $this->validate(request(), $rules);

        if (request()->hasFile('job_proof')) {
            try {
                $jobProof = fileUploader(request()->file('job_proof'), getFilePath('jobProof'));
            } catch (Exception) {
                $toast[] = ['error', 'File uploading process has failed'];

                return back()->with('toasts', $toast);
            }
        }

        // store job application
        $jobApplication                = new JobApplication();
        $jobApplication->user_id       = auth()->id();
        $jobApplication->job_id        = $job->id;
        $jobApplication->applicant_bio = request('applicant_bio');
        $jobApplication->job_proof     = $jobProof ?? null;
        $jobApplication->save();

        // notify the applicant
        notify(auth()->user(), 'JOB_APPLICATION_SUBMIT', [
            'job_code' => $job->job_code,
            'title'    => $job->title,
        ]);

        $toast[] = ['success', 'Your application has been submitted'];

        return back()->with('toasts', $toast);
    }

    function freelancers() {
        $pageTitle      = 'Top Freelancers';
        $topFreelancers = $this->getFreelancers()->paginate(getPaginate());

        return view($this->activeTheme . 'page.freelancers', compact('pageTitle', 'topFreelancers'));
    }

    private function getFreelancers()
    {
        return AssignedJob::query()
            ->selectRaw('assigned_to, COUNT(*) AS completed_jobs_count')
            ->groupBy('assigned_to')
            ->where('status', ManageStatus::ASSIGNED_JOB_COMPLETED)
            ->orderByDesc('completed_jobs_count')
            ->whereHas('userAssignedTo', fn ($query) => $query->activeFreelancer())
            ->with(['userAssignedTo' => function ($query) {
                $query->activeFreelancer()
                    ->with(['freelancerReviews' => function (EloquentBuilder $builder) {
                        $builder->selectRaw('freelancer_id, AVG(rating) AS average_rating, COUNT(*) AS reviews_count')
                            ->groupBy('freelancer_id');
                    }]);
            }]);
    }

    function freelancerShow(string $username) {
        $pageTitle = 'Freelancer Profile';

        try {
            $freelancer = User::withCount([
                'assignedJobs as completed_jobs_count' => fn ($query) => $query->completed(),
                'freelancerReviews as reviews_count'
            ])
                ->withAvg('freelancerReviews as average_rating', 'rating')
                ->with(['freelancerReviews' => fn ($query) => $query->with('author')->take(5)->orderByDesc('id')])
                ->where('username', $username)
                ->active()
                ->firstOrFail();

            // Group reviews by rating and count the number of reviews per rating
            $ratingsBreakdown = $freelancer->freelancerReviews()
                ->selectRaw('rating, COUNT(*) AS count')
                ->groupBy('rating')
                ->orderByDesc('rating')
                ->get();

            // Initialize all ratings to 0
            $ratingsCount = [];

            for ($i = 1; $i <= 5; $i++) $ratingsCount[$i] = 0;

            // Populate ratingsCount with actual counts from ratingsBreakdown
            foreach ($ratingsBreakdown as $item) $ratingsCount[$item->rating] = $item->count;
        } catch (ModelNotFoundException $ex) {
            $toast[] = ['error', 'The freelancer you\'re looking for cannot be found'];

            return back()->with('toasts', $toast);
        }

        return view($this->activeTheme . 'page.freelancerShow', compact('pageTitle', 'freelancer', 'ratingsCount'));
    }

    function freelancerReviews(string $username) {
        $this->validate(request(), [
            'reviews_batch_size' => 'required|integer|gt:0',
        ]);

        $freelancer = User::where('username', $username)->active()->first();

        if (!$freelancer) {
            return response()->json(['error' => 'Freelancer not found'], Response::HTTP_NOT_FOUND);
        }

        $reviewsBatchSize = request('reviews_batch_size');
        $reviews          = $freelancer->freelancerReviews()
            ->with('author')
            ->take($reviewsBatchSize)
            ->orderByDesc('id')
            ->get();

        $reviewsLeft = $freelancer->freelancerReviews()->count() - $reviews->count();

        return response()->json([
            'html'        => view($this->activeTheme . 'partials.freelancerReviews', compact('reviews'))->render(),
            'reviewsLeft' => $reviewsLeft,
        ]);
    }

    function blog() {
        $pageTitle    = 'Latest Articles';
        $blogElements = SiteData::where('data_key', 'blog.element')->orderByDesc('id')->paginate(getPaginate());

        return view($this->activeTheme . 'page.blog', compact('pageTitle', 'blogElements'));
    }

    function blogShow($name, $id) {
        $pageTitle = 'Blog Details';
        $blogData  = SiteData::findOrFail($id);

        $seoContents['keywords']           = $blogData->data_info->meta_keywords ?? [];
        $seoContents['social_title']       = $blogData->data_info->title;
        $seoContents['description']        = strLimit($blogData->data_info->description, 150);
        $seoContents['social_description'] = strLimit($blogData->data_info->description, 150);
        $imageSize                         = '855x500';
        $seoContents['image']              = getImage(activeTheme(true) . 'images/site/blog/' . $blogData->data_info->image, $imageSize);
        $seoContents['image_size']         = $imageSize;

        $recentBlogData = SiteData::where('data_key', 'blog.element')
            ->whereNot('id', $blogData->id)
            ->latest()
            ->limit(4)
            ->get();

        return view($this->activeTheme . 'page.blogShow', compact('pageTitle', 'blogData', 'seoContents', 'recentBlogData'));
    }

    function faq() {
        $pageTitle   = 'FAQs';
        $faqContent  = getSiteData('faq.content', true);
        $faqElements = getSiteData('faq.element', false, null, true);

        return view($this->activeTheme . 'page.faq', compact('pageTitle', 'faqContent', 'faqElements'));
    }

    function contact() {
        $pageTitle       = 'Contact Us';
        $user            = auth()->user();
        $contactContent  = getSiteData('contact_us.content', true);
        $contactElements = getSiteData('contact_us.element', false, null, true);

        return view($this->activeTheme . 'page.contact', compact('pageTitle', 'user', 'contactContent', 'contactElements'));
    }

    function contactStore() {
        $this->validate(request(), [
            'name'    => 'required|string|max:40',
            'email'   => 'required|string|max:40',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $user         = auth()->user();
        $email        = $user ? $user->email : request('email');
        $contactCheck = Contact::where('email', $email)->where('status', ManageStatus::NO)->first();

        if ($contactCheck) {
            $toast[] = ['warning', 'There is an existing contact on our record, kindly wait for the admin\'s response'];

            return back()->withToasts($toast);
        }

        $contact          = new Contact();
        $contact->name    = $user ? $user->fullname : request('name');
        $contact->email   = $email;
        $contact->subject = request('subject');
        $contact->message = request('message');
        $contact->save();

        $toast[] = ['success', 'We have received your message, kindly wait for the admin\'s response'];

        return back()->withToasts($toast);
    }

    function changeLanguage($lang = null) {
        $language = Language::where('code', $lang)->first();

        if (!$language) $lang = 'en';

        session()->put('lang', $lang);

        return back();
    }

    function cookieAccept() {
        Cookie::queue('gdpr_cookie', bs('site_name'), 43200);
    }

    function cookiePolicy() {
        $pageTitle = 'Cookie Policy';
        $cookie    = SiteData::where('data_key', 'cookie.data')->first();

        return view($this->activeTheme . 'page.cookie', compact('pageTitle', 'cookie'));
    }

    function maintenance() {
        if (bs('site_maintenance') == ManageStatus::INACTIVE) return to_route('home');

        $maintenance = SiteData::where('data_key', 'maintenance.data')->first();
        $pageTitle   = $maintenance->data_info->heading;

        return view($this->activeTheme . 'page.maintenance', compact('pageTitle', 'maintenance'));
    }

    function policyPages($slug, $id) {
        $policy    = SiteData::where('id', $id)->where('data_key', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_info->title;

        return view($this->activeTheme . 'page.policy', compact('policy', 'pageTitle'));
    }

    function placeholderImage($size = null) {
        $imgWidth  = explode('x',$size)[0];
        $imgHeight = explode('x',$size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/RobotoMono-Regular.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);

        if ($fontSize <= 9) $fontSize = 9;

        if ($imgHeight < 100 && $fontSize > 30) $fontSize = 30;

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 175, 175, 175);

        imagefill($image, 0, 0, $bgFill);

        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;

        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }
}
