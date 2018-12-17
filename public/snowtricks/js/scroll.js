jQuery(document).ready(function ($) {

    $(window).scroll(function () {
      if ($(this).scrollTop() > 400) {
        $('.scrollup').fadeIn('slow');
      } else {
        $('.scrollup').fadeOut('slow');
      }
    });
  
  });