@extends('frontend.layouts.app')

@section('meta.title', 'Contact Us')
@section('meta.description', 'Contact Us')

@section('content')

@include('frontend.partials.breadcrumb', ['title' => "Courses"])

<section class="courses_section pb-md-5 pt-md-5 pb-4 pt-4">
   <div class="container">
      <div class="row g-4">
         <!-- Online Courses Section -->
         <div class="col-lg-6 pe-lg-5" id="online-course">
            <h2 class="robot_slab text_color mb-md-4 text-capitalize">
               <i class="fa-solid fa-laptop me-2"></i> {{ $onlineCategory->name ?? 'Online Course' }}
            </h2>
            <ul class="course-list list-unstyled">
               @if(isset($onlineCategory) && $onlineCategory->courses->count() > 0)
                  @foreach($onlineCategory->courses as $course)
                     <li class="course-item pb-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                           
                           <span class="course-name"><span class="course-bullet me-3"></span> {{ $course->name }}</span>
                           
                           <div class="couses_icons">

                           <a href="/" class="btn btn-sm btn-outline-primary ms-2" download>
                           <i class="fa-brands fa-youtube"></i>
                           </a>
                           
                           @if($course->brochure)
                           <a href="{{ uploaded_asset($course->brochure) }}" class="btn btn-sm btn-outline-primary ms-2" download>
                              <i class="fas fa-file-pdf"></i>
                           </a>
                           @endif
                           </div>
                           
                        </div>
                     </li>
                  @endforeach
               @else
                  <li class="course-item pb-3">
                     <div class="d-flex align-items-center">
                        
                        <span class="course-name"> <span class="course-bullet me-3"></span> No online courses available</span>
                     </div>
                  </li>
               @endif
            </ul>
         </div>

         <!-- Offline Courses Section -->
         <div class="col-lg-6 ps-lg-5" id="offline-course">
            <h2 class="robot_slab text_color mb-md-4 text-capitalize">
               <i class="fa-solid fa-chalkboard me-2"></i> {{ $offlineCategory->name ?? 'Offline Course' }}
            </h2>
            <ul class="course-list list-unstyled">
               @if(isset($offlineCategory) && $offlineCategory->courses->count() > 0)
                  @foreach($offlineCategory->courses as $course)
                     <li class="course-item pb-3 border-bottom">
                        <div class="d-flex align-items-center justify-content-between">
                           
                           <span class="course-name"><span class="course-bullet me-3"></span> {{ $course->name }}</span>
                           <div class="couses_icons">
                           <a href="/" class="btn btn-sm btn-outline-primary ms-2" download>
                           <i class="fa-brands fa-youtube"></i>
                           </a>
                           
                           <a href="assets/frontend/img/courses_pdf.pdf" class="btn btn-sm btn-outline-primary ms-2" download>
                              <i class="fas fa-file-pdf"></i>
                           </a>
                        </div>
                        </div>
                     </li>
                  @endforeach
               @else
                  <li class="course-item pb-3">
                     <div class="d-flex align-items-center">
                       
                        <span class="course-name"> <span class="course-bullet me-3"></span> No offline courses available</span>
                     </div>
                  </li>
               @endif
            </ul>
         </div>
      </div>
   </div>
</section>

<!-- Enroll Now Button Section -->
<section class="enroll-section pb-md-5 pb-4 pt-md-4">
   <div class="container">
      <div class="row">
         <div class="col-12 text-center">
            <button type="button" class=" enroll-btn robot_slab" data-bs-toggle="modal" data-bs-target="#enrollModal">
               ENROLL NOW
            </button>            
         </div>
      </div>
   </div>
</section>

<!-- Enroll Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title robot_slab" id="enrollModalLabel">
                    Enroll in Course
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
               @include('frontend.components.enrolment-enquiry')
            </div>

        </div>
    </div>
</div>

@if (session('success') || session('error') || $errors->any())
<script defer>
   setTimeout(() => $('.enroll-btn').trigger('click'), 500);
</script>
@endif

@endsection
