<?php
  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

  if (isset($option)) {
    $option_type = isset( $option['type'] ) ? $option['type'] : '';
    $option_id = isset( $option['id'] ) ? $this->settings_name . '[' . $option['id'] . ']' : '';
    $option_css = sanitize_html_class( $this->settings_name . '_' . $option['id'] );
    $option_name = isset($option['name']) ? stripslashes($option['name']) : '';

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

    <?php
      $name = $option_name .'['. $option_id .']';

      foreach ( $option['options'] as $key => $option ) {
        $id = $option_css .'-'. $key;
      ?>

      <input id="setting-<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $option_id ); ?>" type="<?php echo esc_attr( $option_type ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php echo esc_html( implode( ' ', $attributes ) ); ?> <?php checked( $value, $key ); ?> />
      <label for="setting-<?php echo esc_attr( $id ); ?>" class="label-<?php echo esc_attr( $option_type ); ?>"><?php echo esc_html( $option ); ?></label>

    <?php } ?>

  </div>

<?php } ?>

