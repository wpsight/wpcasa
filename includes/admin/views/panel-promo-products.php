<?php
    $images = [
        'https://images.unsplash.com/photo-1575976371069-bf1d39f2fd21?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80',
        'https://images.unsplash.com/photo-1575991519121-156b78281296?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80',
        'https://images.unsplash.com/photo-1575931923112-bf17a477d09c?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=622&q=80',
        'https://images.unsplash.com/photo-1575915655585-76375f9d46bc?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80',
        'https://images.unsplash.com/photo-1575961895658-53c39b1d3307?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80',
        'https://images.unsplash.com/photo-1575931390568-44e58811bf7a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80',
        'https://images.unsplash.com/photo-1575923877462-a865f26c967a?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=634&q=80'
    ];

    shuffle($images);
?>

<div class="wpsight-admin-ui-promo">

    <div class="swiper-container wpsight-promo-slider">
        <div class="swiper-wrapper">

            <?php foreach ($images as $image) { ?>
                <div class="swiper-slide">
                    <img class="promo-slider-img" src="<?php echo $image; ?>" alt="">
                </div>
            <?php } ?>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <div class="swiper-pagination"></div>
    </div>

</div>
