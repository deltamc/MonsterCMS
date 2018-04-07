
(function( $ ) {
    $.mcmsDelete = function(options ) {

        var settings = $.extend( {
            //перед запросом ajax
            'before'      : function(){},
            //результат удаления
            'result'      : function(id, result, message){
                if (message != '') alert(message);
            },
            //ajax адрес
            'ajaxUrl'     : null,
            //редирект после удаления
            'lacationUrl' : null,
            //после удаления, удалит элемент
            'remove'      : null,
            'deleteText'  : "Delete?"
        }, options);

        if (!options.ajaxUrl) return;
        var result = confirm(options.deleteText);
        if (!result) return;

        if($.isFunction(options.before)) {
            options.before();
        }

        $.ajax({
            url: options.ajaxUrl,
            dataType: "json",
            success: function (data, textStatus) {
                if (data.message != '') alert(data.message);
                var id = data.id;

                if(options.remove instanceof $  && data.delete === 'true') {
                    options.remove.fadeOut(500, function(){
                        this.remove();});
                }

                if($.isFunction(options.result)) {
                    options.result(data.id, data.result, data.message);
                }

                if(options.lacationUrl && data.delete === 'true'){
                    location.href=options.lacationUrl;
                }

            }
        });


    };
})(jQuery);
