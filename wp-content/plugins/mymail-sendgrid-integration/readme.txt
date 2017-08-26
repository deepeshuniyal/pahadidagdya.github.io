=== MyMail SendGrid Integration ===
Contributors: revaxarts
Tags: sendgrid, mymail, delivery, deliverymethod, newsletter, email, revaxarts, mymailesp
Requires at least: 3.7
Tested up to: 4.7.1
Stable tag: 0.5.1
License: GPLv2 or later

== Description ==

> This Plugin requires [MyMail Newsletter Plugin for WordPress](http://rxa.li/mymail?utm_campaign=wporg&utm_source=SendGrid+integration+for+MyMail)

Uses SendGrid to deliver emails for the [MyMail Newsletter Plugin for WordPress](http://rxa.li/mymail?utm_campaign=wporg&utm_source=SendGrid+integration+for+MyMail).

== Installation ==

1. Upload the entire `mymail-sendgrid-integration` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings => Newsletter => Delivery and select the `SendGrid` tab
4. Enter your credentials
5. Send a testmail

== Changelog ==

= 0.5.1 =
* tested with 4.7.1

= 0.5 =
* changed the way the core plugin is detected
* updated formatting
* updated textdomain slug

= 0.4.5 =

* fixed warning while verification
* fixed error handling after sending

= 0.4.4 =

* SVN problems, sorry

= 0.4.3 =

* fixed: issue with https endpoint on some servers
* fixed: issue with empty json encoded headers on some servers

= 0.4.2 =

* handles now blocks, spamreports and unsubscribes from the API as well

= 0.4.1 =

* better message on delivery errors via WEB API

= 0.4 =

* fixed: bounce handling
* change: requires now MyMail 2.0.25
* added: option to select bounce handling (via MyMail or SendGrid)
* added: option to add catagories to mails (X_SMTPAPI)

= 0.3.1 =

* secure settings now applies to WEB API as well

= 0.3 =

* moved to class based structure
* fixed missing tracking pixel when WEB API is used

= 0.2.5 =

* fixed verification problems

= 0.2.4 =
* sending via SMTP is now faster

= 0.2.3 =
* fixed a bug where mails are not send at an early stage of the page load

= 0.2.2 =
* added port check for SMTP connection

= 0.2.1 =
* small bug fixes

= 0.2 =
* small bug fixes

= 0.1 =
* initial release

== Additional Info One ==

This Plugin requires [MyMail Newsletter Plugin for WordPress](http://rxa.li/mymail?utm_campaign=wporg&utm_source=SendGrid+integration+for+MyMail)
