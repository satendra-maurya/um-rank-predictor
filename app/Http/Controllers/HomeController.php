<?php

namespace App\Http\Controllers;

use App\Enums\ActiveStatus;
use App\Models\ExamAuthority;
use App\Models\ImportantLink;
use App\Models\Notice;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $authorities = ExamAuthority::where('status', '!=', ActiveStatus::INACTIVE)->get();
        $hasNotices = Notice::where('status', ActiveStatus::ACTIVE)->exists();
        $hasImportantLinks = ImportantLink::where('status', ActiveStatus::ACTIVE)->exists();

        return view('home', compact('authorities', 'hasNotices', 'hasImportantLinks'));
    }

    public function availableExams(string $authoritySlug): View
    {
        $authority = ExamAuthority::where('slug', $authoritySlug)
            ->where('status', '!=', ActiveStatus::INACTIVE)
            ->with(['exams'])
            ->first();

        if (! $authority) {
            $authority = ExamAuthority::where('short_name', strtolower($authoritySlug))
                ->where('status', '!=', ActiveStatus::INACTIVE)
                ->with(['exams'])
                ->first();
        }

        if (! $authority) {
            abort(404);
        }

        return view('public.available-exams', compact('authority'));
    }

    public function howItWorks(): View
    {
        return view('pages.how-it-works');
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function privacyPolicy(): View
    {
        return view('pages.privacy-policy');
    }

    public function privacyNotice(): View
    {
        return view('pages.privacy-notice');
    }
}
