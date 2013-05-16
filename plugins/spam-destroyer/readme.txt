=== Spam Destroyer ===
Contributors: ryanhellyer, bjornjohansen, dimadin
Donate link: http://geek.ryanhellyer.net/products/spam-destroyer/
Tags: spam, anti-spam, antispam, buddypress, bbpress, kill, destroy, eliminate
Requires at least: 3.5
Stable tag: 1.4.5 alpha

Kills spam dead in it's tracks. Be gone evil demon spam!

== Description ==

Stops automated spam while remaining as unobtrusive as possible to regular commenters. <a href="http://geek.ryanhellyer.net/products/spam-destroyer/">The Spam Destroyer plugin</a> is intended to be effortless to use, simply install
and enjoy a spam free website :)

<small>This plugin now features experimental support for bbPress user registrations, bbPress guest posting, BuddyPress posts and commenting and WordPress multi-site user and blog signups. If you test it, I'd love to <a href="http://geek.ryanhellyer.net/contact/">hear about the results</a>.</small>

== Installation ==

Simply install and activate the plugin. No settings necessary.

For more information, visit the <a href="http://geek.ryanhellyer.net/products/spam-destroyer/">Spam Destroyer plugin page</a>.

<small>Note: Spam Destroyer apparently does not work in conjunction with the Jetpack plugin. That plugin has a nasty way of handling it's commenting system which requires you to connect to an external service and will not work if your installation is not connected to the internet (which is the case for all my development sites) and as such this problem will not be fixed any time soon. If the Jetpack team fix these major problems I will happily make the plugin compatible with it, but in the mean time I suggest avoiding the Jetpack plugin at all costs.</small>


== Frequently Asked Questions ==

Check out the FAQ on the <a href="http://geek.ryanhellyer.net/products/spam-destroyer/">Spam Destroyer plugin</a> page.


== Changelog ==

= 1.4.5 alpha (11/5/2013) =
* Fixed tracking system
* Began switch to post requests to tracking system
= 1.4.4 alpha (11/4/2013) =

= 1.4 alpha (30/3/2013) =
* Fixed bug preventing URL lookup of trackbacks
* Added support for higher level spam protection including simple captcha
* Added support for tracking to assist with plugin development
* Added bbPress support
* Added BuddyPress support
* Added WordPress multisite support
* Added support for non-Javascript users when on low level protection
= 1.3 (6/3/2013) =
* Instantiated class to variable to allow for remove hooks and filters when necessary
* Added redirect after spam comment detected
* Added error notice on redirection due to spam comment detection
= 1.2.5 (19/8/2012) =
* Changed from kill.php file to kill.js file
* Allows for caching of payload
* Allows for automatic script concatentation
* Cookie creation achieved via raw JS
* Key is passed to script via wp_localize_script()
= 1.2.4 (11/8/2012) =
* Re-removed requirement for jQuery
* Added try / catch to JS to ensure it doesn't fail
* Moved JS enqueue to form field area so that it only loads when needed
* Added Bjørn Johansen to the contributor list
* Added correct mime-type to JS file
= 1.2.3 (9/8/2012) =
* Added requirement for jQuery due to bug with code introduced in 1.2.2
= 1.2.2 (9/8/2012) =
* Removed need for jQuery
= 1.2.1 (9/8/2012) =
* Moved script to footer on advice of Ronald Huereca and Bjørn Johansen
* Fixed potential security flaw in kill.php
= 1.2 (5/8/2012) =
* Fixed multisite and BuddyPress bugs
* Added support for bbPress registrations
* Added support for bbPress guest posting protection
* Removed the "bad word" list
= 1.1 (5/8/2012) =
* Added support for BuddyPress signup page
* Added support for WordPress multisite signup page
= 1.0.3 (30/7/2012) =
* Upgrade to documentation
= 1.0.2 (30/7/2012) =
* Changed name to 'spam-destroyer'
= 1.0.1 (30/7/2012) =
* Cleaned up some legacy code from older implementations
= 1.0 (29/7/2012) =
* Initial release

<small>Any beta/alpha versions to be released in future, will be posted for download on the <a href="http://geek.ryanhellyer.net/products/spam-destroyer/">Spam Destroyer plugin page</a>.</small>


== Credits ==

* <a href="http://ocaoimh.ie/">Donncha O Caoimh</a> - Developer of Cookies for Comments, functionality of which is incorporated into Spam Destroyer<br />
* <a href="http://elliottback.com/">Elliot Back</a> - Developer of WP Hashcash, functionality of which is incorporated into Spam Destroyer<br />
* <a href="#">Brian Layman</a> - Code advice<br />
* <a href="http://ronalfy.com/">Ronald Huereca</a> - JS advice<br />
* <a href="https://twitter.com/shawngaffney">Shawn Gaffney</a> - Bug reporting<br />
* <a href="http://konstruktors.com/">Kaspars Dambis</a> - Bug reporting<br />

