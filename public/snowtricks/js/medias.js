jQuery(document).ready(function ($) {

    $('#medias-show').click(function () {
      document.getElementById("images").style.display = "block";
      document.getElementById("videos").style.display = "block";
      document.getElementById("medias-show").style.display = "none";
      document.getElementById("medias-hidden").style.display = "block";
      document.getElementById("medias-divider").style.display = "block";
    })
  
    $('#medias-hidden').click(function () {
      document.getElementById("images").style.display = "none";
      document.getElementById("videos").style.display = "none";
      document.getElementById("medias-hidden").style.display = "none";
      document.getElementById("medias-show").style.display = "block";
      document.getElementById("medias-divider").style.display = "none";
    })
  
  });