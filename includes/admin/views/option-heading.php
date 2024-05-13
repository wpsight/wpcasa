<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if (isset($option)) {
    $option_name = isset($option['name']) ? stripslashes($option['name']) : '';
    $option_desc = isset($option['desc']) ? stripslashes($option['desc']) : '';
?>

    <th scope="row" colspan="2">
        <h4> <?php echo esc_html( $option_name ) ?></h4>
        <i><?php echo esc_html( $option_desc ) ?></i>
    </th>

<?php } ?>

