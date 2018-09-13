// this variable is the list in the dom, it's initiliazed when the document is ready
var $collectionImages;
var $collectionVideos;
// the link which we click on to add new items
var $addNewImage = $('<a href="#" class="btn btn-info">Ajouter une image</a>');
var $addNewVideo = $('<a href="#" class="btn btn-info">Ajouter une vidéo</a>');
// when the page is loaded and ready
$(document).ready(function () {
    // get the collectionHolder, initilize the var by getting the list;
    $collectionImages = $('#images');
    $collectionVideos = $('#videos');
    // append the add new item link to the collectionHolder
    $collectionImages.append($addNewImage);
    $collectionVideos.append($addNewVideo);
    // add an index property to the collectionHolder which helps track the count of forms we have in the list
    $collectionImages.data('index', $collectionImages.find('.panel-images').length)
    $collectionVideos.data('index', $collectionVideos.find('.panel-videos').length)
    // finds all the panels in the list and foreach one of them we add a remove button to it
    // add remove button to existing items
    $collectionImages.find('.panel-images').each(function () {
        // $(this) means the current panel that we are at
        // which means we pass the panel to the addRemoveButton function
        // inside the function we create a footer and remove link and append them to the panel
        // more informations in the function inside
        addRemoveImageButton($(this));
    });

    $collectionVideos.find('.panel-videos').each(function () {
        // $(this) means the current panel that we are at
        // which means we pass the panel to the addRemoveButton function
        // inside the function we create a footer and remove link and append them to the panel
        // more informations in the function inside
        addRemoveVideoButton($(this));
    });
    // handle the click event for addNewItem
    $addNewImage.click(function (e) {
        // preventDefault() is your  homework if you don't know what it is
        // also look up preventPropagation both are usefull
        e.preventDefault();
        // create a new form and append it to the collectionHolder
        // and by form we mean a new panel which contains the form
        addNewFormImage();
    })

    $addNewVideo.click(function (e) {
        // preventDefault() is your  homework if you don't know what it is
        // also look up preventPropagation both are usefull
        e.preventDefault();
        // create a new form and append it to the collectionHolder
        // and by form we mean a new panel which contains the form
        addNewFormVideo();
    })
});
/*
 * creates a new form and appends it to the collectionHolder
 */
function addNewFormImage() {
    // getting the prototype
    // the prototype is the form itself, plain html
    var prototype = $collectionImages.data('prototype');
    // get the index
    // this is the index we set when the document was ready, look above for more info
    var index = $collectionImages.data('index');
    // create the form
    var newForm = prototype;
    // replace the __name__ string in the html using a regular expression with the index value
    newForm = newForm.replace(/__name__/g, index);
    // incrementing the index data and setting it again to the collectionHolder
    $collectionImages.data('index', index+1);
    // create the panel
    // this is the panel that will be appending to the collectionHolder
    var $panel = $('<div class="panel-images panel-images-warning"><div class="panel-images-heading"></div></div>');
    // create the panel-body and append the form to it
    var $panelBody = $('<div class="panel-images-body"></div>').append(newForm);
    // append the body to the panel
    $panel.append($panelBody);
    // append the removebutton to the new panel
    addRemoveImageButton($panel);
    // append the panel to the addNewItem
    // we are doing it this way to that the link is always at the bottom of the collectionHolder
    $addNewImage.before($panel);
}

function addNewFormVideo() {
    // getting the prototype
    // the prototype is the form itself, plain html
    var prototype = $collectionVideos.data('prototype');
    // get the index
    // this is the index we set when the document was ready, look above for more info
    var index = $collectionVideos.data('index');
    // create the form
    var newForm = prototype;
    // replace the __name__ string in the html using a regular expression with the index value
    newForm = newForm.replace(/__name__/g, index);
    // incrementing the index data and setting it again to the collectionHolder
    $collectionVideos.data('index', index+1);
    // create the panel
    // this is the panel that will be appending to the collectionHolder
    var $panel = $('<div class="panel-videos panel-videos-warning"><div class="panel-videos-heading"></div></div>');
    // create the panel-body and append the form to it
    var $panelBody = $('<div class="panel-videos-body"></div>').append(newForm);
    // append the body to the panel
    $panel.append($panelBody);
    // append the removebutton to the new panel
    addRemoveVideoButton($panel);
    // append the panel to the addNewItem
    // we are doing it this way to that the link is always at the bottom of the collectionHolder
    $addNewVideo.before($panel);
}

/**
 * adds a remove button to the panel that is passed in the parameter
 * @param $panel
 */
function addRemoveImageButton ($panel) {
    // create remove button
    var $removeButton = $('<a href="#" class="btn btn-danger">Supprimer l\'image</a>');
    // appending the removebutton to the panel footer
    var $panelFooter = $('<div class="panel-images-footer"></div>').append($removeButton);
    // handle the click event of the remove button
    $removeButton.click(function (e) {
        e.preventDefault();
        // gets the parent of the button that we clicked on "the panel" and animates it
        // after the animation is done the element (the panel) is removed from the html
        $(e.target).parents('.panel-images').slideUp(1000, function () {
            $(this).remove();
        })
    });
    // append the footer to the panel
    $panel.append($panelFooter);
}

/**
 * adds a remove button to the panel that is passed in the parameter
 * @param $panel
 */
function addRemoveVideoButton ($panel) {
    // create remove button
    var $removeButton = $('<a href="#" class="btn btn-danger">Supprimer la vidéo</a>');
    // appending the removebutton to the panel footer
    var $panelFooter = $('<div class="panel-videos-footer"></div>').append($removeButton);
    // handle the click event of the remove button
    $removeButton.click(function (e) {
        e.preventDefault();
        // gets the parent of the button that we clicked on "the panel" and animates it
        // after the animation is done the element (the panel) is removed from the html
        $(e.target).parents('.panel-videos').slideUp(1000, function () {
            $(this).remove();
        })
    });
    // append the footer to the panel
    $panel.append($panelFooter);
}
