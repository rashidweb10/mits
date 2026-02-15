@extends('frontend.layouts.app')

@section('meta.title', $pageData->seo_title)
@section('meta.description', $pageData->seo_description)

@section('content')

@include('frontend.partials.breadcrumb', ['title' => $pageData->title])

<section class="pb-md-5 pb-4">
    <div class="container">
        <div class="row">
            <div class="12">
                {!! $pageData->content !!}
            </div>
        </div>
    </div>
</section>

@endsection