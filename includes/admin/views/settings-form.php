<?php   $settings = isset( $settings ) ? $settings : '';
        $settings_group = isset( $settings_group ) ? $settings_group : '';
?>

<form method="post" action="options.php">

    <?php settings_fields( $settings_group ); ?>

    <?php

    foreach ( $settings as $key => $section ) {

        echo '<div id="settings-' . sanitize_title( $key ) . '" class="settings_panel">'; ?>

        <div class="wpsight-admin-ui-container">

            <div class="wpsight-admin-ui-grid">

                <div class="wpsight-admin-ui-grid-col wpsight-admin-ui-grid-1-1">
                    <div class="wpsight-admin-ui-panel wpsight-admin-ui-panel-large">

                        <table class="form-table">

                            <?php foreach ( $section[1] as $option ) {
                                $option_css			= sanitize_html_class( $this->settings_name . '_' . $option['id'] );

                                $option_name		= isset( $option['name'] )				? stripslashes ( $option['name'] )					: '';
                                $option_desc		= isset( $option['desc'] )				? stripslashes ( $option['desc'] )					: '';
                                $option_type		= isset( $option['type'] )				? $option['type']									: '';
                                $class				= isset( $option['class'] )				? ' ' . $option['class']							: '';

                                ?>

                                <tr valign="top" class="setting-<?php echo $option_css ?>-tr<?php echo $class ?>">
                                    <?php

                                    if( ( $option_type == 'pageheading' ) || ( $option_type == 'heading' ) ) {
                                        require  plugin_dir_path( __FILE__ ) . 'option-' . $option_type . '.php';

                                    } else { ?>

                                        <th scope="row">
                                            <label for="setting-' . $option_css . '"><?php echo $option_name ?></label>
                                            <p class="description"><?php echo $option_desc ?></p>
                                        </th>
                                        <td>
                                            <div class="wpsight-settings-field-wrap wpsight-settings-field-' . $option_type . '-wrap">

                                                <?php require  plugin_dir_path( __FILE__ ) . '/option-' . $option_type . '.php'; ?>

                                            </div>
                                        </td>
                                    <?php  } ?>
                                </tr>

                            <?php  } ?>

                        </table>

                    </div>
                </div>

            </div>

        </div>

        </div>

    <?php  } ?>

</form>