@php 
    $categories = DB::table('course_categories')->where('is_active', '1')->where('id', '!=', 1)->get();
@endphp

<section class="courses_we_offered pt-4 pt-md-5 pb-md-5 pb-4 position-relative" data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-12 aos-init aos-animate">
            <div class="text-start mb-md-4 mb-2 pt-2">
               <h3 class="robot_slab text_color text-left">Courses we offer</h3>
            </div>
         </div>
         <div class="owl-carousel curseswe_offer">

            @forelse($categories as $category)
                <div class="item">
                    <div class="position-relative">
                        <div class="offered_box">
                            <img
                                class="jbox-img rotate w-100 hvr-bounce-in"
                                src="{{ uploaded_asset($category->image) }}"
                                alt="{{ $category->name }}">

                            <p class="text-center pt-1">
                                {{ $category->name }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center">No courses available</p>
            @endforelse            

         </div>
      </div>
   </div>
</section>