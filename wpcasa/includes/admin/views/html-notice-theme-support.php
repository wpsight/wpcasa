<?php
/**
 * Admin View: Notice - Theme Support
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$docs_url		= '//docs.wpcasa.com/article/third-party-custom-theme-compatibility/?utm_source=notice&utm_medium=product&utm_content=themecompatibility&utm_campaign=wpcasaplugin';
$themes_url		= admin_url( 'admin.php?page=wpsight-themes' );

?>
<div class="notice notice-warning wpsight-message">
	<a class="wpsight-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wpsight-hide-notice', 'theme_support' ), 'wpsight_hide_notices_nonce', '_wpsight_notice_nonce' ) ); ?>"></a>

	<p><?php printf( __( '<strong>Your theme does not declare support for WPCasa</strong> &#8211; Please read our %sintegration%s guide or check out our %savailable themes (free and premium)%s which are designed specifically for use with WPCasa.', 'wpcasa' ), '<a target="_blank" href="' . esc_url( apply_filters( 'wpcasa_docs_url', $docs_url, 'theme-compatibility' ) ) . '">', '</a>', '<a href="' . esc_url( $themes_url ) . '">', '</a>' ); ?></p>
	<p class="submit">
		<a href="<?php echo $themes_url; ?>" class="button-primary"><?php _e( 'View WPCasa Themes', 'wpcasa' ); ?></a>
		<a href="<?php echo esc_url( apply_filters( 'wpcasa_docs_url', $docs_url ) ); ?>" class="button-secondary" target="_blank"><?php _e( 'Theme Integration Guide', 'wpcasa' ); ?></a>
	</p>
</div>
