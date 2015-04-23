var WPUltimatePostGrid = WPUltimatePostGrid || {};

WPUltimatePostGrid.post_type = null;
WPUltimatePostGrid.filter_possible = false;

WPUltimatePostGrid.initForm = function() {
    // General selects
    jQuery('.wpupg-select2').select2({
        width: '100%'
    });

    // Specific selects
    //$('#wpupg_post_type').select2({
    //    maximumSelectionLength: 1
    //});

    // Sliders
    var slider_args = {
        start: 1,
        step: 1,
        range: {
            'min': 1,
            'max': 500
        },
        behaviour: 'tap-drag',
        format: wNumb({
            decimals: 0
        })
    };

    var posts_per_page = jQuery('#wpupg_pagination_pages_posts_per_page');
    slider_args.start = posts_per_page.val();
    jQuery('#wpupg_pagination_pages_posts_per_page_slider').noUiSlider(slider_args).Link('lower').to(posts_per_page);

    slider_args = {
        start: 0,
        step: 1,
        range: {
            'min': 0,
            'max': 50
        },
        behaviour: 'tap-drag',
        format: wNumb({
            decimals: 0
        })
    };

    var isotope_margin_vertical = jQuery('#wpupg_isotope_filter_style_margin_vertical');
    slider_args.start = isotope_margin_vertical.val();
    jQuery('#wpupg_isotope_filter_style_margin_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_margin_vertical);

    var isotope_margin_horizontal = jQuery('#wpupg_isotope_filter_style_margin_horizontal');
    slider_args.start = isotope_margin_horizontal.val();
    jQuery('#wpupg_isotope_filter_style_margin_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_margin_horizontal);

    var isotope_padding_vertical = jQuery('#wpupg_isotope_filter_style_padding_vertical');
    slider_args.start = isotope_padding_vertical.val();
    jQuery('#wpupg_isotope_filter_style_padding_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_padding_vertical);

    var isotope_padding_horizontal = jQuery('#wpupg_isotope_filter_style_padding_horizontal');
    slider_args.start = isotope_padding_horizontal.val();
    jQuery('#wpupg_isotope_filter_style_padding_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_padding_horizontal);

    var isotope_border_width = jQuery('#wpupg_isotope_filter_style_border_width');
    slider_args.start = isotope_border_width.val();
    jQuery('#wpupg_isotope_filter_style_border_width_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(isotope_border_width);

    
    var pagination_margin_vertical = jQuery('#wpupg_pagination_style_margin_vertical');
    slider_args.start = pagination_margin_vertical.val();
    jQuery('#wpupg_pagination_style_margin_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_margin_vertical);

    var pagination_margin_horizontal = jQuery('#wpupg_pagination_style_margin_horizontal');
    slider_args.start = pagination_margin_horizontal.val();
    jQuery('#wpupg_pagination_style_margin_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_margin_horizontal);

    var pagination_padding_vertical = jQuery('#wpupg_pagination_style_padding_vertical');
    slider_args.start = pagination_padding_vertical.val();
    jQuery('#wpupg_pagination_style_padding_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_padding_vertical);

    var pagination_padding_horizontal = jQuery('#wpupg_pagination_style_padding_horizontal');
    slider_args.start = pagination_padding_horizontal.val();
    jQuery('#wpupg_pagination_style_padding_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_padding_horizontal);

    var pagination_border_width = jQuery('#wpupg_pagination_style_border_width');
    slider_args.start = pagination_border_width.val();
    jQuery('#wpupg_pagination_style_border_width_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedPaginationStyle();
        }
    }).Link('lower').to(pagination_border_width);

    // Color Pickers
    jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker({
            change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
        });
    jQuery('#wpupg_isotope_filter_style_background_active_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_background_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_active_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_active_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });

    jQuery('#wpupg_pagination_style_background_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_background_active_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_background_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_text_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_text_active_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_text_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_border_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_border_active_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });
    jQuery('#wpupg_pagination_style_border_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedPaginationStyle(); }
    });

    // Initial State
    WPUltimatePostGrid.changedPostType();
    WPUltimatePostGrid.changedFilterType();
    WPUltimatePostGrid.changedIsotopeFilterStyle();
    WPUltimatePostGrid.changedPaginationStyle();
    WPUltimatePostGrid.changedPaginationType();

    // Events
    jQuery('#wpupg_post_types').on('change', function() { WPUltimatePostGrid.changedPostType(); });
    jQuery('#wpupg_filter_type').on('change', function() { WPUltimatePostGrid.changedFilterType(); });
    jQuery('#wpupg_pagination_type').on('change', function() { WPUltimatePostGrid.changedPaginationType(); });

    isotope_margin_vertical.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    isotope_margin_horizontal.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    isotope_padding_vertical.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    isotope_padding_horizontal.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    isotope_border_width.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    jQuery('#wpupg_isotope_filter_style_alignment').on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });

    pagination_margin_vertical.on('change', function() { WPUltimatePostGrid.changedPaginationStyle(); });
    pagination_margin_horizontal.on('change', function() { WPUltimatePostGrid.changedPaginationStyle(); });
    pagination_padding_vertical.on('change', function() { WPUltimatePostGrid.changedPaginationStyle(); });
    pagination_padding_horizontal.on('change', function() { WPUltimatePostGrid.changedPaginationStyle(); });
    pagination_border_width.on('change', function() { WPUltimatePostGrid.changedPaginationStyle(); });
    jQuery('#wpupg_pagination_style_alignment').on('change', function() { WPUltimatePostGrid.changedPaginationStyle(); });

    jQuery('.wpupg-filter-isotope-term').hover(function() {
        if(!jQuery(this).hasClass('active')) {
            var background_hover_color = jQuery('#wpupg_isotope_filter_style_background_hover_color').wpColorPicker('color');
            var text_hover_color = jQuery('#wpupg_isotope_filter_style_text_hover_color').wpColorPicker('color');
            var border_hover_color = jQuery('#wpupg_isotope_filter_style_border_hover_color').wpColorPicker('color');

            jQuery(this)
                .css('background-color', background_hover_color)
                .css('color', text_hover_color)
                .css('border-color', border_hover_color)
            ;
        }
    }, function() {
        if(!jQuery(this).hasClass('active')) {
            var background_color = jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker('color');
            var text_color = jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker('color');
            var border_color = jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker('color');

            jQuery(this)
                .css( 'background-color', background_color )
                .css( 'color', text_color)
                .css( 'border-color', border_color )
            ;
        }
    });

    jQuery('.wpupg-pagination-term').hover(function() {
        if(!jQuery(this).hasClass('active')) {
            var background_hover_color = jQuery('#wpupg_pagination_style_background_hover_color').wpColorPicker('color');
            var text_hover_color = jQuery('#wpupg_pagination_style_text_hover_color').wpColorPicker('color');
            var border_hover_color = jQuery('#wpupg_pagination_style_border_hover_color').wpColorPicker('color');

            jQuery(this)
                .css('background-color', background_hover_color)
                .css('color', text_hover_color)
                .css('border-color', border_hover_color)
            ;
        }
    }, function() {
        if(!jQuery(this).hasClass('active')) {
            var background_color = jQuery('#wpupg_pagination_style_background_color').wpColorPicker('color');
            var text_color = jQuery('#wpupg_pagination_style_text_color').wpColorPicker('color');
            var border_color = jQuery('#wpupg_pagination_style_border_color').wpColorPicker('color');

            jQuery(this)
                .css( 'background-color', background_color )
                .css( 'color', text_color)
                .css( 'border-color', border_color )
            ;
        }
    });
};

WPUltimatePostGrid.changedPostType = function() {
    var post_type = jQuery('#wpupg_post_types').find(':selected').val();

    jQuery('.wpupg_filter_taxonomy_container').hide();
    var container = jQuery('#wpupg_filter_taxonomy_' + post_type + '_container');

    if(container.length > 0) {
        jQuery('#wpupg_no_taxonomies').hide();
        jQuery('#wpupg_form_filter').show();
        container.show();
        WPUltimatePostGrid.filter_possible = true;
    } else {
        jQuery('#wpupg_no_taxonomies').show();
        jQuery('#wpupg_form_filter').hide();
        WPUltimatePostGrid.filter_possible = false;
    }

    WPUltimatePostGrid.changedFilterType();
};

WPUltimatePostGrid.changedFilterType = function() {
    var filter_type = jQuery('#wpupg_filter_type').find(':selected').val();

    // Hide & Show Filter Settings
    if(filter_type == 'none') {
        jQuery('#wpupg_form_filter').find('tr').not('.wpupg_no_filter').hide();
    } else {
        jQuery('#wpupg_form_filter').find('tr').not('.wpupg_no_filter').show();
    }

    // Hide & Show Filter Styles
    jQuery('#wpupg_meta_box_isotope_filter_style').hide();

    if(WPUltimatePostGrid.filter_possible) {
        jQuery('#wpupg_meta_box_' + filter_type + '_filter_style').show();
    }
};

WPUltimatePostGrid.changedIsotopeFilterStyle = function() {
    var margin_vertical = parseInt(jQuery('#wpupg_isotope_filter_style_margin_vertical').val());
    var margin_horizontal = parseInt(jQuery('#wpupg_isotope_filter_style_margin_horizontal').val());
    var padding_vertical = parseInt(jQuery('#wpupg_isotope_filter_style_padding_vertical').val());
    var padding_horizontal = parseInt(jQuery('#wpupg_isotope_filter_style_padding_horizontal').val());
    var border_width = parseInt(jQuery('#wpupg_isotope_filter_style_border_width').val());

    var background_color = jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker('color');
    var text_color = jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker('color');
    var border_color = jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker('color');

    var alignment = jQuery('#wpupg_isotope_filter_style_alignment').find(':selected').val();
    jQuery('#wpupg_filter_preview_isotope_filter_style').css('text-align', alignment);

    jQuery('.wpupg-filter-isotope-term')
        .css( 'margin', margin_vertical + 'px ' + margin_horizontal + 'px' )
        .css( 'padding', padding_vertical + 'px ' + padding_horizontal + 'px' )
        .css( 'background-color', background_color )
        .css( 'color', text_color )
        .css( 'border', border_width + 'px solid ' + border_color )
    ;

    var background_active_color = jQuery('#wpupg_isotope_filter_style_background_active_color').wpColorPicker('color');
    var text_active_color = jQuery('#wpupg_isotope_filter_style_text_active_color').wpColorPicker('color');
    var border_active_color = jQuery('#wpupg_isotope_filter_style_border_active_color').wpColorPicker('color');

    jQuery('.wpupg-filter-isotope-term.active')
        .css( 'background-color', background_active_color )
        .css( 'color', text_active_color )
        .css( 'border', border_width + 'px solid ' + border_active_color )
    ;
};

WPUltimatePostGrid.changedPaginationStyle = function() {
    var margin_vertical = parseInt(jQuery('#wpupg_pagination_style_margin_vertical').val());
    var margin_horizontal = parseInt(jQuery('#wpupg_pagination_style_margin_horizontal').val());
    var padding_vertical = parseInt(jQuery('#wpupg_pagination_style_padding_vertical').val());
    var padding_horizontal = parseInt(jQuery('#wpupg_pagination_style_padding_horizontal').val());
    var border_width = parseInt(jQuery('#wpupg_pagination_style_border_width').val());

    var background_color = jQuery('#wpupg_pagination_style_background_color').wpColorPicker('color');
    var text_color = jQuery('#wpupg_pagination_style_text_color').wpColorPicker('color');
    var border_color = jQuery('#wpupg_pagination_style_border_color').wpColorPicker('color');

    var alignment = jQuery('#wpupg_pagination_style_alignment').find(':selected').val();
    jQuery('#wpupg_filter_preview_pagination_style').css('text-align', alignment);

    jQuery('.wpupg-pagination-term')
        .css( 'margin', margin_vertical + 'px ' + margin_horizontal + 'px' )
        .css( 'padding', padding_vertical + 'px ' + padding_horizontal + 'px' )
        .css( 'background-color', background_color )
        .css( 'color', text_color )
        .css( 'border', border_width + 'px solid ' + border_color )
    ;

    var background_active_color = jQuery('#wpupg_pagination_style_background_active_color').wpColorPicker('color');
    var text_active_color = jQuery('#wpupg_pagination_style_text_active_color').wpColorPicker('color');
    var border_active_color = jQuery('#wpupg_pagination_style_border_active_color').wpColorPicker('color');

    jQuery('.wpupg-pagination-term.active')
        .css( 'background-color', background_active_color )
        .css( 'color', text_active_color )
        .css( 'border', border_width + 'px solid ' + border_active_color )
    ;
};

WPUltimatePostGrid.changedPaginationType = function() {
    var pagination_type = jQuery('#wpupg_pagination_type').find(':selected').val();

    // Hide & Show Pagination Settings
    jQuery('#wpupg_form_pagination').find('tbody').not('.wpupg_pagination_' + pagination_type).not('.wpupg_pagination_none').hide();
    jQuery('#wpupg_form_pagination').find('tbody.wpupg_pagination_' + pagination_type).show();

    // Hide & Show Pagination Styles
    if(pagination_type != 'none') {
        jQuery('#wpupg_meta_box_pagination_style').show();
    } else {
        jQuery('#wpupg_meta_box_pagination_style').hide();
    }
};

jQuery(document).ready(function($) {
    WPUltimatePostGrid.initForm();
});