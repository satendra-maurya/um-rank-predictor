<?php

namespace App\Http\Controllers;

use App\Enums\ActiveStatus;
use App\Models\ExamAuthority;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $authorities = ExamAuthority::where('status', '!=', ActiveStatus::INACTIVE)->get();

        return view('home', compact('authorities'));
    }

    public function availableExams(string $authoritySlug): View
    {
        $authority = ExamAuthority::where('slug', $authoritySlug)
            ->where('status', '!=', ActiveStatus::INACTIVE)
            ->with(['exams'])
            ->firstOrFail();

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
}
