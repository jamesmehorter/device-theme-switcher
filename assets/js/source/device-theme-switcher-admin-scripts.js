// On load..
(function($){

    // Display & Hide the Hidden Settings
    $('.optional-settings-toggle').click(function(){

        // Cache an instance of the clicked element
        oThis = $(this);

        // Slide the option settings panel open/close
        $('.optional-settings').slideToggle(250, function(){
            if ($(this).is(':visible')) {
                oThis.text('Hide Optional Settings');
            } else {
                oThis.text('Show Optional Settings');
            }
        });
    });

    // Display & Hide the Hidden Help & Support
    $('.help-and-support-toggle').click(function(){
        $('.help-and-support').slideToggle(250, function(){
            // complete
        });
    });

})(jQuery);