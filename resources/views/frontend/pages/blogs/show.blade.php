@extends('frontend.layouts.app')

@section('meta.title', $blog->seo_title ?: $blog->title)
@section('meta.description', $blog->seo_description ?: ($blog->excerpt ?: $blog->title))

@section('content')
@include('frontend.partials.breadcrumb', ['title' => $blog->title])

<section class="pt-4 pt-md-5 pb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="mb-3">
                    @if($blog->image)
                        <img class="w-100" src="{{ uploaded_asset($blog->image) }}" alt="{{ $blog->title }}">
                    @endif
                </div>

                <h3 class="robot_slab text_color">{{ $blog->title }}</h3>

                @php
                    $cats = $blog->categories ? $blog->categories->pluck('name')->filter()->values() : collect();
                @endphp
                @if($cats->count())
                    <p class="text-muted mb-3">{{ $cats->implode(', ') }}</p>
                @endif

                @if($blog->published_at)
                    <p class="text-muted">
                        {{ formatDatetime($blog->published_at) }}
                    </p>
                @endif

                @if($blog->excerpt)
                    <div class="mb-3">
                        {!! $blog->excerpt !!}
                    </div>
                @endif

                <div>
                    {!! $blog->content !!}
                </div>
            </div>

            <div class="col-lg-4">
                <div class="mb-4">
                    <h5 class="robot_slab text_color">Categories</h5>
                    <ul class="list-unstyled">
                        @foreach($categories as $cat)
                            <li class="mb-1">
                                <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="text-decoration-none">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                @if($relatedBlogs->count())
                    <div>
                        <h5 class="robot_slab text_color">Related Blogs</h5>
                        @foreach($relatedBlogs as $rb)
                            <div class="mb-3">
                                <a href="{{ route('blog.show', $rb->slug) }}" class="text-decoration-none text-dark">
                                    <div class="d-flex gap-2">
                                        <div style="width: 72px; flex: 0 0 72px;">
                                            @if($rb->image)
                                                <img class="w-100" src="{{ uploaded_asset($rb->image) }}" alt="{{ $rb->title }}">
                                            @else
                                                <img class="w-100" src="{{ asset('assets/frontend/img/b1.jpeg') }}" alt="{{ $rb->title }}">
                                            @endif
                                        </div>
                                        <div>
                                            <div class="robot_slab">{{ $rb->title }}</div>
                                            @if($rb->published_at)
                                                <small class="text-muted">{{ formatDatetime($rb->published_at) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
