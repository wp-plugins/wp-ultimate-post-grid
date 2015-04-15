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
        jQuery(this).siblings('.wpupg-filter-isotope-term').removeClass('active');

        var filter = jQuery(this).addClass('active').data('filter');
        container.isotope({ filter: filter });
    });
};

jQuery(document).ready(function($) {
    $('.wpupg-grid').each(function() {
        WPUltimatePostGrid.initGrid($(this));
    });
});