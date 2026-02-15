@extends('frontend.layouts.app')

@section('meta.title', $pageData->seo_title)
@section('meta.description', $pageData->seo_description)

@section('content')
@php
   $banner_images = $pageData->meta->where('meta_key', 'banner_images')->first()->meta_value ?? '';

   $about_description = $pageData->meta->where('meta_key', 'about_description')->first()->meta_value ?? '';
   $about_image = $pageData->meta->where('meta_key', 'about_image')->first()->meta_value ?? '';
   $about_title = $pageData->meta->where('meta_key', 'about_title')->first()->meta_value ?? '';

   //$milestones = json_decode($pageData->meta->where('meta_key', 'home_milestones')->first()->meta_value ?? '[]', true);

   $quicklinks = json_decode($pageData->meta->where('meta_key', 'home_quicklinks')->first()->meta_value ?? '[]', true);
@endphp

@include('frontend.partials.breadcrumb', ['title' => $pageData->title, 'image' => $banner_images])

<!--about us section start-->
<section class="aboutpg_section pt-0 pt-md-5 pb-md-5 position-relative">
   <div class="container position-relative">
      <div class="row">
        
        
         <div class="col-lg-8 paddngrgt80" data-aos="fade-right" data-aos-duration="1000" data-aos-once="true">
            <div class="text-start mb-md-4 mb-2 pt-4">
               <h3 class="roboto text_color roboto fw-normal robot_slab ">{{$about_title}}</h3>
            </div>
           <div>
             {!! $about_description !!}
           </div>
         </div>
         <div class="col-lg-4 col-12 pt-5" data-aos="fade-left" data-aos-duration="1000" data-aos-once="true">
            <div class="about_border border_6 position-relative">
               <img class="hvr-bounce-in aboutimgss" src="{{ uploaded_asset($about_image) }}" alt="img" />
            </div>
         </div>
      </div>
   </div>
</section>

{{-- @include('frontend.partials.course-carousel') --}}

 {{-- @if(isset($milestones['itration']) && is_array($milestones['itration']))
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
@endif --}}

<!-- Features Section -->
@if(isset($quicklinks['itration']) && is_array($quicklinks['itration']))
<section class="features_section pt-5 pb-5 mb-md-5" id="features_section">
   <div class="container">
      <div class="row g-4">
      <div class="text-start mb-md-2 mb-0 pt-2">
            <h3 class="robot_slab text_color text-center">Why MarinArch</h3>
         </div>
         
         @foreach($quicklinks['itration'] as $index => $itration) 
         <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
            <div class="feature_card">
               <div class="feature_icon">
               <img class="jbox-img rotate w-100" src="{{ uploaded_asset($quicklinks['icon'][$index]) }}" alt="Image {{ $index }}">
               </div>
               <h3 class="feature_title robot_slab">{{$quicklinks['title'][$index]}}</h3>
               <p class="feature_description">
                  {{$quicklinks['description'][$index]}}
               </p>
            </div>
         </div>
      @endforeach

      </div>
   </div>
</section>
@endif

{{-- @if(isset($quicklinks['itration']) && is_array($quicklinks['itration']))
<section class="gallery_section">
   <div class="bgcolor pb-4 pt-4 pb-md-5 pt-md-5">
      <div class="container">
       
         <div class="row">

            @foreach($quicklinks['itration'] as $index => $itration)  
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
               <a class="text-decoration-none text-dark" href="{{$quicklinks['description'][$index]}}">
                  <div class="classroom_box border_2 position-relative">
                     <img class="hvr-bounce-in w-100" src="{{ uploaded_asset($quicklinks['icon'][$index]) }}" alt="Image {{ $index }}">
                     <div class=" text-center pt-3">
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
@endif --}}

@endsection
