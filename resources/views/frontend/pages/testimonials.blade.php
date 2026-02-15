@extends('frontend.layouts.app')

@section('meta.title', $pageData->seo_title)
@section('meta.description', $pageData->seo_description)

@section('content')

@include('frontend.partials.breadcrumb', ['title' => $pageData->title, 'image' => $pageData->meta->where('meta_key', 'banner_images')->first()->meta_value ?? ''])
@php
    //$testimonial_images = array_filter(explode(',', $pageData->testimonial_images ?? ''));
    $testimonial_images = array_filter(explode(',', $pageData->meta->where('meta_key', 'testimonial_images')->first()->meta_value ?? ''));

@endphp
<section class="courses_we_offered pt-4 pt-md-5 pb-4 position-relative" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
   <div class="container">
      <div class="row align-items-center mb-md-4 mb-3">
         <div class="col-lg-8 col-md-7 col-12">
            <div class="text-start pt-2">
               <h3 class="text_color robot_slab mb-0">{{$pageData->title}}</h3>
            </div>
         </div>
         <div class="col-lg-4 col-md-5 col-12 text-md-end text-start mt-md-0 mt-3">
            <a href="https://www.google.com/search?sca_esv=eb783835d62ec6f7&rlz=1C1SLLM_enIN1120IN1120&si=AL3DRZEsmMGCryMMFSHJ3StBhOdZ2-6yYkXd_doETEE1OR-qOSOXx31BNcQ1ajSXd2D9ZuQC7kMPdaCdKMY0Xm0lPIgc-R5Jf8M93s6HO0-qu1rqhvw0o8RUmQBjbCy-SEAqrlroqq_Q&q=Marin+Arch+Reviews&sa=X&ved=2ahUKEwimyJPhso2SAxWoUGwGHWLJC_8Q0bkNegQIIhAH&biw=1600&bih=731&dpr=1&aic=0" target="_blank" class="google-reviews-btn">
               <i class="fab fa-google me-2"></i>
               Google Reviews
            </a>
         </div>
      </div>
      <div class="row">
         @foreach($testimonial_images as $img)
         <div class="col-md-3">
            <div class="position-relative">
               <div class="testiminials_box">
                  <a data-fancybox="gallery" data-caption="IMU CET Entrance" href="{{ uploaded_asset($img) }}">
                  <img src="{{ uploaded_asset($img) }}" class=" " alt="IMU CET Entrance">
                  </a>
               </div>
            </div>
         </div>
         @endforeach
      </div>
   </div>
</section>
@endsection

