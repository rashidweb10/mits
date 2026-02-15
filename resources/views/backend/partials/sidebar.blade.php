<div class="sidenav-menu">

<!-- Brand Logo -->
<a href="" class="logo">
    <span class="logo-light">
        <span class="logo-lg"><img src="{{ asset('assets/backend/img/logo_new.png') }}" alt="logo"></span>
        <span class="logo-sm"><img src="{{ asset('assets/backend/img/logo-sm.png') }}" alt="small logo"></span>
    </span>

    <span class="logo-dark">
        <span class="logo-lg"><img src="{{ asset('assets/backend/img/logo-dark.png') }}" alt="dark logo"></span>
        <span class="logo-sm"><img src="{{ asset('assets/backend/img/logo-sm.png') }}" alt="small logo"></span>
    </span>
</a>

<!-- Sidebar Hover Menu Toggle Button -->
<button class="button-sm-hover">
    <i class="ti ti-circle align-middle"></i>
</button>

<!-- Full Sidebar Menu Close Button -->
<button class="button-close-fullsidebar">
    <i class="ti ti-x align-middle"></i>
</button>

<div data-simplebar>

    <!--- Sidenav Menu -->
    <ul class="side-nav">
        <li class="side-nav-title">Navigation</li>

        <li class="side-nav-item">
            <a href="{{route('backend.dashboard')}}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-dashboard"></i></span>
                <span class="menu-text"> Dashboard </span>
            </a>
        </li> 

        <li class="side-nav-item">
            <a href="{{ route('companies.edit', 1) }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-school"></i></span>
                <span class="menu-text"> Company </span>
            </a>
        </li>        
        
        <li class="side-nav-item">
            <a href="{{ route('pages.index') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-pencil"></i></span>
                <span class="menu-text"> Pages </span>
            </a>
        </li> 

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#blog" aria-expanded="false" aria-controls="sidebarTables"
                class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-notebook"></i></span>
                <span class="menu-text"> Blogs </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="blog">
                <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('blog-categories.index') }}" class="side-nav-link">
                            <span class="menu-text">Categories</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('blogs.index') }}" class="side-nav-link">
                            <span class="menu-text">Blogs</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#sidebarUploads" aria-expanded="false" aria-controls="sidebarTables"
                class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-file-upload"></i></span>
                <span class="menu-text"> Media Uploads </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="sidebarUploads">
                <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('uploaded-files.create') }}" class="side-nav-link">
                            <span class="menu-text">Add New</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('uploaded-files.index') }}" class="side-nav-link">
                            <span class="menu-text">All Uploads</span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>        

        {{-- <li class="side-nav-item">
            <a href="{{ route('students.index') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-users"></i></span>
                <span class="menu-text"> Students </span>
            </a>
        </li>     --}}

        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#users" aria-expanded="false" aria-controls="sidebarTables"
                class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-users"></i></span>
                <span class="menu-text"> Student Management </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="users">
                <ul class="sub-menu">
                <li class="side-nav-item">
                        <a href="{{ route('forms.by', ['form_name' => (auth()->user()->company_id == 1) ? 'enrolments' : 'enrolments']) }}" class="side-nav-link">
                            <span class="menu-text">Enquiries</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('students.index') }}" class="side-nav-link">
                            <span class="menu-text">Registered Students</span>
                        </a>
                    </li>
                    
                </ul>
            </div>
        </li>        
        
        <li class="side-nav-item">
            <a data-bs-toggle="collapse" href="#courses" aria-expanded="false" aria-controls="sidebarTables"
                class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-books"></i></span>
                <span class="menu-text"> Course Management </span>
                <span class="menu-arrow"></span>
            </a>
            <div class="collapse" id="courses">
                <ul class="sub-menu">
                    <li class="side-nav-item">
                        <a href="{{ route('course-categories.index') }}" class="side-nav-link">
                            <span class="menu-text">Categories</span>
                        </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('courses.index') }}" class="side-nav-link">
                            <span class="menu-text">Courses</span>
                        </a>
                    </li>   
                    <li class="side-nav-item">
                        <a href="{{ route('course-materials.index') }}" class="side-nav-link">
                            <span class="menu-text">Materials</span>
                        </a>
                    </li>     
                    {{-- <li class="side-nav-item">
                        <a href="{{ route('quizzes.index') }}" class="side-nav-link">
                            <span class="menu-text">Quizzes</span>
                        </a>
                    </li> --}}
                    {{-- <li class="side-nav-item">
                        <a href="{{ route('course-enrolments.index') }}" class="side-nav-link">
                            <span class="menu-text">Assign Course</span>
                        </a>
                    </li>                                                     --}}
                </ul>
            </div>
        </li> 
        
        <li class="side-nav-item">
            <a href="{{ route('quizzes.index') }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-clipboard-list"></i></span>
                <span class="menu-text"> Exam Management </span>
            </a>
        </li>        

        
    
        {{-- <li class="side-nav-item">
            <a href="{{ route('forms.by', ['form_name' => (auth()->user()->company_id == 1) ? 'enrolments' : 'enrolments']) }}" class="side-nav-link">
                <span class="menu-icon"><i class="ti ti-message-question"></i></span>
                <span class="menu-text"> Form Submissions </span>
            </a>
        </li>    --}}
        <li class="side-nav-item">
            <a target="_blank" href="{{ url('') . '/command/optimize-clear?back=true' }}" class="side-nav-link text-danger fw-bold">
                <span class="menu-icon"><i class="ti ti-refresh"></i></span>
                <span class="menu-text"> Clear Cache </span>
            </a>
        </li>
       
    </ul>
    <div class="clearfix"></div>
</div>
</div>