<script type="text/javascript">
    var pluginDirectory = '<?php echo plugins_url('', __FILE__); ?>';
</script>
<div class="wrap">
   <h2 class="align-center">Related Post Shortcode</h2>

   <form method="post" action="options.php">
   <?php settings_fields( 'related_post_shortcode-admin-settings-group' ); ?>
    <?php do_settings_sections( 'related_post_shortcode-admin-settings-group' ); ?>
    <h3><?php _e('Settings :', 'related-post-shortcode') ?></h3>

    <hr>

   <table class="form-table">

         <tr valign="top">
        <th scope="row"><?php _e('Display an excerpt:', 'related-post-shortcode') ?></th>
        <td><input type="checkbox" name="related_post_shortcode_display_excerpt" value="1" <?php if( get_option('related_post_shortcode_display_excerpt')==1 ){ echo 'checked';} ?> /></td>
        </tr>
         <tr valign="top">
        <th scope="row"><?php _e('Use a custom title of section:', 'related-post-shortcode') ?><p class="description"><?php _e('Default title is "You may also like"', 'related-post-shortcode') ?></p></th>
        <td><input type="text" style="min-width:350px;" name="related_post_shortcode_title_custom" value="<?php echo esc_attr( get_option('related_post_shortcode_title_custom') ); ?>" /></td>
        </tr>

    </table>
    <hr>

    <?php submit_button(); ?>

</form>
</div>
