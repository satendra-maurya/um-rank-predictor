@extends(backpack_view('blank'))

@php
    $breadcrumbs = [
        trans('backpack::crud.admin') => backpack_url('dashboard'),
        trans('backpack::base.dashboard') => false,
    ];
@endphp

@section('header')
    <section class="container-fluid flex justify-between my-3">
        <h2 class="text-capitalize mb-0">
            {{ trans('backpack::base.dashboard') }}
        </h2>
    </section>
@endsection

@section('content')
    <div class="row row-cards">
        
        <!-- Total Exams Card -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-primary text-white avatar">
                                <i class="la la-graduation-cap font-size-24"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ $totalExams }} Exams
                            </div>
                            <div class="text-muted">
                                {{ $activeExams }} Active Exams
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Exam Cycles -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-success text-white avatar">
                                <i class="la la-calendar-check font-size-24"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ $activeExamCycles }} Active Cycles
                            </div>
                            <div class="text-muted">
                                Recruitment Drives
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Prediction Models -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-info text-white avatar">
                                <i class="la la-calculator font-size-24"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ $activePredictionModels }} Prediction Models
                            </div>
                            <div class="text-muted">
                                Configured Algorithms
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Submissions -->
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-warning text-white avatar">
                                <i class="la la-users font-size-24"></i>
                            </span>
                        </div>
                        <div class="col">
                            <div class="font-weight-medium">
                                {{ $totalSubmissions }} Submissions
                            </div>
                            <div class="text-muted">
                                {{ $flaggedSubmissions }} Risk Flagged
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Navigation Links -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="la la-rocket mr-2"></i> Quick Operations</h3>
                </div>
                <div class="card-body">
                    <div class="btn-list">
                        <a href="{{ backpack_url('exam') }}" class="btn btn-outline-primary">
                            <i class="la la-list mr-1"></i> Manage Exams
                        </a>
                        <a href="{{ backpack_url('exam-cycle') }}" class="btn btn-outline-success">
                            <i class="la la-calendar mr-1"></i> Exam Cycles
                        </a>
                        <a href="{{ backpack_url('notice') }}" class="btn btn-outline-info">
                            <i class="la la-bullhorn mr-1"></i> Publish Notice
                        </a>
                        <a href="{{ backpack_url('candidate-submission') }}" class="btn btn-outline-warning">
                            <i class="la la-user-check mr-1"></i> Review Submissions
                        </a>
                        <a href="{{ backpack_url('user') }}" class="btn btn-outline-secondary">
                            <i class="la la-user-cog mr-1"></i> Admin Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
