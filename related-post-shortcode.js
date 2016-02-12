

( function() {

    var ready = false,
        pluginUrl = getPluginUrl(),
        pluginTrans = getPluginTrans();

    tinymce.init({
        content_css : pluginUrl +"/related-post-shortcode.css"
    });

    tinymce.PluginManager.add( 'related_post_shortcode', function( editor, url ) {


        editor.addButton( 'related_post_shortcode_button', {
            text: pluginTrans.btnTitle,
            icon: 'mad-editor-icon',
            style: "background: url('"+pluginUrl+"/related-post-shortcode-editor-icon.png?v=2') 3px 50% no-repeat;",
            onclick: function() {
                    // Open Tiny MCE window
                    editor.windowManager.open( {
                      title: pluginTrans.popupTitle,
                      url: pluginUrl+'/rps-popin.html',
                      width: 600,
                      height: 400

                    },
                    {
          						editor: editor,
                      jquery: jQuery,
                      trans: pluginTrans,
          						ajaxurl: ajaxurl
          					} );


            }

        } );

    } );

} )();


function getPluginUrl() {
    var data = {'action': 'related_post_shortcode_getPluginUrl'};
    var q = jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        dataType: 'json',
        async: false
    });

    var values = q.responseText;

    return values;

}

function getPluginTrans() {
    var data = {'action': 'related_post_shortcode_getTransFields'};
    var q = jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: data,
        dataType: 'json',
        async: false
    });

    var values = q.responseJSON;

    return values;

}
