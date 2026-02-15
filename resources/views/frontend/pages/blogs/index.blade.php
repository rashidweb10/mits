@extends('frontend.layouts.app')

@section('meta.title', "Blogs")
@section('meta.description', "Blogs")

@section('content')
@include('frontend.partials.breadcrumb', ['title' => "Blogs"])

<section class="pt-4 pt-md-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb-4">
                    <form method="GET" action="{{ route('blog.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                            <div class="mb-3">
                            <input type="text" name="search" value="{{ request()->get('search') }}" class="form-control" placeholder="Search blog">
                        </div>
                            </div>

                            <div class="col-md-4">
                                
                            <div class="mb-3">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->slug }}" @if(request()->get('category') == $cat->slug) selected @endif>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                            </div>

                            <div class="col-md-4">
                                <div class="dsplay_flex1">
                                <button style="background:#00a0e3 !important;border:0px;" type="submit" class="btn btn-2 btn-success robot_slab w-100">Filter</button>
                            <a  href="{{ route('blog.index') }}" class="btn btn-secondary robot_slab w-100">Reset</a>
                             
                                </div>
                            </div>
                        </div>
                       

                        

                       
                    </form>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="row">
                    @forelse($blogs as $blog)
                        <div class="col-md-4 mb-4">
                            @php
                                $cats = $blog->categories ? $blog->categories->pluck('name')->filter()->values() : collect();
                                $primaryCategory = $cats->first();
                                $extraCategoryCount = max(0, $cats->count() - 1);
                                $imageSrc = uploaded_asset($blog->image);
                                $dateText = $blog->published_at
                                    ? \Illuminate\Support\Carbon::parse($blog->published_at)->format('M d, Y')
                                    : ($blog->created_at ? $blog->created_at->format('M d, Y') : null);
                            @endphp

                            <div class="blog-card h-100">
                                <div class="blog-card-media position-relative">
                                    <img class="blog-card-img" src="{{ $imageSrc }}" alt="{{ $blog->title }}">

                                    @if($primaryCategory)
                                        <div class="blog-card-badges">
                                            <span class="blog-badge">{{ $primaryCategory }}</span>
                                            @if($extraCategoryCount)
                                                <span class="blog-badge blog-badge-muted">+{{ $extraCategoryCount }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <div class="blog-card-body">
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="" aria-label="Read {{ $blog->title }}">
                                        @if($dateText)
                                            <div class="blog-card-meta">{{ $dateText }}</div>
                                        @endif

                                        <h5 class="blog-card-title robot_slab">{{ $blog->title }}</h5>

                                        @if($blog->excerpt)
                                            <div class="blog-card-excerpt">
                                                {{ \Illuminate\Support\Str::limit(strip_tags($blog->excerpt), 140) }}
                                            </div>
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning">No blogs found.</div>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4">
                    {{ $blogs->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
