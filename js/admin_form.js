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

    var margin_vertical = jQuery('#wpupg_isotope_filter_style_margin_vertical');
    slider_args.start = margin_vertical.val();
    jQuery('#wpupg_isotope_filter_style_margin_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(margin_vertical);

    var margin_horizontal = jQuery('#wpupg_isotope_filter_style_margin_horizontal');
    slider_args.start = margin_horizontal.val();
    jQuery('#wpupg_isotope_filter_style_margin_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(margin_horizontal);

    var padding_vertical = jQuery('#wpupg_isotope_filter_style_padding_vertical');
    slider_args.start = padding_vertical.val();
    jQuery('#wpupg_isotope_filter_style_padding_vertical_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(padding_vertical);

    var padding_horizontal = jQuery('#wpupg_isotope_filter_style_padding_horizontal');
    slider_args.start = padding_horizontal.val();
    jQuery('#wpupg_isotope_filter_style_padding_horizontal_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(padding_horizontal);

    var border_width = jQuery('#wpupg_isotope_filter_style_border_width');
    slider_args.start = border_width.val();
    jQuery('#wpupg_isotope_filter_style_border_width_slider').noUiSlider(slider_args).on({
        slide: function() {
            WPUltimatePostGrid.changedIsotopeFilterStyle();
        }
    }).Link('lower').to(border_width);

    // Color Pickers
    jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker({
            change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
        });
    jQuery('#wpupg_isotope_filter_style_background_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_text_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });
    jQuery('#wpupg_isotope_filter_style_border_hover_color').wpColorPicker({
        change: function () { WPUltimatePostGrid.changedIsotopeFilterStyle(); }
    });

    // Initial State
    WPUltimatePostGrid.changedPostType();
    WPUltimatePostGrid.changedFilterType();
    WPUltimatePostGrid.changedIsotopeFilterStyle();

    // Events
    jQuery('#wpupg_post_types').on('change', function() { WPUltimatePostGrid.changedPostType(); });
    jQuery('#wpupg_filter_type').on('change', function() { WPUltimatePostGrid.changedFilterType(); });

    margin_vertical.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    margin_horizontal.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    padding_vertical.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    padding_horizontal.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });
    border_width.on('change', function() { WPUltimatePostGrid.changedIsotopeFilterStyle(); });

    jQuery('.wpupg-filter-isotope-term').hover(function() {
        var background_hover_color = jQuery('#wpupg_isotope_filter_style_background_hover_color').wpColorPicker('color');
        var text_hover_color = jQuery('#wpupg_isotope_filter_style_text_hover_color').wpColorPicker('color');
        var border_hover_color = jQuery('#wpupg_isotope_filter_style_border_hover_color').wpColorPicker('color');

        jQuery(this)
            .css( 'background-color', background_hover_color )
            .css( 'color', text_hover_color )
            .css( 'border-color', border_hover_color )
        ;
    }, function() {
        var background_color = jQuery('#wpupg_isotope_filter_style_background_color').wpColorPicker('color');
        var text_color = jQuery('#wpupg_isotope_filter_style_text_color').wpColorPicker('color');
        var border_color = jQuery('#wpupg_isotope_filter_style_border_color').wpColorPicker('color');

        jQuery(this)
            .css( 'background-color', background_color )
            .css( 'color', text_color)
            .css( 'border-color', border_color )
        ;
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

    jQuery('.wpupg-filter-isotope-term')
        .css( 'margin', margin_vertical + 'px ' + margin_horizontal + 'px' )
        .css( 'padding', padding_vertical + 'px ' + padding_horizontal + 'px' )
        .css( 'background-color', background_color )
        .css( 'color', text_color )
        .css( 'border', border_width + 'px solid ' + border_color )
    ;


};

jQuery(document).ready(function($) {
    WPUltimatePostGrid.initForm();
});