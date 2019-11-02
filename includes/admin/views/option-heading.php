<?php
if (isset($option)) {
    $option_name = isset($option['name']) ? stripslashes($option['name']) : '';
    $option_desc = isset($option['desc']) ? stripslashes($option['desc']) : '';
?>

    <th scope="row" colspan="2">
        <h4> <?php echo $option_name ?></h4>
        <i><?php echo $option_desc ?></i>
    </th>

<?php } ?>

