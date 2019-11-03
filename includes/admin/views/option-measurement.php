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
    $measurement = $value;

    $placeholder = isset( $option['placeholder'] ) ? 'placeholder="' . $option['placeholder'] . '"'	: '';
?>

  <div class="wpsight-settings-field wpsight-settings-field-text">
    <input id="setting-<?php echo $option_css; ?>_label" class="regular-text" type="text" name="<?php echo $option_id . '[label]'; ?>" value="<?php echo esc_attr( $measurement['label'] ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php echo $placeholder; ?> />
  </div>

  <div class="wpsight-settings-field wpsight-settings-field-radio">

    <?php

    foreach ( wpsight_measurements() as $key => $unit ) {
      $id = $option_css .'-'. $key;

      ?>

      <input id="setting-<?php echo $id; ?>" name="<?php echo esc_attr( $option_id ); ?>[unit]" type="radio" value="<?php echo esc_attr( $key ); ?>" <?php echo implode( ' ', $attributes ); ?> <?php checked( $measurement['unit'], $key ); ?> />
      <label for="setting-<?php echo $id; ?>" class="label-radio"><?php if( empty( $unit ) ) { echo 'None'; } else { echo $unit; } ?></label>

    <?php } ?>

  </div>

<?php } ?>
