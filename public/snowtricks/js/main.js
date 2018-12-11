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

$('#medias-show').click(function() {
    document.getElementById("images").style.display = "block";
    document.getElementById("videos").style.display = "block";
    document.getElementById("medias-show").style.display = "none";
    document.getElementById("medias-hidden").style.display = "block";
    document.getElementById("medias-divider").style.display = "block";
})

$('#medias-hidden').click(function() {
    document.getElementById("images").style.display = "none";
    document.getElementById("videos").style.display = "none";
    document.getElementById("medias-hidden").style.display = "none";
    document.getElementById("medias-show").style.display = "block";
    document.getElementById("medias-divider").style.display = "none";
})

});
