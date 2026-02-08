(function ($) {
    'use strict';
    var $cinzal_apply = $('#cinzal_apply');
    cinzal_terms_select();
    cinzal_conditional_init();
    cinzal_conditional_select();
    cinzal_labels_select();

    $('.color-picker').wpColorPicker();

    $cinzal_apply.on('change', function() {
        var apply = $(this).val();
        var $terms = $('#cinzal_terms');

        $('#cinzal_configuration_combination').hide();
        $('#cinzal_configuration_terms').hide();

        if (apply === '' || apply === 'none' || apply === 'all' || apply ===
            'sale' || apply === 'featured' || apply === 'bestselling' || apply ===
            'instock' || apply === 'outofstock' || apply === 'backorder') {
            return;
        }

        if (apply === 'combination') {
            $('#cinzal_configuration_combination').show();
            return;
        }

        $('#cinzal_configuration_terms').show();

        if ((typeof $terms.data(apply) === 'string' || $terms.data(apply) instanceof
            String) && $terms.data(apply) !== '') {
            $terms.val($terms.data(apply).split(',')).change();
        } else {
            $terms.val([]).change();
        }

        cinzal_terms_select();
    });

    function cinzal_terms_select() {
        var apply = $cinzal_apply.val();
        var label = $cinzal_apply.find(':selected').text().trim();

        $('#cinzal_configuration_terms_label').html(label);

        $('#cinzal_terms').selectWoo({
            ajax: {
                url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
                    return {
                        q: params.term,
                        action: 'cinzal_search_term',
                        nonce: cinzal_vars.nonce,
                        taxonomy: apply,
                    };
                }, processResults: function(data) {
                    var options = [];
                    if (data) {
                        $.each(data, function(index, text) {
                            options.push({id: text[0], text: text[1]});
                        });
                    }
                    return {
                        results: options,
                    };
                }, cache: true,
            }, minimumInputLength: 1,
        });
    }

    $(document).on('click touch', '.cinzal_add_conditional', function(e) {
        e.preventDefault();

        var $this = $(this);

        $this.addClass('disabled');

        var data = {
            action: 'cinzal_add_conditional', nonce: cinzal_vars.nonce,
        };

        $.post(ajaxurl, data, function(response) {
            $('.cinzal_conditionals').append(response);
            cinzal_conditional_init();
            $this.removeClass('disabled');
        });
    });

    function cinzal_conditional_init() {
        $('.cinzal_conditional_apply').each(function() {
            var $this = $(this);
            var $value = $this.closest('.cinzal_conditional').
            find('.cinzal_conditional_value');
            var $select_wrap = $this.closest('.cinzal_conditional').
            find('.cinzal_conditional_select_wrap');
            var $select = $this.closest('.cinzal_conditional').
            find('.cinzal_conditional_select');
            var $compare = $this.closest('.cinzal_conditional').
            find('.cinzal_conditional_compare');
            var apply = $this.val();
            var compare = $compare.val();

            if (apply === 'sale' || apply === 'featured' || apply === 'bestselling' ||
                apply === 'instock' || apply === 'outofstock' || apply ===
                'backorder') {
                $compare.hide();
                $value.hide();
                $select_wrap.hide();
            } else {
                $compare.show();

                if (apply === 'price' || apply === 'rating' || apply === 'release') {
                    $select_wrap.hide();
                    $value.show();
                    $compare.find('.cinzal_conditional_compare_price option').
                    prop('disabled', false);
                    $compare.find('.cinzal_conditional_compare_terms option').
                    prop('disabled', true);

                    if (compare === 'is' || compare === 'is_not') {
                        $compare.val('equal').trigger('change');
                    }
                } else {
                    $select_wrap.show();
                    $value.hide();
                    $compare.find('.cinzal_conditional_compare_price option').
                    prop('disabled', true);
                    $compare.find('.cinzal_conditional_compare_terms option').
                    prop('disabled', false);

                    if (compare !== 'is' && compare !== 'is_not') {
                        $compare.val('is').trigger('change');
                    }
                }
            }

            if ($value.data(apply) !== '') {
                $value.val($value.data(apply));
            }

            if ((typeof $select.data(apply) === 'string' ||
                $select.data(apply) instanceof String) && $select.data(apply) !==
                '') {
                $select.val($select.data(apply).split(',')).change();
            } else {
                $select.val([]).change();
            }
        });
    }

    function cinzal_conditional_select() {
        $('.cinzal_conditional_select').each(function() {
            var $this = $(this);
            var apply = $this.closest('.cinzal_conditional').
            find('.cinzal_conditional_apply').
            val();

            $this.selectWoo({
                ajax: {
                    url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
                        return {
                            action: 'cinzal_search_term',
                            nonce: cinzal_vars.nonce,
                            q: params.term,
                            taxonomy: apply,
                        };
                    }, processResults: function(data) {
                        var options = [];
                        if (data) {
                            $.each(data, function(index, text) {
                                options.push({id: text[0], text: text[1]});
                            });
                        }
                        return {
                            results: options,
                        };
                    }, cache: true,
                }, minimumInputLength: 1,
            });
        });
    }

    function cinzal_labels_select() {
        $('#cinzal_labels').selectWoo({
            ajax: {
                url: ajaxurl, dataType: 'json', delay: 250, data: function(params) {
                    return {
                        action: 'cinzal_search_labels',
                        nonce: cinzal_vars.nonce,
                        q: params.term,
                    };
                }, processResults: function(data) {
                    var options = [];
                    if (data) {
                        $.each(data, function(index, text) {
                            options.push({id: text[0], text: text[1]});
                        });
                    }
                    return {
                        results: options,
                    };
                }, cache: true,
            }, minimumInputLength: 1, select: function(e) {
                var element = e.params.data.element;
                var $element = $(element);

                $(this).append($element);
                $(this).trigger('change');
            },
        });
    }

    $(document).on('change', '.cinzal_conditional_apply', function() {
        cinzal_conditional_init();
        cinzal_conditional_select();
    });

    $(document).on('click touch', '.cinzal_conditional_remove', function(e) {
        e.preventDefault();

        $(this).closest('.cinzal_conditional').remove();
    });

})(jQuery);