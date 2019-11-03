<?php
  if (isset($option)) {
    $option_type = isset( $option['type'] ) ? $option['type'] : '';
    $option_id = isset( $option['id'] ) ? $this->settings_name . '[' . $option['id'] . ']' : '';
    $option_css = sanitize_html_class( $this->settings_name . '_' . $option['id'] );

    $min = isset( $option['min'] ) ? 'min="' . $option['min'] . '"' : null;
    $max = isset( $option['max'] ) ? 'max="' . $option['max'] . '"' : null;
    $step = isset( $option['step'] ) ? 'step="' . $option['step'] . '"' : null;

    $attributes = array();
    if ( isset( $option['attributes'] ) && is_array( $option['attributes'] ) ) {
      foreach ( $option['attributes'] as $attribute_name => $attribute_value ) {
        $attributes[] = esc_attr( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';
      }
    }

    $value = wpsight_get_option( $option['id'] );
    if( !isset( $value ) && isset( $option['default'] ) ) $value = $option['default'];

    $placeholder = isset( $option['placeholder'] ) ? 'placeholder="' . $option['placeholder'] . '"'	: '';
?>

  <div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">
    <input id="setting-<?php echo $option_css; ?>" class="range-slider__range" type="<?php echo $option_type; ?>" name="<?php echo $option_id; ?>" value="<?php esc_attr_e( $value ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> <?php echo $min; ?> <?php echo $max; ?> <?php echo $step; ?> />
    <span class="range-slider__value">0</span>
  </div>

<?php } ?>

