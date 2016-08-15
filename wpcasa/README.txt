=== WPCasa ===
Contributors: wpsight, simonrimkus
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZRR56U2VTPZAQ
Tags: real estate, realestate, agency, agent, directory, house, listing, listings, property, properties, property management, realtor, wpcasa
Requires at least: 4.0
Tested up to: 4.6
Stable tag: 1.0.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Flexible WordPress plugin to create professional real estate websites and manage property listings with ease.

== Description ==

WPCasa is a **real estate** WordPress solution that provides an intuitive way to manage property listings and create first-class real estate websites. No coding required.

* Website: [wpcasa.com](https://wpcasa.com)
* Demo: [demo.wpcasa.com](http://demo.wpcasa.com)
* Documentation: [docs.wpcasa.com](http://docs.wpcasa.com/)
* Add-Ons: [wpcasa.com/add-ons](https://wpcasa.com/add-ons)
* Themes: [wpcasa.com/themes](https://wpcasa.com/themes)
* Github: [github.com/wpsight/wpcasa](https://github.com/wpsight/wpcasa)

> Have a look at our latest theme [WPCasa Oslo](https://wpcasa.com/downloads/wpcasa-oslo/).

= Easy as WordPress =

WPCasa fully integrates with the familiar and easy-to-use interface that ships with WordPress. You'll be a PRO.

= Flexible & Extendable =

With our custom themes and add-ons you can modularly create a powerful real estate tool tailored to your special needs.

= Advanced Property Search =

The heart of a real estate website is a decent search. Using the advanced filters your clients will find any listing in seconds.

= Intuitive Listing Editor =

Adding property details, locations, image galleries and more is a breeze using our intuitive WPCasa listing editor.

= Works Out of the Box =

Using the existing shortcodes or the powerful template system you can use WPCasa with any WordPress theme out there.

= Admin Property Management =

You can manage great numbers of listings using the well-organized property list with filters and bulk actions.

= Developer Friendly =

WPCasa comes with readable & well-documented code with loads of actions, filters and templates for developers to hook in.

= Translation Ready =

The real estate business is international. And so is WPCasa. The framework and all our add-ons and themes are translation-ready.

= Translations =

* German (de_DE): Simon Rimkus (WPSight)
* Spanish (es_ES): Simon Rimkus (WPSight)
* Portuguese (pt_BR): [Walter Barcelos](http://walterbarcelos.com)

POT file with text strings is included. If you would like to add a translation and see your name here, please [get in touch](https://wpcasa.com/contact/).

= Other Features =

* Handy shortcodes
* Template system
* Theme compatibility
* Comprehensive plugin settings
* Custom agent user roles
* Google Maps integration
* Listing print view
* Responsive elements
* schema.org mirco formats
* Extensive functions API
* RTL CSS

= Custom Post Type =

* Listings (listing)

= Custom Taxonomies =

* Locations (location)
* Listing Types (listing-type)
* Features (feature)
* Categories (listing-category)

== Installation ==

= Automatic Installation =

Automatic installation is the easiest way to install WPCasa. Log into your WordPress admin and go to _WP-Admin > Plugins > Add New_.

Then type "WPCasa" in the search field and click _Install Now_ once you've found the plugin.

= Manual Installation =

If you prefer to install the plugin manually, you need to download it to your local computer and upload the unzipped plugin folder to the `/wp-content/plugins/` directory of your WordPress installation. Then activate the plugin on _WP-Admin > Plugins_.

= Getting Started =

Once you have installed and activated WPCasa you will find a new page called "Listings" with the `[wpsight_listings]` shortcode. Add your listings on _WP-Admin > Listings > Add New_ and they will be listed on that page.

For more information about how to get started please [read our documentation](http://docs.wpsight.com/).

== Frequently Asked Questions ==

= Which shortcodes are included? =

* `[wpsight_listings]`: Displays a list of your latest properites
* `[wpsight_listings_search]`: Displays the property search form
* `[wpsight_listing]`: Displays a single listing
* `[wpsight_listing_teasers]`: Displays a list of property teasers
* `[wpsight_listing_teaser]`: Displays a single property teaser

For more information about shortcodes please [read our documentation](http://docs.wpsight.com/article/shortcodes/).

= Where can I change currency, measurements etc.? =

You can fit general plugin settings to your needs on _WP-Admin > WPCasa > Settings > [tab] Listings_. Things like listing ID prefix, measurement unit, currency, standard listing features, rental periods and more can be set here.

= Is WPCasa free? =

Yes, the core features are free. Additionally we offer free and paid add-ons and themes exclusively built for WPCasa.

= How can I contribute? =

If you find WPCasa an interesting project, please feel free to have a look at our [Github repo](https://github.com/wpsight/wpcasa).

== Screenshots ==

1. Listings archive
2. Single listing
3. Listing teasers
4. Property search form (horizontal)
5. Property search form (vertical)
6. Plugin settings
7. Property Management

== Changelog ==

= 1.0.6.1 =
* Tested up to WordPress 4.5.3
* Added option to enter Google Maps API key

= 1.0.6 =
* Tested up to WordPress 4.5
* Add Brazilian Portuguese language files
* Fix typo in German language files
* Make sure $_GET values in listings search are escaped correctly
* Make sure wpsight_exclude_unavailable filter works correctly
* Make sure default rental periods are set correctly
* Rebuild custom agent profiles fields using CMB2 meta boxes
* Minor updates in German and Spanish language files

= 1.0.5 =
* Add Spanish language files

= 1.0.4.1 =
* Add wpsight_profile_agent_update_post_meta filter for WPCasa Polylang

= 1.0.4 =
* Improve maybe_update_gallery()
* Add missing string in German translation
* Add property management plugin screenshot

= 1.0.3.1 =
* Hotfix to avoid fatal error with wpsight_taxonomies() in v1.0.3 and some themes

= 1.0.3 =
* Hide listing label when label is empty
* Hide rental periods when label is empty
* Fix default handling in wpsight_get_option()
* Minor code cosmetics

= 1.0.2 =
* Add wpsight_get_option filter hook
* Make location fields visible in dashboard
* Add helper function wpsight_taxonomies()
* Correct WPSight_Geocode class name

= 1.0.1 =
* Fix custom query pagination (offset must be set or empty string)
* Fix CMB2 group fields handling through wpsight_meta_boxes filter

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.4.1 =
* Small improvement for the WPCasa Polylang add-on
