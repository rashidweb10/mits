<!-- Header Section Start -->
<div class="top_position">

    <!-- Top Header -->
    <div class="header_section_top">
        <div class="container position-relative">
            <div class="row align-items-center">

                <div class="col-md-2"></div>

                <div class="col-md-4">
                    <p class="mrg_35 robot_slab">
                        All Admission on Counselling Call: {{get_setting('phone')}}
                    </p>
                </div>

                <div class="col-md-6 d-md-block d-none">
                    <div class="d-flex browser_link">
                        <ul class="d-flex ms-auto mb-0">
                            <li class="nav-item">
                                @if( get_setting('brochure') )
                                <a target="_blank" class="nav-link robot_slab" href="{{ uploaded_asset(get_setting('brochure')) }}">
                                    <i class="fas fa-file-download me-1"></i> Brochure
                                </a>
                                @endif
                            </li>
                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link robot_slab dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('auth.dashboard') }}">
                                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('auth.profile') }}">
                                                <i class="fas fa-user me-2"></i> Edit Profile
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('auth.change-password') }}">
                                                <i class="fas fa-lock me-2"></i> Change Password
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('auth.enrolled-courses') }}">
                                                <i class="fas fa-graduation-cap me-2"></i> Enrolled Courses
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger" style="cursor: pointer; border: none; background: none; width: 100%; text-align: left;">
                                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link robot_slab" href="{{ route('auth.login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> Login
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link robot_slab" href="{{ route('auth.register') }}">
                                        <i class="fas fa-user-plus me-1"></i> Register
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Header -->
    <header>
        <div class="container">
            <div class="row align-items-center">

                <!-- Logo -->
                <div class="col-md-1 col-6 order-md-1 order-1">
                    <div class="logo_width">
                        <a class="navbar-brand" href="/">
                            <img
                                class="w-150"
                                src="{{ uploaded_asset(get_setting('logo')) }}"
                                title="MarinArch Logo"
                                alt="MarinArch Logo"
                            />
                        </a>
                    </div>
                </div>

                <!-- Mobile Login/Register -->
                <div class="col-md-4 col-6 order-md-2 order-2 d-lg-none d-flex align-items-center justify-content-end gap-2">
                    @auth
                        <div class="dropdown">
                            <a class="nav-link robot_slab dropdown-toggle" href="#" id="mobileUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="mobileUserDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('auth.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('auth.profile') }}">
                                        <i class="fas fa-user me-2"></i> Edit Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('auth.change-password') }}">
                                        <i class="fas fa-lock me-2"></i> Change Password
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('auth.enrolled-courses') }}">
                                        <i class="fas fa-graduation-cap me-2"></i> Enrolled Courses
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger" style="cursor: pointer; border: none; background: none; width: 100%; text-align: left;">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a class="nav-link robot_slab mobile-login-btn" href="{{ route('auth.login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a class="nav-link robot_slab mobile-register-btn" href="{{ route('auth.register') }}">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    @endauth
                    <!-- Mobile Toggle -->
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#mobileMenu"
                        aria-controls="mobileMenu"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Navigation -->
                <div class="col-md-11 col-12 order-md-3 d-lg-block">
                    <nav class="navbar navbar-expand-lg navbar-light p-0">

                        <div
                            class="collapse navbar-collapse justify-content-end"
                            id="navbarSupportedContent"
                        >
                            <div class="d-md-flex">
                                <ul class="navbar-nav ms-md-auto mb-0 position_tops">

                                    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                                        <a class="nav-link robot_slab" href="{{ route('home') }}">
                                            Home
                                        </a>
                                    </li>

                                    <!-- Marinarch Menu -->
                                    <li class="nav-item menu {{ request()->is('about-us') ? 'active' : '' }}">
                                        <a href="/about-us" class="nav-link robot_slab">
                                        Marinarch
                                        </a>
                                    </li>

                                    <li class="nav-item menu {{ request()->routeIs('courses') ? 'active' : '' }}">
                                        <a href="#" class="nav-link robot_slab">
                                        Courses
                                        </a>
                                        <ul class="submenu">
                                           <li>
                                                  <a href="{{ route('courses') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                  <i class="fa-solid fa-laptop"></i> {{ \App\Models\CourseCategory::where('id', 33)->where('is_active', 1)->value('name') ?? 'Online Courses' }}

                                                  </a>
                                                </li>
                                                
                                                <li>
                                                  <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses') ? 'active' : '' }}">
                                                  <i class="fa-solid fa-chalkboard"></i> {{ \App\Models\CourseCategory::where('id', 34)->where('is_active', 1)->value('name') ?? 'Offline Courses' }}

                                                  </a>
                                                </li>
                                                
                                              
                                        </ul>
                                    </li>

                                   

                                    <!-- Faculties Menu -->
                                    <li class="nav-item menu {{ request()->routeIs('faculties') ? 'active' : '' }}">
                                        <a href="#" class="nav-link robot_slab">
                                            Faculties
                                        </a>
                                        <ul class="submenu">
                                           <li>
                                                  <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                    <i class="fa-solid fa-user-graduate"></i> Ms. Archana Saxena
                                                  </a>
                                                </li>
                                                
                                                <li>
                                                  <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                    <i class="fa-solid fa-user-tie"></i> Mr. Vivek Sangal
                                                  </a>
                                                </li>
                                                
                                                <li>
                                                  <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                    <i class="fa-solid fa-user-doctor"></i> Dr. Brijendra Kumar
                                                  </a>
                                                </li>
                                                
                                                <li>
                                                  <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                    <i class="fa-solid fa-chalkboard-user"></i> Mr. Pravendra Singh
                                                  </a>
                                                </li>
                                                
                                                <li>
                                                  <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                    <i class="fa-solid fa-user"></i> Mr. I. K. Basu
                                                  </a>
                                                </li>
                                                
                                                <li>
                                                  <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                    <i class="fa-solid fa-anchor"></i> Capt. Vishwanath Shenoy
                                                  </a>
                                                </li>
                                                
                                                <li>
                                                  <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                                    <i class="fa-solid fa-briefcase"></i> Mr. Arun O. Mahajan
                                                  </a>
                                                </li>
                                        </ul>
                                    </li>

                                    <li class="nav-item {{ request()->routeIs('testimonials') ? 'active' : '' }}">
                                        <a class="nav-link robot_slab" href="{{ route('testimonials') }}">
                                        Students Review
                                        </a>
                                    </li>

                                    <li class="nav-item {{ request()->routeIs('blog.index') ? 'active' : '' }}">
                                        <a class="nav-link robot_slab" href="{{ route('blog.index') }}">
                                            Blogs
                                        </a>
                                    </li>

                                    <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                                        <a class="nav-link robot_slab" href="{{ route('contact') }}">
                                            Contact Us
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </div>

                    </nav>
                </div>

            </div>
        </div>
    </header>

    <!-- Mobile Side Menu -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title robot_slab" id="mobileMenuLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <nav class="navbar-nav">
                <ul class="navbar-nav mb-0">
                    <li class="nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
                        <a class="nav-link robot_slab" href="{{ route('home') }}">
                            Home
                        </a>
                    </li>

                    <!-- Marinarch Menu -->
                    <li class="nav-item menu {{ request()->is('about-us') ? 'active' : '' }}">
                        <a href="/about-us" class="nav-link robot_slab">
                        Marinarch
                        </a>
                    </li>

                    <li class="nav-item menu {{ request()->routeIs('courses') ? 'active' : '' }}">
                        <a href="#" class="nav-link robot_slab d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#coursesSubmenu" aria-expanded="false">
                            <span>Courses</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                        <ul class="submenu mobile-submenu collapse" id="coursesSubmenu">
                            <li>
                                <a href="{{ route('courses') }}#online-course" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-laptop"></i> {{ \App\Models\CourseCategory::where('id', 33)->where('is_active', 1)->value('name') ?? 'Online Courses' }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('courses') }}#offline-course" class="{{ request()->routeIs('courses') ? 'active' : '' }}">
                                    <i class="fa-solid fa-chalkboard"></i> {{ \App\Models\CourseCategory::where('id', 34)->where('is_active', 1)->value('name') ?? 'Offline Courses' }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Faculties Menu -->
                    <li class="nav-item menu {{ request()->routeIs('faculties') ? 'active' : '' }}">
                        <a href="#" class="nav-link robot_slab d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#facultiesSubmenu" aria-expanded="false">
                            <span>Faculties</span>
                            <i class="fas fa-chevron-down ms-2"></i>
                        </a>
                        <ul class="submenu mobile-submenu collapse" id="facultiesSubmenu">
                            <li>
                                <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-graduate"></i> Ms. Archana Saxena
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-tie"></i> Mr. Vivek Sangal
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user-doctor"></i> Dr. Brijendra Kumar
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-chalkboard-user"></i> Mr. Pravendra Singh
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-user"></i> Mr. I. K. Basu
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-anchor"></i> Capt. Vishwanath Shenoy
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('faculties') }}" class="{{ request()->routeIs('faculties') ? 'active' : '' }}">
                                    <i class="fa-solid fa-briefcase"></i> Mr. Arun O. Mahajan
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item {{ request()->routeIs('testimonials') ? 'active' : '' }}">
                        <a class="nav-link robot_slab" href="{{ route('testimonials') }}">
                            Students Review
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('blog.index') ? 'active' : '' }}">
                        <a class="nav-link robot_slab" href="{{ route('blog.index') }}">
                            Blogs
                        </a>
                    </li>

                    <li class="nav-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <a class="nav-link robot_slab" href="{{ route('contact') }}">
                            Contact Us
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</div>
<!-- Header Section End -->