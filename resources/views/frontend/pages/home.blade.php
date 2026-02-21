@extends('frontend.layouts.app')
    
@section('meta.title', $pageData->seo_title)
@section('meta.description', $pageData->seo_description)

@section('content')

@php

  $banner_title = $pageData->meta->where('meta_key', 'banner_title')->first()->meta_value ?? '';
  $banner_description = $pageData->meta->where('meta_key', 'banner_description')->first()->meta_value ?? '';
  $banner_images = $pageData->meta->where('meta_key', 'banner_images')->first()->meta_value ?? '';

   $popup_title = $pageData->meta->where('meta_key', 'popup_title')->first()->meta_value ?? '';
   $popup_description = $pageData->meta->where('meta_key', 'popup_description')->first()->meta_value ?? '';
   $popup_image = $pageData->meta->where('meta_key', 'popup_image')->first()->meta_value ?? '';

  $about_title = $pageData->meta->where('meta_key', 'about_title')->first()->meta_value ?? '';
  $about_description = $pageData->meta->where('meta_key', 'about_description')->first()->meta_value ?? '';
  $about_image = $pageData->meta->where('meta_key', 'about_image')->first()->meta_value ?? '';

  $about_title2 = $pageData->meta->where('meta_key', 'about_school_title')->first()->meta_value ?? '';
  $about_description2 = $pageData->meta->where('meta_key', 'about_school_description')->first()->meta_value ?? '';

  $categories = \App\Models\CourseCategory::where('is_active', 1)->whereIn('id', [33, 34])->get();
  $onlineCategory = \App\Models\CourseCategory::where('id', 33)->where('is_active', 1)->first();
  $offlineCategory = \App\Models\CourseCategory::where('id', 34)->where('is_active', 1)->first();

  $testimonial_images = array_slice(
    array_filter(
        explode(',', $pageData1->meta->where('meta_key', 'testimonial_images')->first()->meta_value ?? '')
    ),
    -10
);
  
  $milestones = json_decode($pageData->meta->where('meta_key', 'home_milestones')->first()->meta_value ?? '[]', true);

  $achievement_title = $pageData->meta->where('meta_key', 'achievement_title')->first()->meta_value ?? '';
  $achievement_description = $pageData->meta->where('meta_key', 'achievement_description')->first()->meta_value ?? '';
  $achievement_image = $pageData->meta->where('meta_key', 'achievement_image')->first()->meta_value ?? ''; 

  $video = $pageData->meta->where('meta_key', 'video')->first()->meta_value ?? '';

  $quicklinks = json_decode($pageData->meta->where('meta_key', 'home_quicklinks')->first()->meta_value ?? '[]', true);
@endphp

<div class="banner_height position-relative">
    <video width="100%" height="100%" class="elVideo" loop="loop" autoplay="" playsinline="" muted=""
   src="{{ uploaded_asset($banner_images) }}" id="video-slider-1"></video>
    <div class="position-absolute hero_content translate-middle text-center text-white" style="z-index: 10;">


        {!! $banner_description !!}
        <div class="d-flex align-items-center justify-content-center gap-3 mt-3 hero-banner-container">
            <a href="https://www.google.com/search?sca_esv=eb783835d62ec6f7&rlz=1C1SLLM_enIN1120IN1120&si=AL3DRZEsmMGCryMMFSHJ3StBhOdZ2-6yYkXd_doETEE1OR-qOSOXx31BNcQ1ajSXd2D9ZuQC7kMPdaCdKMY0Xm0lPIgc-R5Jf8M93s6HO0-qu1rqhvw0o8RUmQBjbCy-SEAqrlroqq_Q&q=Marin+Arch+Reviews&sa=X&ved=2ahUKEwimyJPhso2SAxWoUGwGHWLJC_8Q0bkNegQIIhAH&biw=1600&bih=731&dpr=1&aic=0" target="_blank"><img src="/assets/frontend/img/review_img.jpeg" class="img-fluid review-banner-img" alt="Reviews" title="Check our students review" /></a>
            <!-- Square Box with Image and Popup -->
            <div class="hero-square-box" data-bs-toggle="modal" data-bs-target="#heroPopupModal">
                <img src="{{ uploaded_asset($popup_image) }}" class="hero-square-img" alt="Popup Image" />
                <div class="hero-square-overlay">
                    <h5 class="hero-square-heading robot_slab mb-0">Click to View</h5>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hero Popup Modal -->
<div class="modal fade" id="heroPopupModal" tabindex="-1" aria-labelledby="heroPopupModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title robot_slab" id="heroPopupModalLabel">{{$popup_title}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{!! $popup_description !!}</p>
            </div>
        </div>
    </div>
</div>

<!--about us section start-->
<section class="about_section pt-0 pt-md-5 pb-md-5 position-relative">
   <div class="container">
      <div class="row">
         <div class="col-lg-8 ">
            <div class="text-start">
               <div class="skew-box ">
                  <p class="robot_slab text_color">{{ $banner_title }}</p>
               </div>
            </div>
         </div>
         <div class="col-lg-8 order-md-1 order-2 paddngrgt80" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
            <div class="text-start mb-md-4 mb-2 pt-md-4 pt-0">
               <h3 class=" text_color robot_slab fw-normal">{{ $about_title }}</h3>
            </div>
            <div>
               {!! $about_description !!}
            </div>
         </div>
         <div class="col-lg-4 col-12 pt-md-5 pb-md-0 pb-4 pt-2 order-md-2 order-1" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
            <div class="about_border border_6 position-relative">
               <img class="hvr-bounce-in aboutimgss" src="{{ uploaded_asset($about_image) }}" alt="img" />
            </div>
         </div>
      </div>
   </div>
</section>

<!-- Enroll Section with Images -->
<section class="enroll-images-section pt-md-5 pb-md-5  pt-4 pb-4">
   <div class="container">
      <div class="row g-4 align-items-center">
         <!-- Offline Course - First -->
        <!-- Online Course - Last -->
        <!-- @if($onlineCategory)
         <div class="col-md-4" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
            <a href="{{ route('courses') }}#online-course" class="text-decoration-none">
               <div class="enroll-image-box position-relative">
                  <img class="w-100 hvr-bounce-in" src="{{ uploaded_asset($onlineCategory->image) }}" alt="{{ $onlineCategory->name }}">
                  <div class="image-label">
                     <span class="robot_slab">{{ $onlineCategory->name }}</span>
                  </div>
               </div>
            </a>
         </div>
         @endif -->

         
         <div class="col-md-8" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
            <a href="" class="text-decoration-none">
               <div class="enroll-image-box position-relative">
                  <img class="w-100 hvr-bounce-in" src="/assets/frontend/img/b1.jpeg" alt="">
                 
               </div>
            </a>
         </div>
       


         <!-- Enroll Form Column - Middle -->
         <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
            <div class="enroll-form-box">
               <h3 class="robot_slab text_color mb-4">Enroll Now</h3>
               @include('frontend.components.enrolment-enquiry')
            </div>
         </div>

         
         

         
         
      </div>
   </div>
</section>

{{-- <!-- @include('frontend.partials.course-carousel') --> --}}

      
<section class="client_section1 py-lg-5" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
          <div class="">
            
                <div class="text-center ">
                  <h3 class="robot_slab text_color  mb-md-4 mb-2 pt-2 pb-md-3">Students Review</h3>
                </div>
              
          
          <div class="services-scroll-container">
            <div class="services-scroll-wrapper">
              <ul class="services-scroll-list">

                  @foreach($testimonial_images as $img)
                  <li class="services-scroll-item">
                     <a href="{{ uploaded_asset($img) }}"
                     data-fancybox="album1"
                     data-caption=""
                     class="d-block position-relative services-box-link">
                        <div class="services_boxs">
                           <img class="jbox-img rotate w-100" src="{{ uploaded_asset($img) }}" alt="">
                        </div>
                     </a>
                  </li>
                  @endforeach
               
              </ul>
            </div>
            
            

          </div>

          <div class="read-more text-center mt-md-5 mt-4">
               <a href="/testimonials" class="btn-2 robot_slab">View All</a>
            </div>
          
          </div>
        </section>



<section class="scholar_section mt-lg-5 pb-lg-5 position-relative z-index-9">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-10 text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
            <div class="text-start mb-md-4 mb-2 pt-md-0">
               <h3 class="robot_slab  text_color text-center fw-normal">{{ $about_title2 }}</h3>
            </div>
            <p class="text-center padd190">
            {!! $about_description2 !!}
            </p>
            <div class="read-more text-center mb-md-5">
               <a href="{{ route('about') }}" class="btn-2 robot_slab">Read More</a>
            </div>
         </div>
      </div>
   </div>
</section>

@if(isset($milestones['itration']) && is_array($milestones['itration']))
<section id="counter" class="statistics-section about-us" >
   <div class="container">
      <div class="row">
         @foreach($milestones['itration'] as $index => $itration)
         <div class="col-md-3 col-6 text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
            <div class="stastic  @if($loop->last) @else startimg @endif ">
               <div class="counter-value robot_slab" data-count="{{$milestones['title'][$index]}}">1+</div>
               <p class="robot_slab">{{$milestones['description'][$index]}}</p>
            </div>
         </div>
         @endforeach
      </div>
   </div>
</section>
@endif

<section class="pt-4 pt-md-5 pb-md-5">
   <div class="container paddlft50 pt-md-5">
      <div class="row align-items-center justify-content-center">
         <div class="col-lg-4 d-lg-block d-none">
            <div class="about_border border_10" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
               <img class="hvr-bounce-in w-100" src="{{ uploaded_asset($achievement_image) }}" alt="img" />
            </div>
         </div>
         <div class="col-lg-8 ps-md-4" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
            <div class="education_box">
               <div class="text-start mb-md-3 mb-2 pt-2 ">
                  <h3 class="roboto text_color robot_slab">{{ $achievement_title }}</h3>
               </div>

               <img class="hvr-bounce-in w-100 d-lg-none d-block pt-2 pb-1 ps-2 pe-2" src="{{ uploaded_asset($achievement_image) }}" alt="img" />
               <p>
               <p>
                  {!! $achievement_description !!}
               </p>
            </div>
         </div>
      </div>
   </div>
</section>

@if(!empty($video))
<section class="awards_achievements pt-4 pt-md-5 pb-0 position-relative" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-md-12">
            <iframe width="100%" height="345" src="{{ $video }}" title="Royal Caribbean Odyssey of the Seas | Full Walkthrough Ship Tour" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            {{-- <video controls style="width:100%;">
    <source src="/assets/frontend/img/marine_video.mp4" type="video/mp4">
  </video> --}}
            </div>
         <div class="col-md-5">
            <div class="owl-carousel achievements">
               <div class="item">
                  <div class="testiminials ">
                     <img class="jbox-img rotate w-100 hvr-bounce-in" src="assets/frontend/img/aboutus.png" alt="">
                  </div>
               </div>
               <div class="item">
                  <div class="testiminials ">
                     <img class="jbox-img rotate w-100 hvr-bounce-in" src="assets/frontend/img/aboutus.png" alt="">
                  </div>
               </div>
               <div class="item">
                  <div class="testiminials ">
                     <img class="jbox-img rotate w-100 hvr-bounce-in" src="assets/frontend/img/aboutus.png" alt="">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
@endif

@if(isset($quicklinks['itration']) && is_array($quicklinks['itration']))
<section class="gallery_section">
   <div class="bgcolor pb-4 pt-4 pb-md-5 pt-md-5">
      <div class="container">
         <div class="text-start mb-md-4 mb-2 pt-2">
            <h3 class="robot_slab text_color text-center">Why MITS </h3>
         </div>
         <div class="row">

            @foreach($quicklinks['itration'] as $index => $itration)  
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
               <a class="text-decoration-none text-dark" href="{{$quicklinks['url'][$index]}}">
                  <div class="classroom_box border_2 position-relative">
                     <img class="hvr-bounce-in w-100" src="{{ uploaded_asset($quicklinks['icon'][$index]) }}" alt="Image {{ $index }}">
                     <div class=" text-center pt-md-3">
                        <p class="robot_slab centered-text">{{$quicklinks['title'][$index]}}</p>
                     </div>
                  </div>
               </a>
            </div>
            @endforeach

         </div>
      </div>
   </div>
</section>
@endif

@push('scripts')
<script>
   $(document).ready(function() {
      // Handle home enroll form submission
      $('#homeEnrollForm').on('submit', function(e) {
         e.preventDefault();
         
         var courseType = $('input[name="courseType"]:checked').val();
         var course = $('#homeCourseSelect').val();
         
         if (!course) {
            alert('Please select a course');
            return;
         }
         
         // Here you can add AJAX call to submit enrollment
         // For now, just show an alert
         alert('Enrollment request submitted for: ' + course + ' (' + courseType + ')');
         
         // Reset form
         $('#homeEnrollForm')[0].reset();
         $('#homeOnlineType').prop('checked', true);
      });
   });
</script>
@endpush

@endsection
