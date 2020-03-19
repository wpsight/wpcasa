<?php
    $recommends = wpsight_get_recommends();
//    shuffle($recommends);
?>

<div class="wpsight-admin-ui-promo">
    <div class="swiper-container wpsight-promo-slider">
        <div class="swiper-wrapper">
            <?php foreach ($recommends as $key => $value) {
                $class = '';

                if ( ($value['title'] != "" ) || ($value['description'] != "" )  ) {
                    $class = 'swiper-slide swiper-slide-overlay';
                }
                ?>
                <a target="_blank" href="<?php echo $value['button_link']; ?>" class="swiper-slide <?php echo $class; ?>">
                    <img class="promo-slider-img" src="<?php echo $value['image_url']; ?>" alt="">
                        <div class="slide-content">
                            <span class="slide-content-title">
                                <?php echo $value['title']; ?>
                            </span>
                            <p class="slide-content-desc">
                                <?php echo $value['description']; ?>
                            </p>
                        </div>
                </a>
            <?php } ?>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
    </div>

</div>
