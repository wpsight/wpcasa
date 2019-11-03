<?php
  $page_args = array(
    'sort_order' => 'asc',
    'sort_column' => 'post_title',
    'hierarchical' => 0
  );

  $get_pages = get_pages( $page_args );

  $pages = array();

  foreach ( $get_pages as $key => $page ) {
    $pages[$page->ID] = array();
    $pages[$page->ID]['name'] = $page->post_title;
    $pages[$page->ID]['date'] = $page->post_name;
  }

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
  <select id="setting-<?php echo $option_css; ?>" class="regular-text" name="<?php echo $option_id; ?>" <?php echo implode( ' ', $attributes ); ?>>
    <option value=""><?php _ex( 'Select page', 'plugin settings', 'wpcasa' ); ?>&hellip;</option><?php
    foreach( $pages as $key => $page )
      echo '<option value="' . esc_attr( $key ) . '" ' . selected( $value, $key, false ) . '>' . esc_html( $page['name'] ) . ' <small><i>(' . esc_html( $page['date'] ) . ')<small><i></option>'; ?>
  </select>
</div>

<?php } ?>
