
var PhotosResult = "";
var Count = 0;
var UploadedFiles = 0;
function photos_fileDialogComplete(numFilesSelected, numFilesQueued) {
    try {
        if (numFilesQueued > 0) {
            PhotosResult = numFilesQueued == '1' ? ' image' : ' images';
            PhotosResult = numFilesQueued + PhotosResult + " attached";
            Count = parseInt(numFilesQueued);
            $('#AddPhotos').val('Загрузка...');
            $('#submitStatus')
                .attr('disabled', 'disabled')
                .addClass('disabled');
            this.startUpload();
        }
    } catch (ex) {
    }
}

function photos_uploadProgress(file, bytesLoaded) {


    try {
        var pw = 600;
        var w = Math.ceil(pw * (UploadedFiles / Count + (bytesLoaded / (file.size * Count))));
        if(w > pw) w = pw;
        $('#Progress').stop().animate({ width: w });
    } catch (ex) {
    }
}
function photos_uploadSuccess(file, serverData) {
    try {
        UploadedFiles++;
        console.log('ответ сервера:');
        console.dir(serverData);


		 e = eval( "(" + serverData + ")" );
        //tpl = $('.mUploadImages .images-item-templ').html();

        $(".images-list").append(e.item);
        init();
        $(".images-list")[0].scrollTop = $(".images-list")[0].scrollHeight;
        //$(".images-list div:last").attr('');

    } catch (ex) {

    }
}

function photos_uploadComplete(file) {
    try {
        if (this.getStats().files_queued > 0) {
            this.startUpload();
        } else {
            //$('#UploadPhotos').hide();
            //$('#Buttons').prepend('<span id="UploadResult" class="images">' + PhotosResult + '</span>');
			$('#Progress').animate({ width: 0 },0);
			$('#AddPhotos').val('Загрузить фото');
			UploadedFiles=0;
			Count = 0;
        }
    } catch (ex) {
    }
}

function file_dialog_start_handler()
{

size ={
    "max_width": $('.imgsize-top-tools_width').val(),
    "max_height": $('.imgsize-top-tools_height').val()};
    console.dir($.extend(size, params));
    this.setPostParams($.extend(size, params));
}

function photos_fileQueueError(file, errorCode, message) {
    try {
        switch (errorCode) {
            case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                alert('Слишком много. Максимум - пять фоток.');
                break;
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
            case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
            case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                break;
        }
    } catch (ex) {
    }

}

function photos_fileQueueError(file, code, message)
{


    console.dir(error);
}

function swfuploadLoaded() {
    $('#Buttons object').hover(
        function() {
            $(this).next().addClass('hover');
        },
        function() {
            $(this).next().removeClass('hover');
        });

}


var swfuPhotos;
var  uploadScript;
var params;
function BindSWFUpload(uploadScripts, param) {


	uploadScript = uploadScripts;
    params = param;


    var swfuPhotosSettings = {
        file_dialog_complete_handler: photos_fileDialogComplete,
        upload_progress_handler: photos_uploadProgress,
        upload_success_handler: photos_uploadSuccess,
        upload_complete_handler: photos_uploadComplete,
        swfupload_loaded_handler: swfuploadLoaded,
        file_queue_error_handler: photos_fileQueueError,
        'file_dialog_start_handler': file_dialog_start_handler,

        file_size_limit: "10 MB",
        file_types: "*.jpg;*.png;*.gif;*.jpeg",
        file_types_description: "JPG, PNG, GIF images",
        file_upload_limit: "1000000",
        button_placeholder_id: "fAddPhotos"
    }
    var post = {
        "uli_upload": "true",
        "class": "muploadimages"
        }
    params = $.extend(post, params);


    var defaultSettings = {
        flash_url: MFWPath+"/swf/swfupload.swf",
         upload_url: uploadScript,
        post_params: params,

        button_width: 115,
        button_height: 32,
        button_image_url: MFWPath+"/images/white50.png",

        button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
        button_cursor: SWFUpload.CURSOR.HAND
    }

    swfuPhotos = new SWFUpload($.extend(swfuPhotosSettings, defaultSettings));
    init();


}

function save_pos()
{
    var post_list = "";
    $('.mUploadImages .images-list div').each(function()
    {
        id = $(this).attr('data-image-id');
        post_list += id+','

    });
//alert(post_list);
    $.post
    (
        uploadScript,
        {'class':"muploadimages", 'uli_pos':post_list},
        function(e)
        {
           //alert(e);
            save_hide();
        }
    )
}



function init()
{

    save_hide();

    $('.mUploadImages .images-list img').each(function(){
        img_center ($(this));
    });

    $( ".mUploadImages .images-list").sortable( {'update': function(event, ui) {
        save_show();
        save_pos();

    }}).disableSelection();

    $('.mUploadImages .images-list a.fa.fa-times').click
    (
        function ()
        {
            var id = $(this).parent().attr('data-image-id');
            save_show();

            $.post
            (
                uploadScript,
                {'class':"muploadimages", 'uli_del':id},
                function(e)
                {

                    $('div[data-image-id="'+id+'"]').remove();
                    save_hide();
                }
            )
        }
    );


}

function save_show()
{
    $('.mUploadImages .top-tools .save').show();
}

function save_hide()
{
    $('.mUploadImages .top-tools .save').hide();
}
function img_center (el)
{
    el.hide();
    el.bindImageLoad(function()
    {
        $(this).show();
        imgheigth = el.height();
        imgwidth  = el.width();
        $(this).css('margin-top', '-' + (imgheigth/2) + 'px');
        $(this).css('margin-left', '-' + (imgwidth/2) + 'px');
        $(this).css('top', '50%');
        $(this).css('left', '50%');

    });

}


(function ($) {

    $.fn.bindImageLoad = function (callback) {

        function isImageLoaded(img) {

            // Во время события load IE и другие браузеры правильно

            // определяют состояние картинки через атрибут complete.

            // Исключение составляют Gecko-based браузеры.

            if (!img.complete) {

                return false;

            }

            // Тем не менее, у них есть два очень полезных свойства: naturalWidth и naturalHeight.

            // Они дают истинный размер изображения. Если какртинка еще не загрузилась,

            // то они должны быть равны нулю.

            if (typeof img.naturalWidth !== "undefined" && img.naturalWidth === 0) {

                return false;

            }

            // Картинка загружена.

            return true;

        }



        return this.each(function () {

            var ele = $(this);

            if (ele.is("img") && $.isFunction(callback)) {

                ele.one("load", callback);

                if (isImageLoaded(this)) {

                    ele.trigger("load");

                }

            }

        });

    };

})(jQuery);