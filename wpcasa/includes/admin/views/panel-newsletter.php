<style>
.mc-field-group {margin: 0 0 1rem 0;}
.mc-field-group input {max-width: 100%;}
</style>                                    
                                    
<h3><?php _e( 'Newsletter', 'wpcasa' ); ?></h3>
<p><?php _e( 'Subscribe to our Newsletter and stay in the loop', 'wpcasa' ); ?></p>
                                    
<!-- Begin MailChimp Signup Form -->
<div id="mc_embed_signup">
    <form action="https://wpcasa.us1.list-manage.com/subscribe/post?u=6b72fd1d9478637d374a68873&amp;id=e2758b7d77" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
        <div id="mc_embed_signup_scroll">
			<?php $current_user = wp_get_current_user(); ?>                                   
            <div class="mc-field-group">
                <input type="text" value="<?php echo esc_html( $current_user->display_name ); ?>" name="NAME" class="required regular-text" id="mce-NAME" placeholder="<?php _e( 'Name', 'wpcasa' ); ?>">
            </div>
            <div class="mc-field-group">
                <input type="email" value="<?php echo esc_html( $current_user->user_email ); ?>" name="EMAIL" class="required email regular-text" id="mce-EMAIL" placeholder="<?php _e( 'Email Address', 'wpcasa' ); ?>">
            </div>
            
            <div id="mce-responses" class="clear">
                <div class="response" id="mce-error-response" style="display:none"></div>
                <div class="response" id="mce-success-response" style="display:none"></div>
            </div>
            <div style="position: absolute; left: -6786px;" aria-hidden="true"><input type="text" name="b_6b72fd1d9478637d374a68873_e2758b7d77" tabindex="-1" value=""></div>
            <input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button button-primary">
            
        </div>
    </form>
</div>

<script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
<script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='NAME';ftypes[1]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
<!--End mc_embed_signup-->