$(function(){ $(".win").mcms_window_link(); });


(function($){





    $.fn.MCMSwindow = function(option)
    {





        if(typeof option == "object")
        {

            var setting = $.extend
            (

                {

                    x:false,

                    y:false,

                    autoOpen:false,

                    mask:false,

                    quickClose:false //���� ����������� �� ������ ���� � ����� ����� (����� ����) � ��� ������� [esc]

                },

                option||{}

            );



            MCMSwindowCreate($(this), setting);



            //���������� ����, ���� ������ �������� autoOpen

            if(setting.autoOpen)

            {



                MCMSwindowShow($(this));

            }

            else

            {

                $(this).hide();

                $(this).attr('MCMSwshow','false');

            }

        }

        else if(option == "open")

        {

            MCMSwindowShow($(this));



        }

        else if(option == "close")

        {

            MCMSwindowHide($(this));

        }



    }



    MCMSwindowCreate = function(el, setting)

    {



        if(setting.mask)

        {

            MCMSwindowCreateMask();

        }



        MCMSwindowPosition(el, setting);



        el.find('*[name="close"]').click(function()

        {

            MCMSwindowHide(el);

        });



        $(window).resize(function ()

        {

            MCMSwindowPosition(el,setting);

        });



        $('.cke_button_maximize').click(function(){
            MCMSwindowShow(el);
        });



        if(setting.quickClose)

        {

            $(document).keyup(function(e)

            {



                if(e.keyCode == 27)

                {

                    MCMSwindowHide(el,setting);

                }

            });



            $(document).bind('click.mcmswindow',function(e)

            {





                if(el.attr('MCMSwshow') == 'true' && !MCMSwindowFindEl($(e.target),el))

                {

                    MCMSwindowHide(el);

                }





            });



        }













    }



    function MCMSwindowPosition(el,setting)
    {
        //������ ���� ��������
        var winH = $(window).height();
        var winW = $(window).width();


        //������������� �� ������ ���� ��������� �� ������

        if(!setting.y)
        {
            y = winH/2-el.height()/2;

            //����� ���� �� �������� �� �����
            if(y < 0)
            {
                y = 20;
            }
        }
        else
        {
            y = setting.y;
        }

        el.css('top',  y+getScrollXY()[1]);

        if(!setting.x)
        {
            x = winW/2-el.width()/2;

            //����� ���� �� �������� �� �����
            if(x < 0)
            {
                x = 20;
            }
        }
        else
        {
            x = setting.x;
        }

        el.css('left', x);

        if(setting.mask)
        {
            var maskHeight = $(document).height();
            var maskWidth = $(window).width();

            $('#MCMSwindowMask').css({'height':maskHeight});
            $('#MCMSwindowMask').css({'top':0,'left':0});


        }
    }



    function MCMSwindowHide(el)
    {
        $('#MCMSwindowMask').hide();

        el.attr('MCMSwshow','false');
        $('body').removeClass('mcms-openwin');
        el.hide();
    }



    function MCMSwindowShow(el)

    {

        $('body').addClass('mcms-openwin');

        $('#MCMSwindowMask').show();

        //$('#MCMSwindowMask').fadeIn(1000);

        //('#MCMSwindowMask').fadeTo("slow",0.8);
        $(this).attr('MCMSwshow','true');

        el.show();



    }



    function MCMSwindowFindEl(el,elfind)

    {



        e = el;



        while(e.parent().size() != 0)

        {



            if(e.get(0) == elfind.get(0))

            {

                return true;

            }

            e = e.parent();

        }

        return false;





    }



    function MCMSwindowCreateMask()

    {

        if($('#MCMSwindowMask').size() == 0)

        {

            maskHTML = '<div id="MCMSwindowMask"></div>';

            $('body').append(maskHTML);





        }


    }
    function MCMSwindowCreateHtml()
    {

        if($('#MCMSwindow').size() == 0)

        {

            html = '<div id="MCMSwindow">';
            html += '<a name="close" class="close"></a>';
            html += '<iframe src="';
            html += '" frameborder=0 scrolling=auto ';
            html += '" width=100% height="100%"></iframe>';
            html += '</div>';


            $('body').append(html);

        }
    }



    /* ��������� ������ � ���� */
    $.fn.mcms_window_link = function(option)
    {

        /* ��������� �� ���������� */
        if(typeof option != "object")
        {
            option = {};
        }

        var setting = $.extend

        (

            {

                x:false,

                y:false,

                autoOpen:true,

                mask:true,

                quickClose:false

            },

            option||{}

        );


        /* ������� ���� */
        MCMSwindowCreateHtml();

        $(this).click(
            function (event)
            {
                /* ��������� ������� �� ������*/
                event.preventDefault();

                /* ������ ����*/
                size =  $(this).attr("data-size");

                size = size.split("x");

                width = size[0];
                height = size[1];
                $('#MCMSwindow').width(width);
                $('#MCMSwindow').height(height);
                $('#MCMSwindow iframe').contents().find('body').html('<body><div style="position:absolute;left:50%;top:50%;margin-left: -50px;margin-top: -50px;width:100px;">Load...</div></body>');

                /*��������� ����*/
                $('#MCMSwindow').MCMSwindow(setting);

                url = $(this).attr('href');
                $('#MCMSwindow iframe').attr('src',url);
            });


    }









}

    )(jQuery);



function getScrollXY() {

    var scrOfX = 0, scrOfY = 0;

    if( typeof( window.pageYOffset ) == 'number' ) {

        // Netscape compliant

        scrOfY = window.pageYOffset;

        scrOfX = window.pageXOffset;

    } else if( document.body &&

        ( document.body.scrollLeft || document.body.scrollTop ) ) {

        // DOM compliant

        scrOfY = document.body.scrollTop;

        scrOfX = document.body.scrollLeft;

    } else if( document.documentElement &&

        ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {

        // IE6 Strict

        scrOfY = document.documentElement.scrollTop;

        scrOfX = document.documentElement.scrollLeft;

    }

    return [ scrOfX, scrOfY ];

}