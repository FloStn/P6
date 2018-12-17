var $collectionImages;
var $collectionVideos;

var $addNewImage = $('<div class="col-md-4 mt-3 text-center"><a href="#" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle px-1"></i>Ajouter une image</a></div>');
var $addNewVideo = $('<div class="col-md-4 mt-3 text-center"><a href="#" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle px-1"></i>Ajouter une vidéo</a></div>');

$(document).ready(function () {
  $collectionImages = $('#images');
  $collectionVideos = $('#videos');
  $collectionImages.append($addNewImage);
  $collectionVideos.append($addNewVideo);
  $collectionImages.data('index', $collectionImages.find('.panel-images').length)
  $collectionVideos.data('index', $collectionVideos.find('.panel-videos').length)
  $collectionImages.find('.panel-images').each(function () {
    addRemoveImageButton($(this));
  });

  $collectionVideos.find('.panel-videos').each(function () {
    addRemoveVideoButton($(this));
  });

  $addNewImage.click(function (e) {
    e.preventDefault();
    addNewFormImage();
  })

  $addNewVideo.click(function (e) {
    e.preventDefault();
    addNewFormVideo();
  })
});

function addNewFormImage() {
  var prototype = $collectionImages.data('prototype');
  var index = $collectionImages.data('index');
  var newForm = prototype;
  newForm = newForm.replace(/__name__/g, index);
  $collectionImages.data('index', index + 1);
  var $panel = $('<div class="panel-images panel-images-warning col-md-4"><div class="panel-images-heading p-3"></div></div>');
  var $panelBody = $('<div class="panel-images-body"></div>').append(newForm);
  $panel.append($panelBody);
  addRemoveImageButton($panel);
  $addNewImage.before($panel);
}

function addNewFormVideo() {
  var prototype = $collectionVideos.data('prototype');
  var index = $collectionVideos.data('index');
  var newForm = prototype;
  newForm = newForm.replace(/__name__/g, index);
  $collectionVideos.data('index', index + 1);
  var $panel = $('<div class="panel-videos panel-videos-warning col-md-4"><div class="panel-videos-heading p-3"></div></div>');
  var $panelBody = $('<div class="panel-videos-body"></div>').append(newForm);
  $panel.append($panelBody);
  addRemoveVideoButton($panel);
  $addNewVideo.before($panel);
}

function addRemoveImageButton($panel) {
  var $removeButton = $('<a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>');
  var $panelFooter = $('<div class="panel-images-footer text-center"></div>').append($removeButton);
  $removeButton.click(function (e) {
    e.preventDefault();
    $(e.target).parents('.panel-images').slideUp(0, function () {
      $(this).remove();
    })
  });
  $panel.append($panelFooter);
}

function addRemoveVideoButton($panel) {
  var $removeButton = $('<a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></a>');
  var $panelFooter = $('<div class="panel-videos-footer text-center"></div>').append($removeButton);
  $removeButton.click(function (e) {
    e.preventDefault();
    $(e.target).parents('.panel-videos').slideUp(0, function () {
      $(this).remove();
    })
  });
  $panel.append($panelFooter);
}