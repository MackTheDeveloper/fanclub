// button ripple effect start
(function (window, $) {

  $(function () {

    $('.fill-btn , .border-btn').on('click', function (event) {
      // event.preventDefault();
      var $btn = $(this),
        $div = $('<div/>'),
        btnOffset = $btn.offset(),
        xPos = event.pageX - btnOffset.left,
        yPos = event.pageY - btnOffset.top;

      $div.addClass('ripple-effect');
      $div
        .css({
          height: $btn.height(),
          width: $btn.height(),
          top: yPos - ($div.height() / 2),
          left: xPos - ($div.width() / 2),
          background: $btn.data("ripple-color") || "#fff"
        });
      $btn.append($div);

      window.setTimeout(function () {
        $div.remove();
      }, 2000);
    });

  });

})(window, jQuery);
// button ripple effect end

if($('.tab-section').length){
  const mouseWheel = document.querySelector('.tab-section');
  mouseWheel.addEventListener('wheel', function(e) {
      const race = 30; // How many pixels to scroll

      if (e.deltaY > 0) // Scroll right
          mouseWheel.scrollLeft += race;
      else // Scroll left
          mouseWheel.scrollLeft -= race;
      e.preventDefault();
  });
}


$(document).on("click",'.menu-icon', function () {
  $(".sideMenu").toggleClass("active");
  $("body").toggleClass("scroll-stop");
  $(".backBg").toggleClass("show");
});
$(document).on("click",'.closeIcons', function () {
  $(".sideMenu").toggleClass("active");
  $("body").toggleClass("scroll-stop");
  $(".backBg").toggleClass("show");
});
$(document).on("click",'.backBg', function () {
  $(".sideMenu").toggleClass("active");
  $("body").toggleClass("scroll-stop");
  $(".backBg").toggleClass("show");
});



$(document).on("click",'.chat-person', function () {
  $(".chat-sidebar").toggleClass("active");
});
$(document).on("click",'.back-chat', function () {
  $(".chat-sidebar").toggleClass("active");
});




$('.music-language .dropdown-menu').on({
  "click": function (e) {
    e.stopPropagation();
  }
});
$('.music-btns button').on('click', function () {
  $('.dropdown-menu').removeClass('show');
});



$('.notification .dropdown-menu').on({
  "click": function (e) {
    e.stopPropagation();
  }
});
$('.closer').on('click', function () {
  $('.btn-group').removeClass('open');
});



$('.settingDropdown .dropdown-menu').on({
  "click": function (e) {
    e.stopPropagation();
  }
});
// $('.closer').on('click', function () {
//   $('.btn-group').removeClass('open');
// });



$(document).on('focusout','.inputs-group input, .inputs-group textarea, .number-group input, .date-group input',function () {
    var text_val = $(this).val();
  
    if (text_val === "") {
      $(this).removeClass('has-value');
    } else {
      $(this).addClass('has-value');
    }
});
// $('.inputs-group input, .inputs-group textarea, .number-group input, .date-group input').focusout(function () {
//   var text_val = $(this).val();

//   if (text_val === "") {
//     $(this).removeClass('has-value');
//   } else {
//     $(this).addClass('has-value');
//   }
// });
// $(document).ready(function () {
//   if ($('.inputs-group input').val() != '') {
//     $('.inputs-group input').addClass('has-value');
//   }
// })

$(document).ready(function () {
  $('.inputs-group input').each(function () {
    if ($(this).val() != '') {
      $(this).addClass('has-value');
    }
  })
  $('.inputs-group textarea').each(function () {
    if ($(this).val() != '') {
      $(this).addClass('has-value');
    }
  })
  $('.number-group input').each(function () {
    if ($(this).val() != '') {
      $(this).addClass('has-value');
    }
  })
  $('.date-group input').each(function () {
    if ($(this).val() != '') {
      $(this).addClass('has-value');
    }
  })
})



var dateValueCheck = function () {
  $('.hasDatepicker').each(function () {
    if($(this).val() != ''){
      $(this).addClass('has-value');
    }
  });
}

var timeValueCheck = function () {
  $('.timepicker').each(function () {
    if($(this).val() != ''){
      $(this).addClass('has-value');
    }
  });
}

var checkAutoFill = function () {
  $('input:-webkit-autofill').each(function () {
    $(this).addClass('has-value');
  });
}
setTimeout(function () {
  checkAutoFill();
}, 500)

setInterval(function(){
  dateValueCheck();
}, 1)

setInterval(function(){
  timeValueCheck();
}, 1)

// sticky navbar start

// window.onscroll = function() {myFunction()};

// var navbar = document.getElementsByClassName('sub-nav');
// var mainBody = document.getElementsByTagName('body');
// var stickyNavbar = navbar[0].offsetTop;

// function myFunction() {
//   if (window.pageYOffset >= stickyNavbar) {
//     navbar[0].classList.add("stickyNavbar");
//     mainBody[0].classList.add("stickyPadding");
//   } else {
//     navbar[0].classList.remove("stickyNavbar");
//     mainBody[0].classList.remove("stickyPadding");
//   }
// }






// OTP JS

// let digitValidate = function (ele) {
//   ele.value = ele.value.replace(/[^0-9]/g, '');
// }
// $('.otp-input').find('input').each(function () {
//   $(this).attr('maxlength', 1);
//   $(this).on('keyup', function (e) {
//     var parent = $($(this).parent());

//     if (e.keyCode === 8 || e.keyCode === 37) {
//       var prev = parent.find('input#' + $(this).data('previous'));

//       if (prev.length) {
//         $(prev).select();
//       }
//     } else if ((e.keyCode >= 48 && e.keyCode <= 57) || (e.keyCode >= 65 && e.keyCode <= 90) || (e.keyCode >= 96 && e.keyCode <= 105) || e.keyCode === 39) {
//       var next = parent.find('input#' + $(this).data('next'));

//       if (next.length) {
//         $(next).select();
//       } else {
//         if (parent.data('autosubmit')) {
//           parent.submit();
//         }
//       }
//     }
//   });
// });





$(document).ready(function () {

  $('.introduce-radio').on('click', function (event) {
    var value = $(this).val();
    if (value.trim().toUpperCase() == 'YES') {
      $('.yes-select').addClass('show')
      $('.no-select').removeClass('show')
    }
    else {
      $('.yes-select').removeClass('show')
      $('.no-select').addClass('show')
    }
  });
})




function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
      $('#imagePreview').hide();
      $('#imagePreview').fadeIn(650);
    }
    reader.readAsDataURL(input.files[0]);
  }
}
$("#imageUpload").change(function () {
  readURL(this);
});


$(document).ready(function () {
  $(".footer-block .s2").click(function () {
    $(this).toggleClass("show")
  })
})









$(document).ready(function () {
  $('.rounded-img-carousel .owl-carousel, .square-img-carousel .owl-carousel, .double-img-carousel .owl-carousel').owlCarousel({
    margin: 16,
    rewindNav: false,
    dots: false,
    nav: true,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1,
        margin: 16
      },
      400: {
        items: 2,
        margin: 16
      },
      576: {
        items: 2,
        margin: 16
      },
      768: {
        items: 4,
        margin: 24
      },
      1200: {
        items: 6,
        margin: 24
      }
    }
  })
})



// Sort By mobile js

$(document).ready(function () {
  $(".sortIcons").on("click", function () {
    $(".sortMenu").toggleClass("active");
    $("body").toggleClass("scroll-stop")
  });
  $(".closeIcons2").on("click", function () {
    $(".sortMenu").toggleClass("active");
    $("body").toggleClass("scroll-stop")
  });
});


// Scroll To Anchor jQuery

$(document).ready(function () {
  $('a[href^="#"]').on('click', function (e) {
    e.preventDefault();
    var target = this.hash;
    var $target = $(target);
    $('html, body').stop().animate({
      'scrollTop': $target.offset().top
    }, 900, 'swing', function () {
      // window.location.hash = target;
    });
  });
});




$(document).ready(function () {
  $('.collection-wrapper .owl-carousel').owlCarousel({
    margin: 16,
    rewindNav: false,
    dots: false,
    nav: true,
    responsiveClass: true,
    responsive: {
      0: {
        items: 1
      },
      450: {
        items: 2,
        margin: 24
      },
      768: {
        items: 3,
        margin: 24
      },
      991: {
        items: 3,
        margin: 24
      },
      1000: {
        items: 4,
        margin: 24
      },
      1200: {
        items: 4,
        margin: 24
      },
      // 1400: {
      //   items: 5,
      //   margin: 24
      // }
    }
  })
})



const slider = document.querySelector('.tab-section');
let isDown = false;
let startX;
let scrollLeft;

if(slider){
  slider.addEventListener('mousedown', (e) => {
    isDown = true;
    slider.classList.add('active');
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });
  slider.addEventListener('mouseleave', () => {
    isDown = false;
    slider.classList.remove('active');
  });
  slider.addEventListener('mouseup', () => {
    isDown = false;
    slider.classList.remove('active');
  });
  slider.addEventListener('mousemove', (e) => {
    if(!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 3; //scroll-fast
    slider.scrollLeft = scrollLeft - walk;
    console.log(walk);
  });
}

// $(".tab-section ul").scrollspy({ offset: -300 });


// $(document).ready(function(){
//   $(".tab-section ul li a, ul.sidebarScroll li a").click(function(){
//     $('html, body').animate({scrollTop: $($(this).attr("data")).offset().top - 190 }, 'slow')
//   })	
// })



$(document).ready(function(){
  
  $(document).on('mouseover','#stars li',function () {
    var onStar = parseInt($(this).data('value'), 10); 
    $(this).parent().children('li.star').each(function(e){
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });
    
  }).on('mouseout', function(){
    $(this).parent().children('li.star').each(function(e){
      $(this).removeClass('hover');
    });
  });
  
  $(document).on('click','#stars li',function () {
    var onStar = parseInt($(this).data('value'), 10); 

    $("#star-value").val($(this).data('value'))
    var stars = $(this).parent().children('li.star');
    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }
    
    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }
  });
  
});



// var textarea = document.querySelector('textarea');

// if(textarea){
//   textarea.addEventListener('keydown', autosize);

//   function autosize(){
//     var el = this;
//     setTimeout(function(){
//       el.style.cssText = 'height:auto; padding:0';
//       // for box-sizing other than "content-box" use:
//       // el.style.cssText = '-moz-box-sizing:content-box';
//       el.style.cssText = 'height:' + el.scrollHeight + 'px';
//     },0);
//   }
// }


$(document).ready(function(){
  $(".toggle-about").click(function(){
    $(".toggle-content").toggleClass("toggle-apply");
    if($(".toggle-content").hasClass("toggle-apply")){
      $(this).text('Read More');
    }else{
      $(this).text('Read Less');
    }
  })  
})
$(document).on('click','.toggle-btns',function () {
    $(this)
        .closest(".toggle-parent")
        .find(".toggle-content")
        .toggleClass("toggle-apply-2");
    if (
        $(this)
            .closest(".toggle-parent")
            .find(".toggle-content")
            .hasClass("toggle-apply-2")
    ) {
        $(this).text("Read More");
    } else {
        $(this).text("Read Less");
    }
    // $(".toggle-content").toggleClass("toggle-apply-2");
    // if($(".toggle-content").hasClass("toggle-apply-2")){
    //   $(this).text('Read More');
    // }else{
    //   $(this).text('Read Less');
    // }
});

function multiLineOverflows() {
  $('.toggle-content').each(function(){
      if($(this).hasClass('toggle-apply-2')){
          var clientHeight = $(this).prop('clientHeight');
          var scrollHeight = $(this).prop('scrollHeight');
          if ((scrollHeight-clientHeight)<10) {
              
              $(this).closest('.toggle-parent').find('.toggle-btns').hide();
          }
      }
  })
}


