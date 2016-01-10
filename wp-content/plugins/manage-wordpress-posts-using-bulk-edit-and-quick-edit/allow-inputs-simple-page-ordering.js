(function ($) {

    var sort_container = $(".wp-list-table tbody");
    sort_container.sortable({
        stop: function () {
            // enable text select on inputs
            sort_container.find("input")
                .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
                    e.stopImmediatePropagation();
                });
            sort_container.find("select")
                .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
                    e.stopImmediatePropagation();
                });
            sort_container.find(".save-options")
                .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
                    e.stopImmediatePropagation();
                });
        }
    }).disableSelection();

// enable text select on inputs
    sort_container.find("input")
        .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
            e.stopImmediatePropagation();
        });
    sort_container.find("input")
        .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
            e.stopImmediatePropagation();
        });
    sort_container.find("select")
        .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
            e.stopImmediatePropagation();
        });
    sort_container.find(".save-options")
        .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
            e.stopImmediatePropagation();
        });

})(jQuery);