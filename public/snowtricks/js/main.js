jQuery(document).ready(function ($) {

//---------------------------------------------
// Scroll
//---------------------------------------------
$('.scrolldown').click(function(event) {
    event.preventDefault();
    var n = $(document).height();
    $('html, body').animate({ scrollTop: n }, 1000);
});

$(window).scroll(function () {
    if ($(this).scrollTop() > 400) {
        $('.scrollup').fadeIn('slow');
    } else {
        $('.scrollup').fadeOut('slow');
    }
});

$('.scrollup').click(function () {
    $('html, body').animate({scrollTop: 0}, 1000);
    return false;
});

});
