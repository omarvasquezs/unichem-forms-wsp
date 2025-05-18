=== Unichem Forms - WhatsApp Integration ===
Contributors: omarvasquez
Donate link: https://beacons.ai/omarvasquez
Tags: forms, whatsapp, contact, form, redirect
Requires at least: 5.0
Tested up to: 6.5
Stable tag: 1.1.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create customizable forms whose submissions are sent to WhatsApp with formatted messages.

== Description ==

Unichem Forms - WhatsApp Integration allows users to create a custom form that redirects submissions to a WhatsApp API URL, enabling personalized customer engagement via WhatsApp conversations.

Features include:
* Admin form builder with customizable fields (text, textarea, email, phone)
* Field customization options: placeholders, hide labels
* WhatsApp integration with formatted messages
* Proper handling of form submission redirects to WhatsApp without popup blocking
* User-friendly interface with feedback messages

== Installation ==

1. Upload `unichem-forms-wsp` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Unichem Forms WSP settings page to configure your form fields and WhatsApp number
4. Add the shortcode `[unichem_whatsapp_form]` to any page or post where you want the form to appear

== Frequently Asked Questions ==

= How do I configure the WhatsApp number? =

Go to the Unichem Forms WSP settings page in your WordPress admin area, and enter your country code and phone number.

= Can I customize the form appearance? =

Yes, you can add custom CSS in the plugin settings or in your theme to style the form as needed.

= How do I add the form to my website? =

Use the shortcode `[unichem_whatsapp_form]` in any page or post.

== Screenshots ==

1. Form Builder Admin Interface
2. Frontend Form Example
3. WhatsApp Message Format

== Changelog ==

= 1.1.0 =
* Fixed issue with popup blocking by opening form submission in a new tab
* Added a more user-friendly redirect interface with WhatsApp button
* Improved feedback with success messages in the original tab
* Added loading animations and better button states
* Added meta referrer tag for better cross-domain redirects
* Enhanced mobile responsiveness
* Fixed issues with WhatsApp message formatting

= 1.0.0 =
* Initial release
* Basic form builder functionality
* WhatsApp integration with message formatting
* Customizable fields with labels and placeholders
* Hide label option

== Upgrade Notice ==

= 1.1.0 =
This update fixes the important issue with popup blocking and improves the user experience. Upgrade recommended for all users.

== Additional Info ==

For more information, please visit [Omar Vásquez](https://beacons.ai/omarvasquez).
