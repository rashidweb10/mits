<footer class="footer pt-md-5 pt-4 pb-md-2 pb-4">
   <div class="container">
      <div class="row">
         <div class="col-md-2">
            <div class="footer_logo">
               <a href="/">
               <img title="MarinArch" class="w-100" src="{{ uploaded_asset(get_setting('logo')) }}">
               </a>
            </div>
         </div>
         <div class="col-md-10">
            <div class="row">
               <div class="col-md-12">
                  <div class="footer_link1 " >
                     <h5 class="robot_slab pb-2">Quick Link</h5>
                     <ul class="footer-menu">
                        <li class="footer-nav-item">
                           <a href="{{ route('home') }}">Home </a>
                        </li>
                        <li class="footer-nav-item">
                           <a href="{{ route('about') }}">Marine Arch</a>
                        </li>
                        <li class="footer-nav-item">
                           <a href="{{ route('courses') }}">Courses</a>
                        </li>
                        <li class="footer-nav-item">
                           <a href="{{ route('faculties') }}">Faculties</a>
                        </li>
                        <li class="footer-nav-item">
                           <a href="{{ route('testimonials') }}">Students Review</a>
                        </li>
                        <li class="footer-nav-item">
                           <a href="{{ route('blog.index') }}">Blogs</a>
                        </li>                        
                        <li class="footer-nav-item">
                           <a href="{{ route('contact') }}">Contact Us</a>
                        </li>
                     </ul>
                  </div>
               </div>
               <div class="col-lg-10  pt-4 pb-4">
                  <h4 class="text-md-start robot_slab">Go Social</h4>
                  <div class="d-flex gap-2 justify-content-md-start align-items-center flex-wrap">
                     <a target="_blank" href="{{ get_setting('facebook_url') }}">
                     <img class="w-20 hvr-bounce-in" src="{{ asset('assets/frontend/img/fb.png') }}">
                     </a>
                     <a target="_blank" href="{{ get_setting('instagram_url') }}">
                     <img class="w-20 hvr-bounce-in" src="{{ asset('assets/frontend/img/insta.png') }}">
                     </a>
                     <a target="_blank" href="{{ get_setting('linkedin_url') }}">
                     <img class="w-20 hvr-bounce-in" src="{{ asset('assets/frontend/img/in.png') }}">
                     </a>
                     <a target="_blank" href="{{ get_setting('youtube_url') }}">
                     <img class="w-20 hvr-bounce-in" src="{{ asset('assets/frontend/img/yt.png') }}">
                     </a>
                     <div class="mobile-playstore">
                        <img class="playstore_size" src="{{ asset('assets/frontend/img/icon-play-store.png') }}" />
                     </div>
                  </div>
               </div>
               <div class="col-lg-2 pt-5 pb-4 desktop-playstore">
                  <img class="playstore_size" src="{{ asset('assets/frontend/img/icon-play-store.png') }}" />
               </div>
            </div>
         </div>
         <div class="col-md-2"></div>
         <div class="col-md-7">
            <p class="footer-copyright mb-0">© {{date("Y")}} Marinarch Online Academy. All Rights Reserved.</p>
         </div>
         <div class="col-md-3 text-end">
            <p class="footer-copyright mb-0 copyrighr2 text-md-end text-center">Powered by <a href="{{config('custom.author_url')}}" target="_blank" style="font-weight:bold">Maptek</a>
            </p>
         </div>
      </div>
   </div>
</footer>


<div class="whatsapp">
     <a href="https://api.whatsapp.com/send?phone=+919920062295" target="_blank" title="Contact Us">
         <img class="hvr-bounce-in" src="{{ asset('assets/frontend/img/whatsap.png') }}" style="width: 46px;" title="Contact Us">
     </a>
 </div>