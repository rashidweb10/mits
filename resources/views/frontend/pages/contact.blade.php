@extends('frontend.layouts.app')

@section('meta.title', 'Contact Us')
@section('meta.description', 'Contact Us')

@section('content')

@include('frontend.partials.breadcrumb', ['title' => "Contact Us"])

<section class="py-5">
   <div class="container">
      <div class="row g-5">
         <!-- Left Side -->
         <div class="col-lg-12 mt-0 pt-md-5 pt-4">
            <h3 class=" mb-md-5 mb-4 robot_slab ">Get in touch</h3>
            <div class="row g-0">
               <!-- Address -->
               <div class="col-md-6 border-end pe-md-4  mb-md-0 mb-4">
                  <div class="d-flex icon_hovers h-100">
                     <div class="icon-circle me-3" style="width: 54px;">
                        <i class="fa-solid fa-map"></i>
                     </div>
                     <div>
                        <h5 class="fw-semibold mb-1">MarinArch – Online Coaching Classes</h5>
                        <p class="mb-0 "><strong>Address:</strong> {!! get_setting('address') !!}
                        </p>
                     </div>
                  </div>
               </div>
               <!-- Email -->
               <div class="col-md-3 border-end ps-md-5 pe-md-5 mb-md-0 mb-4">
                  <div class="d-flex icon_hovers h-100">
                     <div class="icon-circle me-3">
                        <i class="fa-solid fa-envelope"></i>
                     </div>
                     <div>
                        <h5 class="fw-semibold mb-1">Email Us</h5>
                        <p class="mb-0">{{get_setting('email')}}</p>
                     </div>
                  </div>
               </div>
               <!-- Phone -->
               <div class="col-md-3 ps-md-5">
                  <div class="d-flex icon_hovers h-100">
                     <div class="icon-circle me-3">
                        <i class="fa-solid fa-phone"></i>
                     </div>
                     <div>
                        <h5 class="fw-semibold mb-1">Call Us</h5>
                        <p class="mb-0 ">{{get_setting('phone')}}</p>
                     </div>
                  </div>
               </div>
            </div>
           
            
			
			<!-- <div class="bg-light p-4 p-md-5 rounded-3 shadow-sm mt-3">
               <h3 class=" mb-4 robot_slab ">Send us a message</h3>

<form class="needs-validation" id="contactForm" action="{{route('form.submit')}}" method="POST" onsubmit="protect_with_recaptcha_v3(this, 'contact')">
  @include('frontend.components.form-alert')
  @csrf
  <div class="row mb-3">
    <div class="col-md-6 mb-3 mb-md-0">
      <input type="hidden" name="form_name" value="contact">
      <label for="name" class="form-label fw-medium">Name</label>
      <input 
        type="text" 
        class="form-control" 
        id="name" 
        name="name" 
        required 
      />
      <div class="invalid-feedback">Please enter your name.</div>
    </div>
    <div class="col-md-6">
      <label for="company" class="form-label fw-medium">Company</label>
      <input 
        type="text" 
        class="form-control" 
        id="company" 
        name="company" 
        required
      />
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6 mb-3 mb-md-0">
      <label for="phone" class="form-label fw-medium">Phone</label>
      <input type="tel" class="form-control" id="phone" name="phone"
       pattern="[0-9]{10}" title="Please enter exactly 10 digits"
       maxlength="10" inputmode="numeric" required>
    </div>
    <div class="col-md-6">
      <label for="email" class="form-label  fw-medium">Email</label>
      <input 
        type="email" 
        class="form-control" 
        id="email" 
        name="email" 
        required 
      />
      <div class="invalid-feedback">Please enter a valid email.</div>
    </div>
  </div>

  <div class="mb-3">
    <label for="subject" class="form-label fw-medium">Subject</label>
    <input 
      type="text" 
      class="form-control" 
      id="subject" 
      name="subject" 
    />
  </div>

  <div class="mb-4">
    <label for="message" class="form-label fw-medium">Message</label>
    <textarea 
      class="form-control" 
      id="message" 
      name="message" 
      rows="5" 
    ></textarea>
    <div class="invalid-feedback">Please enter your message.</div>
  </div>

  <button 
    type="submit"
    class="cnotact_btns btn btn-primary py-2 mt-1 fs-5"
  >
    Send
  </button>
</form>


            </div> -->
			
			
           
            
            
            <!-- Social Media -->
            <!--<div class="mt-5">-->
            <!--   <h5 class="fw-semibold mb-3 robot_slab ">Follow our social media</h5>-->
            <!--   <div class="d-flex gap-2">-->
            <!--      <a href="{{ get_setting('facebook_url') }}" class="icon-circle"><i class="fa-brands fa-facebook"></i></a>-->
            <!--      <a href="{{ get_setting('instagram_url') }}" class="icon-circle"><i class="fa-brands fa-instagram"></i></a>-->
            <!--      <a href="{{ get_setting('x_url') }}" class="icon-circle"><i class="fa-brands fa-twitter"></i></a>-->
            <!--      <a href="{{ get_setting('youtube_url') }}" class="icon-circle"><i class="fa-brands fa-youtube"></i></a>-->
            <!--   </div>-->
            <!--</div>-->
         </div>
         <!-- Right Side - Contact Form -->
         <div class="col-lg-12 mt-md-5 mt-4">
             {!! get_setting('google_map') !!}
         </div>
      </div>
   </div>
</section>

@endsection