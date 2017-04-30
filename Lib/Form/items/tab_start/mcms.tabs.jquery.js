(function($)
{

    $.mcms_tabs = function(el, options) {


        var base            = this;
        base.$this          = $(el);
        base.tab_page       = options.tab_page;
        base.$tab_pages     = null;
        base.tab_size       = 0;
        base.show_tab_index = options.show_tab_index;
        base.$nav_items     = null;
        base.$nav           = null;
        base.nav            = "";
        base.tab_page_name  = options.tab_page_name;

        base.hide_all_tab_page = function ()
        {
            base.$nav_items.removeClass('current');
            base.$tab_pages.hide();
        };

        base.show_all_tab_page = function ()
        {
            base.$tab_pages.show();
        };

        base.show_tab_page_by_index = function (index)
        {
            base.$this.find(this.tab_page).eq(index).show();
            base.$nav_items.eq(index).addClass('current');
        };

        base.init = function ()
        {

            base.$tab_pages = base.$this.find(base.tab_page);
            base.tab_size   = base.$tab_pages.size();
            base.nav        = base.nav_gen();

            base.$this.find(base.tab_page).eq(0).before(base.nav);

            base.$nav_items  = base.$this.find(".nav li");
            base.$nav = base.$this.find(".nav");

            base.hide_all_tab_page();

            base.show_tab_page_by_index(this.show_tab_index);

            base.$nav.delegate("li", "click", base.tab_click);

            //base.$nav_items.click(base.tab_click);

        };

         base.nav_gen=  function ()
        {

            html = '<ul class="nav">';

            this.$tab_pages.each(function()
            {

                $(this).addClass('tabPage');
                $tab_page_name = $(this).find(base.tab_page_name);

                text = $tab_page_name.text();

                $tab_page_name.hide();
                html +="<li>" + text + "</li>\n";
            });

            html += '</ul>';

            return html;
        };

         base.tab_click  =  function tab_click(e)
         {

            var tabIndex = $(e.target).index();

            base.hide_all_tab_page();
            base.show_tab_page_by_index(tabIndex);
         };

        base.init();
    };

    $.fn.mcms_tabs = function(option)
    {

        var setting = $.extend
        (
            {
                'tab_page':       'fieldset',
                'tab_page_name':  'legend',
                'debag':          true,
                'show_tab_index': 0
            },
            option||{}
        )

        return this.each(function()
        {
           (new $.mcms_tabs(this, setting));
        });








    }

})(jQuery);