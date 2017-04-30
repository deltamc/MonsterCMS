(function($){

    $.fn.MCMSmenu = function(options) {

        var opts = $.extend($.fn.MCMSmenu.defaults,options);

        this.each(
            function ()
            {
               
                MCMSmenu.init($(this), opts);
                
            }
        );

    }

    $.fn.MCMSmenu.defaults = {
        eventshow:'mouseover',
        eventhide:'mouseout'
    }

    MCMSmenu =
    {
        position: "v",
        showNode: function ()
        {

           $el = $(this).find("ul:first");
		   $thisEl = $(this);
		   /*
		   
		   if($el.size() == 0)
		   {
			   $el = $(this).parent().find("ul:first");
			   $thisEl = $(this).parent();
		   }
		   */
		    display = $thisEl.css("display");
			
			position = 'v';
			if(display != "block") position = 'h';
			
			//console.log("pos: "+position);
			//console.log("width: "+$(this).width());

           if(position == 'v')
           {
                $el.css("left", $thisEl.width());
                $el.css("top", 0);
           }
           else
           {
               $el.css("left", 0);
               $el.css("top",$thisEl.height())
           }
           $el.show();
        },

        hideNode:  function ()
        {
            
			
			$(this).find("ul:first").hide();
        },
        init: function ($el, opts, type)
        {

            $el.find("a[href='#']").click(function (e){
                e.preventDefault();
            });

            this.position = type;
            $el.find("li").bind(opts.eventshow, MCMSmenu.showNode);
            $el.find("li").bind(opts.eventhide, MCMSmenu.hideNode);
			
			$el.find("li:last-child").addClass("last");
			
			$el.find("li").hover(function (){
				
				$(this).addClass("hover");				
				$(this).find('a').addClass("hover");				
			},
			function (){
				$(this).removeClass("hover");				
				$(this).find('a').removeClass("hover");				
			});
			//$el.find("li:last").addClass("last");
        }
    }






})(jQuery);
