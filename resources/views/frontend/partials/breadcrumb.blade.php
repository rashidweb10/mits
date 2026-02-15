@if(!request()->is('auth*'))
    <div class="about_banner">
        <img src="{{ isset($image) && !empty($image) ? uploaded_asset($image) : asset('assets/frontend/img/b1.jpeg') }}" class="w-100">
    </div>
@else 
<div class="about_banner_spacer"></div>
  <style>
    @media (min-width: 767px) {
        .about_banner_spacer {
            padding: 80px 0;
        }
    }
  </style>
@endif