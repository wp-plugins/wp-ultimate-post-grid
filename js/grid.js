var WPUltimatePostGrid = WPUltimatePostGrid || {};

WPUltimatePostGrid.grids = [];

WPUltimatePostGrid.initGrid = function(container) {
    var grid_id = container.attr('id');

    container.isotope({
        itemSelector: '.wpupg-item',
        transitionDuration: '0.8s',
        hiddenStyle: {
            opacity: 0
        },
        visibleStyle: {
            opacity: 1
        }
        //masonry: {
        //    isFitWidth: true
        //}
    });

    WPUltimatePostGrid.grids.push(container);

    // Isotope Filter
    jQuery('#' + grid_id + '-filter').find('.wpupg-filter-isotope-term').click(function() {
        var filter_item = jQuery(this);
        filter_item.siblings('.wpupg-filter-isotope-term').removeClass('active').trigger('checkActiveFilter');

        document.location.hash = filter_item.data('slug');

        var filter = filter_item.addClass('active').trigger('checkActiveFilter').data('filter');
        container.isotope({ filter: filter });
    });
};

WPUltimatePostGrid.initFilter = function(container) {
    var filter_type = container.data('filter-type');
    var filter_items = container.find('.wpupg-filter-item');

    if(filter_type == 'isotope') {
        var margin = container.data('margin-vertical') + 'px ' + container.data('margin-horizontal') + 'px';
        var padding = container.data('padding-vertical') + 'px ' + container.data('padding-horizontal') + 'px';
        var border = container.data('border-width') + 'px solid ' + container.data('border-color');
        var background_color = container.data('background-color');
        var text_color = container.data('text-color');

        var active_border = container.data('border-width') + 'px solid ' + container.data('active-border-color');
        var active_background_color = container.data('active-background-color');
        var active_text_color = container.data('active-text-color');

        var hover_border = container.data('border-width') + 'px solid ' + container.data('hover-border-color');
        var hover_background_color = container.data('hover-background-color');
        var hover_text_color = container.data('hover-text-color');

        filter_items.each(function() {
            var filter_item = jQuery(this);

            filter_item
                .css('margin', margin)
                .css('padding', padding)
                .css('border', border)
                .css('background-color', background_color)
                .css('color', text_color)
                .hover(function() {
                    if(!filter_item.hasClass('active')) {
                        filter_item
                            .css('border', hover_border)
                            .css('background-color', hover_background_color)
                            .css('color', hover_text_color);
                    }
                }, function() {
                    if(!filter_item.hasClass('active')) {
                        filter_item
                            .css('border', border)
                            .css('background-color', background_color)
                            .css('color', text_color);
                    }
                })
                .on('checkActiveFilter', function() {
                    if(filter_item.hasClass('active')) {
                        filter_item
                            .css('border', active_border)
                            .css('background-color', active_background_color)
                            .css('color', active_text_color);
                    } else {
                        filter_item
                            .css('border', border)
                            .css('background-color', background_color)
                            .css('color', text_color);
                    }
                }).trigger('checkActiveFilter');
        });
    }

    // Deep linking
    var active_filter = document.location.hash;
    if(active_filter) {
        active_filter = active_filter.substr(1);

        filter_items.each(function() {
            var filter_item = jQuery(this);

            if(filter_item.data('slug') == active_filter) {
                filter_item.trigger('click');
            }
        });
    }
};

jQuery(document).ready(function($) {
    $('.wpupg-grid').each(function() {
        WPUltimatePostGrid.initGrid($(this));
    });

    $('.wpupg-filter').each(function() {
        WPUltimatePostGrid.initFilter($(this));
    });
});