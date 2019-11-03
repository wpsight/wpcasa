<?php
  if (isset($option)) {
    $option_type = isset( $option['type'] ) ? $option['type'] : '';
    $option_id = isset( $option['id'] ) ? $this->settings_name . '[' . $option['id'] . ']' : '';
    $option_css = sanitize_html_class( $this->settings_name . '_' . $option['id'] );

    $attributes = array();
    if ( isset( $option['attributes'] ) && is_array( $option['attributes'] ) ) {
      foreach ( $option['attributes'] as $attribute_name => $attribute_value ) {
        $attributes[] = esc_attr( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';
      }
    }

    $value = wpsight_get_option( $option['id'] );
    if( !isset( $value ) && isset( $option['default'] ) ) $value = $option['default'];

?>

  <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
      <div class="switch">
          <input id="setting-<?php echo $option_css; ?>" name="<?php echo $option_id; ?>" type="<?php echo $option_type; ?>" value="1" <?php echo implode( ' ', $attributes ); ?> <?php checked( '1', $value ); ?> />
          <label for="setting-<?php echo $option_css; ?>" class="label-<?php echo $option_type; ?>"><?php //echo $option_cb_label; ?></label>
      </div>
  </div>

<?php } ?>

