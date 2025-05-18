# Unichem Forms - WhatsApp Integration

A WordPress plugin that creates customizable forms whose submissions are sent to WhatsApp with formatted messages.

## Description

Unichem Forms - WhatsApp Integration allows users to create a custom form that redirects submissions to a WhatsApp API URL, enabling personalized customer engagement via WhatsApp conversations.

### Features

* Admin form builder with customizable fields (text, textarea, email, phone)
* Field customization options: placeholders, hide labels
* WhatsApp integration with formatted messages
* Proper handling of form submission redirects to WhatsApp without popup blocking
* User-friendly interface with feedback messages

## Installation

1. Upload `unichem-forms-wsp` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Unichem Forms WSP settings page to configure your form fields and WhatsApp number
4. Add the shortcode `[unichem_whatsapp_form]` to any page or post where you want the form to appear

## Frequently Asked Questions

### How do I configure the WhatsApp number?

Go to the Unichem Forms WSP settings page in your WordPress admin area, and enter your country code and phone number.

### Can I customize the form appearance?

Yes, you can add custom CSS in the plugin settings or in your theme to style the form as needed.

### How do I add the form to my website?

Use the shortcode `[unichem_whatsapp_form]` in any page or post.

## Changelog

### 1.1.0
* Fixed issue with popup blocking by opening form submission in a new tab
* Added a more user-friendly redirect interface with WhatsApp button
* Improved feedback with success messages in the original tab
* Added loading animations and better button states
* Added meta referrer tag for better cross-domain redirects
* Enhanced mobile responsiveness
* Fixed issues with WhatsApp message formatting

### 1.0.0
* Initial release
* Basic form builder functionality
* WhatsApp integration with message formatting
* Customizable fields with labels and placeholders
* Hide label option

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
```

## Credits

Developed by [Omar Vásquez](https://beacons.ai/omarvasquez)
