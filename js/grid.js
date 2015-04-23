var WPUltimatePostGrid = WPUltimatePostGrid || {};

WPUltimatePostGrid.grids = {};

WPUltimatePostGrid.initGrid = function(container) {
    var grid_id = container.data('grid');

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

    WPUltimatePostGrid.grids[grid_id] = {
        container: container,
        pages: [0],
        page: 0,
        filter_tag: '*',
        filter_slug: ''
    };

    // TODO Refactor
    // Isotope Filter
    jQuery('#wpupg-grid-' + grid_id + '-filter').find('.wpupg-filter-isotope-term').click(function() {
        var filter_item = jQuery(this);
        filter_item.siblings('.wpupg-filter-isotope-term').removeClass('active').trigger('checkActiveFilter');

        filter_item.addClass('active').trigger('checkActiveFilter');

        WPUltimatePostGrid.grids[grid_id].filter_tag = filter_item.data('filter');;
        WPUltimatePostGrid.grids[grid_id].filter_slug = filter_item.data('slug');
        WPUltimatePostGrid.filterGrid(grid_id);
    });

    // pagination
    jQuery('#wpupg-grid-' + grid_id + '-pagination').find('.wpupg-pagination-term').click(function() {
        var pagination_item = jQuery(this);
        pagination_item.siblings('.wpupg-pagination-term').removeClass('active').trigger('checkActiveFilter');

        var page = parseInt(pagination_item.addClass('active').trigger('checkActiveFilter').data('page'));

        if(WPUltimatePostGrid.grids[grid_id].pages.indexOf(page) !== -1) {
            WPUltimatePostGrid.grids[grid_id].page = page;
            WPUltimatePostGrid.filterGrid(grid_id);
        } else {
            // Get new page via AJAX
            var data = {
                action: 'wpupg_get_page',
                security: wpupg_public.nonce,
                grid: grid_id,
                page: page
            };

            pagination_item.addClass('wpupg-spinner');
            pagination_item.css('color', WPUltimatePostGrid.grids[grid_id].pagination_style.active_background_color);

            // Get recipes through AJAX
            jQuery.post(wpupg_public.ajax_url, data, function(html) {
                var posts = jQuery(html).toArray();
                container.isotope('insert', posts);
                WPUltimatePostGrid.grids[grid_id].page = page;
                WPUltimatePostGrid.filterGrid(grid_id);

                pagination_item.removeClass('wpupg-spinner');
                pagination_item.css('color', WPUltimatePostGrid.grids[grid_id].pagination_style.active_text_color);

                WPUltimatePostGrid.grids[grid_id].pages.push(page);
            });
        }
    });
};

WPUltimatePostGrid.filterGrid = function(grid_id) {
    var grid = WPUltimatePostGrid.grids[grid_id];
    var page = grid.page || 0;
    var filter_tag = grid.filter_tag || '*';

    var filter = '.wpupg-page-' + page;

    if(filter_tag != '*') {
        filter += filter_tag;
    }

    grid.container.isotope({ filter: filter });
    WPUltimatePostGrid.updateDeeplink();
};

WPUltimatePostGrid.updateDeeplink = function() {
    var link = '';

    for(var grid_id in WPUltimatePostGrid.grids) {
        if(WPUltimatePostGrid.grids.hasOwnProperty(grid_id)) {
            var grid = WPUltimatePostGrid.grids[grid_id];

            if(grid.page != 0 || grid.filter_slug != '') {
                if(link != '') link += '#';
                link += grid_id;

                if(grid.page != 0) link += '|p:' + grid.page;
                if(grid.filter_slug != 0) link += '|tag:' + grid.filter_slug;
            }
        }
    }
    document.location.hash = link;
};

WPUltimatePostGrid.restoreDeeplink = function() {
    var link = document.location.hash;
    link = link.substr(1);

    var grids = link.split('#');

    for(var i=0; i < grids.length; i++) {
        var parts = grids[i].split('|');
        var grid_id = parts[0];

        for(var j=1; j < parts.length; j++) {
            var filter = parts[j].split(':');

            if(filter[0] == 'p') {
                jQuery('#wpupg-grid-' + grid_id + '-pagination').find('.wpupg-page-' + filter[1]).click();
            } else {
                jQuery('#wpupg-grid-' + grid_id + '-filter').find('.wpupg-filter-' + filter[0] + '-' + filter[1]).click();
            }
        }
    }
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
};

WPUltimatePostGrid.initPagination = function(container) {
    var grid_id = container.data('grid');
    var pagination_items = container.find('.wpupg-pagination-term');

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

    WPUltimatePostGrid.grids[grid_id].pagination_style = {
        margin: margin,
        padding: padding,
        border: border,
        background_color: background_color,
        text_color: text_color,
        active_border: active_border,
        active_background_color: active_background_color,
        active_text_color: active_text_color,
        hover_border: hover_border,
        hover_background_color: hover_background_color,
        hover_text_color: hover_text_color
    }

    pagination_items.each(function() {
        var pagination_item = jQuery(this);

        pagination_item
            .css('margin', margin)
            .css('padding', padding)
            .css('border', border)
            .css('background-color', background_color)
            .css('color', text_color)
            .hover(function() {
                if(!pagination_item.hasClass('active')) {
                    pagination_item
                        .css('border', hover_border)
                        .css('background-color', hover_background_color)
                        .css('color', hover_text_color);
                }
            }, function() {
                if(!pagination_item.hasClass('active')) {
                    pagination_item
                        .css('border', border)
                        .css('background-color', background_color)
                        .css('color', text_color);
                }
            })
            .on('checkActiveFilter', function() {
                if(pagination_item.hasClass('active')) {
                    pagination_item
                        .css('border', active_border)
                        .css('background-color', active_background_color)
                        .css('color', active_text_color);
                } else {
                    pagination_item
                        .css('border', border)
                        .css('background-color', background_color)
                        .css('color', text_color);
                }
            }).trigger('checkActiveFilter');
    });
};

jQuery(document).ready(function($) {
    $('.wpupg-grid').each(function() {
        WPUltimatePostGrid.initGrid($(this));
    });

    $('.wpupg-filter').each(function() {
        WPUltimatePostGrid.initFilter($(this));
    });

    $('.wpupg-pagination').each(function() {
        WPUltimatePostGrid.initPagination($(this));
    });
    WPUltimatePostGrid.restoreDeeplink();
});