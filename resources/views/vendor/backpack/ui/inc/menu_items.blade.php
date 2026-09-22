{{-- Custom Backpack v7 Menu Navigation for UM Rank Predictor Admin --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i>
        <span class="nav-link-title">{{ trans('backpack::base.dashboard') }}</span>
    </a>
</li>

<li class="nav-separator">MASTER DATA</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('state') }}">
        <i class="la la-map-marked-alt nav-icon"></i>
        <span class="nav-link-title">States</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('category') }}">
        <i class="la la-tags nav-icon"></i>
        <span class="nav-link-title">Categories</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('shift') }}">
        <i class="la la-clock nav-icon"></i>
        <span class="nav-link-title">Shifts</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('exam-authority') }}">
        <i class="la la-university nav-icon"></i>
        <span class="nav-link-title">Exam Authorities</span>
    </a>
</li>

<li class="nav-separator">EXAMS</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('exam') }}">
        <i class="la la-graduation-cap nav-icon"></i>
        <span class="nav-link-title">Exams</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('exam-cycle') }}">
        <i class="la la-calendar-check nav-icon"></i>
        <span class="nav-link-title">Exam Cycles</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('exam-stage') }}">
        <i class="la la-list-ol nav-icon"></i>
        <span class="nav-link-title">Exam Stages</span>
    </a>
</li>

<li class="nav-separator">PREDICTION</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('prediction-model') }}">
        <i class="la la-calculator nav-icon"></i>
        <span class="nav-link-title">Prediction Models</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('cutoff') }}">
        <i class="la la-chart-line nav-icon"></i>
        <span class="nav-link-title">Cutoffs</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('vacancy') }}">
        <i class="la la-briefcase nav-icon"></i>
        <span class="nav-link-title">Vacancies</span>
    </a>
</li>

<li class="nav-separator">PUBLIC CONTENT</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('notice') }}">
        <i class="la la-bullhorn nav-icon"></i>
        <span class="nav-link-title">Notices</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('important-link') }}">
        <i class="la la-link nav-icon"></i>
        <span class="nav-link-title">Important Links</span>
    </a>
</li>

<li class="nav-separator">CANDIDATES</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('candidate-submission') }}">
        <i class="la la-file-alt nav-icon"></i>
        <span class="nav-link-title">Candidate Submissions</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('prediction-result') }}">
        <i class="la la-trophy nav-icon"></i>
        <span class="nav-link-title">Prediction Results</span>
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('submission-risk-log') }}">
        <i class="la la-shield-alt nav-icon"></i>
        <span class="nav-link-title">Submission Risk Logs</span>
    </a>
</li>

<li class="nav-separator">USERS</li>

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('user') }}">
        <i class="la la-users-cog nav-icon"></i>
        <span class="nav-link-title">Admin Users</span>
    </a>
</li>
