jQuery(function() {
    //@see https://stackoverflow.com/a/2880929
    var qsParams;
    (window.onpopstate = function () {
        var match,
            pl     = /\+/g,  // Regex for replacing addition symbol with a space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function (s) { return decodeURIComponent(s.replace(pl, " ")); },
            query  = window.location.search.substring(1);

        qsParams = {};
        while (match = search.exec(query))
           qsParams[decode(match[1])] = decode(match[2]);
    })();
    if (qsParams['page'] === 'metaredirect_settings') {
        var metaredirect_trigger = jQuery('#metaredirect_trigger_2');
        var metaredirect_checked = function() {
            if (metaredirect_trigger.prop('checked')) {
                jQuery('#metaredirect_trigger_argument').prop('disabled', false);
            } else {
                jQuery('#metaredirect_trigger_argument').val('').prop('disabled', true);
            };
        };
        var metaredirect_validate = function(){
            if (!jQuery('#metaredirect_enabled_1').prop('checked')) {
                if (confirm("The redirections are disabled. Are you sure you want to do this?") === true) {
                    return true;
                } else {
                    return false;
                }
            }
            if (jQuery('#metaredirect_customfield').val() === '') {
                alert('The custom field source is required.');
                jQuery('#metaredirect_customfield').focus();
                jQuery('#metaredirect_customfield').prop('autofocus');
                return false;
            }
            if (jQuery('#metaredirect_trigger_2').prop('checked') && jQuery('#metaredirect_trigger_argument').val() === '') {
                alert('A provisional trigger requires a triggering parameter.');
                jQuery('#metaredirect_trigger_argument').focus();
                jQuery('#metaredirect_trigger_argument').prop('autofocus');
                return false;            
            }
            return true;
        };
        metaredirect_checked();
        jQuery('input[name="metaredirect_trigger[]"]').change(function() {
            metaredirect_checked();
        });
        jQuery('form').submit(function(e){
            if( !metaredirect_validate() ){
              e.preventDefault(); 
              return; 
            }
        });
    }
});