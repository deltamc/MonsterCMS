(function($)
{
    $.tlength = function(el, label) {

        var base            = this;
        base.$this          = $(el);
        base.label          = label;
        base.$label;

        base.init = function ()
        {

            base.$label = base.$this.parent().find(base.label);

            base.$this.attr('data-tl-label',base.label);
            base.display(base.$this, base.$label);


            //base.$this.parent().delegate( "textarea", "keyup", base.keyup);
            base.$this.keyup(base.keyup)


        }

        base.keyup = function (e)
        {
            //alert('fds');

            $this = $(this);
            lable = $this.attr('data-tl-label');

            $label = base.$this.parent().find(lable);
            base.display($this, $label);

        }

        base.display = function ($this, $label)
        {
            var max   =  $this.attr('maxlength');

            charss = $this.val().length;
            $label.text(charss + ' / ' + max);

        }

        base.init();
    }

    $.fn.tlength = function(label)
    {
        return this.each(function()
        {
            (new $.tlength(this, label));
        });
    }


})(jQuery);


