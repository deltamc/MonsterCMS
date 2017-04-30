

special_run = {
    init_var: function() {
		
        if (ls.special === void 0 || ls.special === "undefined") {
            ls.special = JSON.stringify({
                enabled: false,
                size: 's1',
                color: 'c1',
                hidden: true
            });
            window.special = JSON.parse(ls.special);
        } else {
            window.special = JSON.parse(ls.special);
        }
		
		
    },
    check_var: function() {
		
		
		
        if (special.enabled === void 0) {
            special.enabled = false;
			
			
			
        }
        if (special.size === void 0) {
            special.size = 's1';
        }
        if (special.color === void 0) {
            special.color = 'c1';
        }
        if (special.hidden === void 0) {
            special.hidden = true;
        }
        ls.special = JSON.stringify(special);
        this.init_var();
    },
    changing: function() {
        if (special.enabled === true) {

            /*
            $('a[name="close"]').hide();
            $('.special_panel').show();
            $('img').remove();
            $('.owl-carousel').remove();
            $('.banners').remove();

*/

            $('body').addClass(special.size);
            $('body').addClass(special.color);

            $('body').addClass('special');
            $('body').addClass(special.size);
            $('body').addClass(special.color);

           //$('.container').attr('class', 'container-fluide bg_content' + ' ' + special.size + ' ' + special.color);
            //$('body').attr('class', special.size + ' ' + special.color);
            $('#special_version_thumbler a').text('Обычная версия');
            //$('.navbar.special').addClass('transparent');
            //$('.navbar.special').find('li[class!="pull-right"]').show();


        }
    },
    make_hidden: function() {
		
        if (special.hidden === true) {
            $('.special_panel').hide();
            $("#special-enabled a").text("Версия для слабовидящих");

        } else if (special.hidden === false) {
            $('.special_panel').show();
            $("#special-enabled a").text("Обычная версия сайта");
        }
        return true;
    },
    hidden: function() {
        if (special.hidden === true) {
            special.hidden = false;
            ls.special = JSON.stringify(special);
            this.make_hidden();
        } else if (special.hidden === false) {
            special.hidden = true;
            ls.special = JSON.stringify(special);
            this.make_hidden();
        }
        return true;
    },
    setup_bind: function() {
        $('#special_version_thumbler a, #special').on('click', function(event) {
            event.preventDefault();
            if (special.enabled === true) {
                special.enabled = false;
                $("#special-enabled a").text("Версия для слабовидящих");
                //$(".special_panel").hide();
                special.hidden = true;
                special_run.make_hidden();
                //this.hidden();
                ls.special = JSON.stringify(special);
                location.reload();
            } else if (special.enabled === false) {
                special.enabled = true;
                //$(".special_panel").show();
                special.hidden = false;
                special_run.make_hidden();
                $("#special-enabled a").text("Обычная версия сайта");
                ls.special = JSON.stringify(special);
                special_run.changing();
            }

        });
        $('a[name="c1"]').on('click', function(event) {
            event.preventDefault();
            $('body').removeClass(special.color);
            special.color = 'c1';

            ls.special = JSON.stringify(special);
            special_run.changing();
        });
        $('a[name="c2"]').on('click', function(event) {
            event.preventDefault();
            $('body').removeClass(special.color);
            special.color = 'c2';
            ls.special = JSON.stringify(special);
            special_run.changing();
        });
        $('a[name="c3"]').on('click', function(event) {
            event.preventDefault();
            $('body').removeClass(special.color);
            special.color = 'c3';
            ls.special = JSON.stringify(special);
            special_run.changing();
        });
        $('a[name="s1"]').on('click', function(event) {
            event.preventDefault();
            $('body').removeClass(special.size);
            special.size = 's1';
            ls.special = JSON.stringify(special);
            special_run.changing();
        });
        $('a[name="s2"]').on('click', function(event) {
            event.preventDefault();
            $('body').removeClass(special.size);
            special.size = 's2';
            ls.special = JSON.stringify(special);
            special_run.changing();
        });
        $('a[name="s3"]').on('click', function(event) {
            event.preventDefault();
            $('body').removeClass(special.size);
            special.size = 's3';
            ls.special = JSON.stringify(special);
            special_run.changing();
        });
        /*
        return $('a[name="close"]').on('click', function(event) {
            event.preventDefault();
            special_run.hidden();
        });
        */
    },
    run_special: function() {
        //special.enabled = true;
		
        this.init_var();
        this.check_var();
        this.changing();
        this.setup_bind();
        this.make_hidden();
    }
};

jQuery(function (){
	special_run.run_special();
	
});
