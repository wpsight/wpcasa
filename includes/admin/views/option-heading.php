<?php

foreach ( $this->settings as $key => $section ) {
    foreach ( $section[1] as $option ) {
        if( $option['type'] == 'heading' ) { ?>
            <th scope="row" colspan="2">
               <h4> <?php echo $option['name'] ?></h4>
               <i><?php echo $option['desc'] ?></i>
          </th>
<?php
         return;
        }
    }
}

?>

