<div class="wrap">
    <h1><?php _e('Simple PWA Options', '_simple_pwa') ?></h1>

    <form action="<?php echo admin_url('admin-ajax.php') ?>" method="post" id="pwa_options_form">
        <input type="hidden" name="action" value="save_simple_pwa_options"/>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="pwa_name"><?php _e('App Name') ?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $pwa_options['name'] ?>" id="pwa_name" name="pwa_name">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="pwa_short_name"><?php _e('App Short Name') ?></label>
                    </th>
                    <td>
                        <input type="text" value="<?php echo $pwa_options['short_name'] ?>" id="pwa_short_name" name="pwa_short_name">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="pwa_display"><?php _e('App Display') ?></label>
                    </th>
                    <td>
                        <select id="pwa_display" name="pwa_display">
                            <?php
                            foreach ($availableDisplayOptions as $displayOption):?>
                                <option value="<?php echo $displayOption?>" <?php if($displayOption==$pwa_options['display']):?>selected<?php endif;?>><?php echo $displayOption?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="pwa_orientation"><?php _e('App Orientation') ?></label>
                    </th>
                    <td>
                        <select id="pwa_orientation" name="pwa_orientation">
                            <?php foreach ($availableOrientationOptions as $orientationOption):?>
                                <option value="<?php echo $orientationOption?>" <?php if($orientationOption==$pwa_options['orientation']):?>selected<?php endif;?>><?php echo $orientationOption?></option>
                            <?php endforeach;?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="pwa_background_color"><?php _e('App Background Color') ?></label>
                    </th>
                    <td>
                        <input class="pwa_color" type="text" value="<?php echo $pwa_options['background_color'] ?>" id="pwa_background_color" name="pwa_background_color">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="pwa_theme_color"><?php _e('App Theme Color') ?></label>
                    </th>
                    <td>
                        <input class="pwa_color" type="text" value="<?php echo $pwa_options['theme_color'] ?>" id="pwa_theme_color" name="pwa_theme_color">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="pwa_theme_color"><?php _e('App Icon') ?></label>
                    </th>
                    <td>
                        <?php
                        $icon = wp_get_attachment_url($pwa_options['icons']);?>
                        <div class='image-preview-wrapper'>
                            <img id='image-preview' width='150' height='150' style='max-height: 150px; width: 150px;' src="<?php echo $icon ?>" />
                        </div>
                        <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload icon' ); ?>" />
                        <input type='hidden' name='pwa_icons' id='pwa_icons' value='<?php echo $pwa_options['icons']?>'>
                        <br>
                        <span class="description"><?php _e('Icon for application.','simple_pwa')?> <?php _e('Must be a PNG image exactly ','simple_pwa')?> <b><?php _e('150x150 in size')?></b></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <button class="button button-primary" type="submit">
            <?php _e('Update', '_simple_pwa') ?>
        </button>
    </form>
</div>
<?php
wp_enqueue_media();
$my_saved_attachment_post_id = get_option( 'simple_pwa_icons')?:0;

?><script type='text/javascript'>

    jQuery( document ).ready( function( $ ) {

        // Uploading files
        var file_frame;
        var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
        var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
        jQuery('#upload_image_button').on('click', function( event ){

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( file_frame ) {
                // Set the post ID to what we want
                file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                // Open frame
                file_frame.open();
                return;
            } else {
                // Set the wp.media post id so the uploader grabs the ID we want when initialised
                wp.media.model.settings.post.id = set_to_post_id;
            }

            // Create the media frame.
            file_frame = wp.media.frames.file_frame = wp.media({
                title: 'Select a image to upload',
                button: {
                    text: 'Use this image',
                },
                multiple: false	// Set to true to allow multiple files to be selected
            });

            // When an image is selected, run a callback.
            file_frame.on( 'select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();

                // Do something with attachment.id and/or attachment.url here
                $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                $( '#pwa_icons' ).val( attachment.id );

                // Restore the main post ID
                wp.media.model.settings.post.id = wp_media_post_id;
            });

            // Finally, open the modal
            file_frame.open();
        });

        // Restore the main ID when the add media button is pressed
        jQuery( 'a.add_media' ).on( 'click', function() {
            wp.media.model.settings.post.id = wp_media_post_id;
        });
    });

</script>