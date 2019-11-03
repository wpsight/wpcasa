<?php
  if (isset($option)) {
  $option_type = isset( $option['type'] ) ? $option['type'] : '';
  $option_id = isset( $option['id'] ) ? $this->settings_name . '[' . $option['id'] . ']' : '';
  $option_css = sanitize_html_class( $this->settings_name . '_' . $option['id'] );
  $option_options = isset( $option['options'] ) ? $option['options'] : '';

  $value = wpsight_get_option( $option['id'] );
  if( !isset( $value ) && isset( $option['default'] ) ) $value = $option['default'];
?>

<div class="wpsight-settings-field wpsight-settings-field-<?php echo $option_type; ?>">

  <?php

  foreach ( $option_options as $key => $name ) {
    $v = isset( $value[$key] ) ? $value[$key] : ''; ?>

    <div class="multicheck">
      <input id="setting-<?php echo $option_css; ?>_<?php echo $key; ?>" name="<?php echo $option_id . '[' . $key . ']' ; ?>" type="checkbox" value="1" <?php //echo implode( ' ', $attributes ); ?> <?php checked( '1', $v ); ?> />
      <label for="setting-<?php echo $option_css; ?>_<?php echo $key; ?>" class="label-checkbox"><?php echo $name; ?></label>
    </div>

  <?php } ?>


</div>

<?php } ?>
