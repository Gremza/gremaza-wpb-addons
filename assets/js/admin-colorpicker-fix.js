jQuery(document).ready(function($){
    function initColorPickers(context) {
        // Find all text inputs that look like color fields
        $(context).find('input[type="text"]').each(function(){
            var $input = $(this);
            // If already initialized, skip
            if ($input.hasClass('wp-color-picker')) return;
            // If input has a color value or is inside a colorpicker param
            var isColor = $input.attr('name') && $input.attr('name').toLowerCase().indexOf('color') !== -1;
            if(isColor) {
                $input.wpColorPicker();
            }
        });
    }
    // Initial run
    initColorPickers(document);
    // When WPBakery modal opens
    $(document).on('vcPanel.shown', function(e){
        initColorPickers($('.vc_ui-panel-window:visible'));
    });
    // For dynamically added fields
    $(document).on('DOMNodeInserted', function(e){
        initColorPickers(e.target);
    });
});
