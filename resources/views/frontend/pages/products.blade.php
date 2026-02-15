@extends('frontend.layouts.app')

@section('meta.title', $pageData->seo_title)
@section('meta.description', $pageData->seo_description)

@section('content')
@php
  $banner_images = $pageData->meta->where('meta_key', 'banner_images')->first()->meta_value ?? '';
  $about = json_decode($pageData->meta->where('meta_key', 'about')->first()->meta_value ?? '[]', true);
@endphp
@include('frontend.partials.breadcrumb', ['title' => $pageData->title])


<div class="banner_inner_slider">
  <img class="w-100" src="{{ uploaded_asset($banner_images) }}" alt="img" />
</div>

<div class="wave_img">
  <img class="w-100" src="{{ asset('assets/frontend/img/wave.png') }}" alt="img" />
</div>

@if(!empty($about['itration']) && is_array($about['itration']))
@foreach($about['itration'] as $index => $itration)
    @php
        $image = $about['image'][$index] ?? '';
        $desc  = $about['decription'][$index] ?? '';
        $title  = $about['title'][$index] ?? '';
    @endphp

    {{-- Odd index (0,2,4...) → First Section (text left, image right) --}}
    @if($loop->index % 2 == 0)
        <section class="about-us pt-4 pt-md-5 pb-5 pb60" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-8 ps-md-4">
                        <div class="text-start mb-md-4 mb-2">
                            <h3 class="robot_slab text_color font-wight-400">
                                {{ $title }}
                            </h3>
                        </div>
                        {!! $desc !!}
                    </div>

                    <div class="col-lg-4 ps-md-5" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
                        <div class="about_border_new about_imgs text-center pt-md-0 pt-3">
                            <img class="hvr-bounce-in" src="{{ uploaded_asset($image) }}" alt="{{ $title }}">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
    {{-- Even index (1,3,5...) → Second Section (image left, text right) --}}
        <section class="padding80 bg_dark_blue">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 pe-md-5 order-md-1 order-2" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
                        <div class="about_border_new about_imgs text-center pt-md-0 pt-3">
                            <img class="hvr-bounce-in" src="{{ uploaded_asset($image) }}" alt="{{ $title }}">
                        </div>
                    </div>

                    <div class="col-lg-8 ps-md-4 order-md-2 order-1" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
                        <div class="text-start mb-md-4 mb-2">
                            <h3 class="robot_slab text_color font-wight-400">
                                {{ $title }}
                            </h3>
                        </div>
                        {!! $desc !!}
                    </div>
                </div>
            </div>
        </section>
    @endif
@endforeach
@endif

@endsection
