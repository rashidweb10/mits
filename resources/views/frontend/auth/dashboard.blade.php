@extends('frontend.layouts.profile')

@section('meta.title', 'Dashboard')
@section('meta.description', 'User Dashboard')

@php
    $pageTitle = 'Dashboard';
@endphp

@section('profile-content')
<div class="bg-light p-4 p-md-5 rounded-3 shadow-sm">
    <h3 class="fw-bold mb-4 robot_slab">Dashboard</h3>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Dashboard content will be added here -->
    
</div>
@endsection

