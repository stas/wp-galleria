jQuery("ul#wp-galleria li.galleria-photo a").fancybox({
       'hideOnContentClick': true,
       'zoomSpeedIn': 300,
       'zoomSpeedOut': 300,
       'overlayShow': false,
       'padding': 0,
       'titleShow': false
});

function getGroupItems(opts) {
       jQuery.each("ul#wp-galleria li.galleria-photo a", function(i) {
       opts.itemArray.push.attr("rel");
    });
}
