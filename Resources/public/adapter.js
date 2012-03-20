$(function () {
    var basePath = jQuery.venne.getBasePath();

    $('textarea[data-venne-form-editor]').ckeditor(function () {
    }, {
        filebrowserBrowseUrl: basePath+'/resources/kcfinderModule/browse.php?type=files',
        filebrowserImageBrowseUrl:basePath+'/resources/kcfinderModule/browse.php?type=images',
        filebrowserFlashBrowseUrl:basePath+'/resources/kcfinderModule/browse.php?type=flash',
        filebrowserUploadUrl:basePath+'/resources/kcfinderModule/browse.php?type=files',
        filebrowserImageUploadUrl:basePath+'/resources/kcfinderModule/browse.php?type=images',
        filebrowserFlashUploadUrl:basePath+'/resources/kcfinderModule/browse.php?type=flash'
    });
});