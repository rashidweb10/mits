<script src="{{ asset('assets/frontend/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/aos.js') }}"></script>
<script src="{{ asset('assets/frontend/js/swiper.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
<script>
   AOS.init({
     duration: 800, // Duration of animations
     once: true, // Whether animation should happen only once
   });
   var a = 0;
   $(window).scroll(function() {
    if ($('#counter').length === 0) return;
     var oTop = $('#counter').offset().top - window.innerHeight;
     if (a == 0 && $(window).scrollTop() > oTop) {
       $('.counter-value').each(function() {
         var $this = $(this),
           countTo = $this.attr('data-count');
         $({
           countNum: $this.text()
         }).animate({
           countNum: countTo
         }, {
           duration: 2000,
           easing: 'swing',
           step: function() {
             $this.text(Math.floor(this.countNum) + '+'); // Add the + during the count
           },
           complete: function() {
             $this.text(this.countNum + '+'); // Ensure the + is added at the end
           }
         });
       });
       a = 1;
     }
   });
</script>
<script>
   $(document).ready(function() {
     $(".curseswe_offer").owlCarousel({
       loop: true,
       margin: 40,
       nav: true,
       autoplay: true,
       autoplayTimeout: 3000,
       responsive: {
         0: {
           items: 3
         },
         768: {
           items: 3
         },
         1024: {
           items: 3
         }
       }
     });
   });
</script>
<script>
   $(document).ready(function() {
     $(".news_update").owlCarousel({
       loop: true,
       margin: 20,
       nav: true,
       autoplay: false,
       autoplayTimeout: 3000,
       responsive: {
         0: {
           items: 1
         },
         768: {
           items: 3
         },
         1024: {
           items: 3
         }
       }
     });
   });
</script>
<script>
   window.addEventListener("scroll", function() {
     const topEl = document.querySelector(".top_position");
     if (window.scrollY > 60) { // adjust value as needed
       topEl.classList.add("fixed-top");
     } else {
       topEl.classList.remove("fixed-top");
     }
   });
</script>
<script>
   $(document).ready(function() {
     $('.submenu').hover(function() {
       // On hover in
       $(this).closest('li').children('a.nav-link').addClass('active');
     }, function() {
       // On hover out
       $(this).closest('li').children('a.nav-link').removeClass('active');
     });
   });
</script>
<script>
   function nhToggleMenu() {
     const overlay = document.getElementById('nhOverlay');
     const toggleBtn = document.getElementById('nhMenuToggle');
     const items = document.querySelectorAll('#nhOverlay ul li a');
     // OPEN
     if (!overlay.classList.contains('nh-open')) {
       overlay.classList.add('nh-open');
       toggleBtn.classList.add('nh-open');
       items.forEach((item, index) => {
         item.classList.remove('animate__animated', 'animate__fadeInUp');
         item.style.opacity = "0";
         void item.offsetWidth; // restart animation
         item.classList.add('animate__animated', 'animate__fadeInUp');
         item.style.animationDelay = `${0.2 * index}s`;
         item.style.opacity = "1";
       });
     } else {
       // CLOSE
       overlay.classList.remove('nh-open');
       toggleBtn.classList.remove('nh-open');
       items.forEach(item => {
         item.style.opacity = "0";
       });
     }
   }
</script>
<script>
   AOS.init({
     duration: 800,
     once: true
   });
</script>
<script>
   $(document).ready(function() {
     $('[data-fancybox="gallery1"]').fancybox({
       thumbs: { autoStart: true },
       toolbar: true,
       buttons: ["zoom", "close"]
     });
   });
</script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('custom.recaptcha_site_key') }}"></script>

<script>
    function protect_with_recaptcha_v3(formElement, action) {
        event.preventDefault();

        grecaptcha.ready(function () {
            grecaptcha.execute('{{ config('custom.recaptcha_site_key') }}', { action: action }).then(function (token) {
                // Create or update recaptcha_token input
                let tokenInput = formElement.querySelector('[name="recaptcha_token"]');
                if (!tokenInput) {
                    tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = 'recaptcha_token';
                    formElement.appendChild(tokenInput);
                }
                tokenInput.value = token;

                //alert(token);

                // Create or update recaptcha_action input
                let actionInput = formElement.querySelector('[name="recaptcha_action"]');
                if (!actionInput) {
                    actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'recaptcha_action';
                    formElement.appendChild(actionInput);
                }
                actionInput.value = action;

                formElement.submit();
            });
        });
    }
</script>