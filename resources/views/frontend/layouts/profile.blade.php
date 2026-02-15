@extends('frontend.layouts.app')

@section('content')

@include('frontend.partials.breadcrumb', ['title' => $pageTitle ?? 'My Account'])

<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3 mb-4">
                <div class="account-sidebar">
                    <!-- Sidebar Header -->
                    <div class="sidebar-header">
                        <div class="sidebar-header-icon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h5 class="sidebar-title robot_slab">My Account</h5>
                    </div>
                    
                    <!-- Navigation Menu -->
                    <nav class="sidebar-nav">
                        <a class="sidebar-nav-item {{ request()->routeIs('auth.dashboard') ? 'active' : '' }}" href="{{ route('auth.dashboard') }}">
                            <div class="nav-item-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span class="nav-item-text">Dashboard</span>
                            @if(request()->routeIs('auth.dashboard'))
                            <div class="nav-item-indicator"></div>
                            @endif
                        </a>
                        
                        <a class="sidebar-nav-item {{ request()->routeIs('auth.profile') ? 'active' : '' }}" href="{{ route('auth.profile') }}">
                            <div class="nav-item-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="nav-item-text">Edit Profile</span>
                            @if(request()->routeIs('auth.profile'))
                            <div class="nav-item-indicator"></div>
                            @endif
                        </a>
                        
                        <a class="sidebar-nav-item {{ request()->routeIs('auth.change-password') ? 'active' : '' }}" href="{{ route('auth.change-password') }}">
                            <div class="nav-item-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <span class="nav-item-text">Change Password</span>
                            @if(request()->routeIs('auth.change-password'))
                            <div class="nav-item-indicator"></div>
                            @endif
                        </a>
                        
                        <a class="sidebar-nav-item {{ request()->routeIs('auth.enrolled-courses') ? 'active' : '' }}" href="{{ route('auth.enrolled-courses') }}">
                            <div class="nav-item-icon">
                                <i class="fas fa-graduation-cap"></i>
                            </div>
                            <span class="nav-item-text">Enrolled Courses</span>
                            @if(request()->routeIs('auth.enrolled-courses'))
                            <div class="nav-item-indicator"></div>
                            @endif
                        </a>
                        
                        <!-- Divider -->
                        <div class="sidebar-divider"></div>
                        
                        <!-- Logout Button -->
                        <form action="{{ route('auth.logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="sidebar-nav-item logout-item">
                                <div class="nav-item-icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <span class="nav-item-text">Logout</span>
                            </button>
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                @yield('profile-content')
            </div>
        </div>
    </div>
</section>

@endsection

<style>
/* Account Sidebar */
.account-sidebar {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    position: sticky;
    top: 20px;
}

/* Sidebar Header */
.sidebar-header {
    background: linear-gradient(135deg, #2098d1 0%, #0a7ba8 100%);
    padding: 25px 20px;
    text-align: center;
    color: #fff;
}

.sidebar-header-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto 15px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

.sidebar-header-icon i {
    font-size: 30px;
    color: #fff;
}

.sidebar-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    color: #fff;
    letter-spacing: 0.5px;
}

/* Sidebar Navigation */
.sidebar-nav {
    padding: 15px 0;
}

.sidebar-nav-item {
    display: flex;
    align-items: center;
    padding: 14px 20px;
    color: #555;
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    border-left: 3px solid transparent;
    margin: 2px 0;
}

.sidebar-nav-item:hover {
    background: #f8f9fa;
    color: #2098d1;
    border-left-color: #2098d1;
    padding-left: 25px;
}

.sidebar-nav-item.active {
    background: linear-gradient(90deg, #e3f2fd 0%, rgba(227, 242, 253, 0.5) 100%);
    color: #2098d1;
    font-weight: 600;
    border-left-color: #2098d1;
}

.sidebar-nav-item.active .nav-item-icon {
    background: #2098d1;
    color: #fff;
    transform: scale(1.1);
}

.sidebar-nav-item.active .nav-item-indicator {
    position: absolute;
    right: 15px;
    width: 6px;
    height: 6px;
    background: #2098d1;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.5;
        transform: scale(1.2);
    }
}

/* Nav Item Icon */
.nav-item-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f0f0;
    border-radius: 10px;
    margin-right: 12px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.nav-item-icon i {
    font-size: 16px;
    color: #666;
    transition: all 0.3s ease;
}

.sidebar-nav-item:hover .nav-item-icon {
    background: #e3f2fd;
    transform: translateX(3px);
}

.sidebar-nav-item:hover .nav-item-icon i {
    color: #2098d1;
}

/* Nav Item Text */
.nav-item-text {
    flex: 1;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.3s ease;
}

/* Logout Item */
.logout-form {
    margin: 0;
    padding: 0;
}

.logout-item {
    color: #dc3545 !important;
    border: 0px;
    background: transparent;
    margin-top: 5px;
    border-radius: 0 0 0 0;
}

.logout-item:hover {
    background: linear-gradient(90deg, #ffe5e5 0%, rgba(255, 229, 229, 0.8) 100%) !important;
    color: #dc3545 !important;
    border-left-color: #dc3545 !important;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.15);
}

.logout-item .nav-item-icon {
    background: linear-gradient(135deg, #ffcccc 0%, #ff9999 100%);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
}

.logout-item .nav-item-icon i {
    color: #dc3545;
    font-weight: 600;
}

.logout-item:hover .nav-item-icon {
    background: linear-gradient(135deg, #ff9999 0%, #ff6666 100%);
    transform: scale(1.1) rotate(-5deg);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.logout-item .nav-item-text {
    font-weight: 600;
    color: #dc3545;
}

/* Sidebar Divider */
.sidebar-divider {
    height: 1px;
    background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
    margin: 10px 20px;
}
.sidebar-nav-item.active i {
    color: #fff;
}   

/* Responsive */
@media (max-width: 768px) {
    .account-sidebar {
        position: static;
        margin-bottom: 20px;
    }

    .sidebar-header {
        padding: 20px 15px;
    }

    .sidebar-header-icon {
        width: 50px;
        height: 50px;
    }

    .sidebar-header-icon i {
        font-size: 24px;
    }

    .sidebar-title {
        font-size: 18px;
    }

    .sidebar-nav-item {
        padding: 12px 15px;
    }

    .nav-item-icon {
        width: 35px;
        height: 35px;
    }
}
</style>

