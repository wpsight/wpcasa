<?php
  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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

  <div class="wpsight-settings-field wpsight-settings-field-<?php echo esc_attr( $option_type ); ?>">
    <input id="setting-<?php echo esc_attr( $option_css ); ?>" class="range-slider__range" type="<?php echo esc_attr( $option_type ); ?>" name="<?php echo esc_attr( $option_id ); ?>" value="<?php echo esc_attr( $value ); ?>" <?php echo esc_html( implode( ' ', $attributes ) ); ?> <?php echo esc_attr( $placeholder ); ?> <?php echo esc_attr( $min ); ?> <?php echo esc_attr( $max ); ?> <?php echo esc_attr( $step ); ?> />
    <span class="range-slider__value">0</span>
  </div>

<?php } ?>

