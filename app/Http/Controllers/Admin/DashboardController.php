<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidateSubmission;
use App\Models\Exam;
use App\Models\ExamCycle;
use App\Models\Notice;
use App\Models\PredictionModel;
use App\Models\PredictionResult;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = [
            'title' => trans('backpack::base.dashboard'),
            'breadcrumbs' => [
                trans('backpack::crud.admin') => backpack_url('dashboard'),
                trans('backpack::base.dashboard') => false,
            ],
            'totalExams' => Exam::count(),
            'activeExams' => Exam::where('status', 'ACTIVE')->count(),
            'activeExamCycles' => ExamCycle::where('status', 'ONGOING')->count(),
            'activePredictionModels' => PredictionModel::where('status', 'ACTIVE')->count(),
            'totalSubmissions' => CandidateSubmission::count(),
            'totalResults' => PredictionResult::count(),
            'flaggedSubmissions' => CandidateSubmission::whereIn('trust_status', ['FLAGGED', 'BLOCKED'])->count(),
            'activeNotices' => Notice::where('status', 'ACTIVE')->count(),
        ];

        return view('admin.dashboard', $data);
    }
}
