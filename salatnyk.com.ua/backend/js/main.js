;(function($, undefined) {
    'use strict';

    // class def
    var MultiInput = function(el, options) {
        this.$el = $(el);
        this.options = options;
    };

    MultiInput.prototype = {
        constructor: MultiInput,

        defaults: {
            templateId: ''
        },

        init: function() {
            var self = this;
            this.cfg = $.extend({}, this.defaults, this.options);

            this.$el
                .on('click.multiInput', '.btnAddArrayRow', function (e) {
                	e.preventDefault();
					
					
					$(this).hide();
                    self.$el.append(createItem(window.tmpl($(this).closest('.control-group-multiple').data('block'), {idx: $(this).data('array-last') + 1})));
                    self.updateControls();

                    $(this).trigger('rowAdded.multiInput');
                })
                .on('click.multiInput', '.btnRemoveArrayRow', function (e) {
                    e.preventDefault();

                    $(this).closest('.controls-multiple-row').remove();
                    self.updateControls();

                    $(this).trigger('rowRemoved.multiInput');
                });

            return this;
        },

        updateControls: function() {
            var $rows = this.$el.find('.controls-multiple-row');
            var $lastRow = $rows.last();

            $lastRow.find('.btnAddArrayRow').show();

            //if($rows.length > 1) {
               // $rows.first().find('.btnRemoveArrayRow').show();
            //} else if($rows.length === 1) {
               // $lastRow.find('.btnRemoveArrayRow').hide();
            //}

            this.$el.find('label').attr('for', $lastRow.find('input, select, textarea').first()[0].id);
        }
    };

    MultiInput.defaults = MultiInput.prototype.defautls;


    function createItem(content) {
        var $item = $('<div class="controls controls-multiple-row" />');
        $item.html(content).find('input, select, textarea').filter(':first').focus();

        return $item;
    }

    // plugin def
    $.fn.multiInput = function(option) {
        return this.each(function() {
            var $this = $(this),
                data = $this.data('multiInput');
            if(!data) {
                $this.data('multiInput', (data = new MultiInput(this, option).init()));
            }
            if(typeof option === 'string') {
                data[option]();
            }

        });
    };

})(jQuery)


;(function ($, undefined) {
    'use strict';
	 if($('ul#form')) {
        if(location.hash !== '') {
            $('a[href = "' + location.hash + '"]').tab('show');
            /*fixThumbnailsHeight(location.hash);*/
        }

        $('#form').find('a[data-toggle="pill"]')
            .on('shown', function (e) {
                return location.hash = $(e.target).attr('href').substr(1);
            })
            .on('shown', function() {
                /*fixThumbnailsHeight(this.hash);*/
            });

        $('ul#form li.disabled a').on('click', function(e) {e.preventDefault();})
    }
    $('.control-group-multiple').multiInput({templateId:'sjstpl'});

})(jQuery);


/**
 * Check product sizes are unique
 *
 * @return {Boolean}
 */
function formAfterValidate(){
	return true;
}

function checkSizeUnique() {
    'use strict';

    var values = {};
    var isValid = true;

    $('.product-info').find('select.validate-unique').each(function (i, el) {
        var $el = $(el);
        var $elHelp = $('#' + el.id + '_em_');

        $el.removeClass('error');
        $elHelp.hide();

        if(values[el.value]) {
            isValid = false;

            $el.addClass('error');
            $elHelp.text('Этот ингредиент уже задан.').show();
            $('#' + values[el.value]).addClass('error');
        } else {
            values[el.value] = el.id;
        }
    });

    return isValid;
}