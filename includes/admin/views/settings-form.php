<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php   $settings = isset( $settings ) ? $settings : '';
        $settings_group = isset( $settings_group ) ? $settings_group : '';
?>

<form method="post" action="options.php">

    <?php settings_fields( $settings_group ); ?>

    <?php

    foreach ( $settings as $key => $section ) {

        echo '<div id="settings-' . esc_attr( sanitize_title( $key ) ) . '" class="settings_panel">'; ?>

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

                                $compare_class = '';

                                if ( isset( $option['show_if'] ) ) {
                                    $dependable_option_id = isset( $option['show_if']['id'] ) ? $option['show_if']['id'] : null;
                                    $comparable_value = isset( $option['show_if']['value'] ) ? $option['show_if']['value'] : null;
                                    $comparision_operator = isset( $option['show_if']['compare'] ) ? $option['show_if']['compare'] : '==';

                                    if ( $dependable_option_id ) {
                                        $dependable_option_value = wpsight_get_option( $dependable_option_id );
                                        if ( ! empty( $comparision_operator ) ) {
                                            switch ( $comparision_operator ) {
                                                case '==' :
                                                    $compare_result = $comparable_value == $dependable_option_value;
                                                    $compare_class = $compare_result ? '' : 'hidden';
                                                    ?>
                                                    <script>
                                                    jQuery(document).ready(function($) {

                                                        var dependableOptionId = '#setting-wpcasa_<?php echo $dependable_option_id; ?>';
                                                        var optionId = '#setting-wpcasa_<?php echo $option['id']; ?>';

                                                        $(document).on('click', dependableOptionId, function(e) {
                                                            if ( $(dependableOptionId).is(':checked') ) {
                                                                $(optionId).closest('tr').removeClass('hidden');
                                                            } else {
                                                                $(optionId).closest('tr').addClass('hidden');
                                                            }
                                                        });

                                                    });
                                                    </script>
                                                    <?php
                                                    break;
                                            }
                                        }
                                    }

                                }
                                ?>

                                <tr valign="top" class="setting-<?php echo esc_attr( $option_css ) ?>-tr<?php echo esc_attr( $class ) ?> <?php echo esc_attr( $compare_class ); ?>">
                                    <?php

                                    if( ( $option_type == 'pageheading' ) || ( $option_type == 'heading' ) ) {
                                        require  plugin_dir_path( __FILE__ ) . 'option-' . $option_type . '.php';

                                    } else { ?>

                                        <th scope="row">
                                            <label for="setting-<?php echo esc_attr( $option_css ) ?>"><?php echo esc_html( $option_name ) ?></label>
                                            <p class="description"><?php echo wp_kses( $option_desc, wp_kses_allowed_html( 'post' ) ) ?></p>
                                        </th>
                                        <td>
                                            <div class="wpsight-settings-field-wrap wpsight-settings-field-<?php echo esc_attr( $option_type ) ?>-wrap">

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