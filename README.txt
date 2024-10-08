=== Coronavirus Utilities ===
Contributors: ucfwebcom
Requires at least: 5.3
Tested up to: 6.1
Stable tag: 1.2.0
Requires PHP: 7.0
License: GPLv3 or later
License URI: http://www.gnu.org/copyleft/gpl-3.0.html

Utility and feature plugin for the UCF Coronavirus website.


== Description ==

Utility and feature plugin for the UCF Coronavirus website.


== Documentation ==

Head over to the [Coronavirus Utilities wiki](https://github.com/UCF/Coronavirus-Utilities/wiki) for detailed information about this plugin, installation instructions, and more.


== Changelog ==

= 1.1.3 =
Bug Fixes:
* Added cache busting for ICYMI email.

= 1.1.2 =
Enhancements:
* Added composer file.

= 1.1.1 =
Enhancements:
* Modified email "preview" nomenclature throughout email builder tools to more clearly define them as "tests"

= 1.1.0 =
Enhancements:
* Added the ability to send weekly email tests from the email builder directly.
* Added Customizer options for changing the subject line and "from" details for email tests.

= 1.0.1 =
Bug fixes:
* Fixed styling for nested list items in WYSIWYG fields in the coronavirus email builder.

= 1.0.0 =
* Initial release


== Upgrade Notice ==

n/a


== Development ==

Note that compiled, minified css files are included within the repo.  Changes to these files should be tracked via git (so that users installing the plugin using traditional installation methods will have a working plugin out-of-the-box.)

[Enabling debug mode](https://codex.wordpress.org/Debugging_in_WordPress) in your `wp-config.php` file is recommended during development to help catch warnings and bugs.

= Requirements =
* node
* gulp-cli

= Instructions =
1. Clone the Coronavirus-Utilities repo into your local development environment, within your WordPress installation's `plugins/` directory: `git clone https://github.com/UCF/Coronavirus-Utilities.git`
2. `cd` into the new Coronavirus-Utilities directory, and run `npm install` to install required packages for development into `node_modules/` within the repo
3. Optional: If you'd like to enable [BrowserSync](https://browsersync.io) for local development, or make other changes to this project's default gulp configuration, copy `gulp-config.template.json`, make any desired changes, and save as `gulp-config.json`.

    To enable BrowserSync, set `sync` to `true` and assign `syncTarget` the base URL of a site on your local WordPress instance that will use this plugin, such as `http://localhost/wordpress/my-site/`.  Your `syncTarget` value will vary depending on your local host setup.

    The full list of modifiable config values can be viewed in `gulpfile.js` (see `config` variable).
3. Run `gulp default` to process front-end assets.
4. If you haven't already done so, create a new WordPress site on your development environment to test this plugin against, and [install and activate all plugin dependencies](https://github.com/UCF/Coronavirus-Utilities/wiki/Installation#installation-requirements).
5. Activate this plugin on your development WordPress site.
6. Configure plugin settings from the WordPress admin via the Customizer (Appearance > Customize > Weekly Emails).
7. Run `gulp watch` to continuously watch changes to scss files.  If you enabled BrowserSync in `gulp-config.json`, it will also reload your browser when plugin files change.

= Other Notes =
* This plugin's README.md file is automatically generated. Please only make modifications to the README.txt file, and make sure the `gulp readme` command has been run before committing README changes.  See the [contributing guidelines](https://github.com/UCF/Coronavirus-Utilities/blob/master/CONTRIBUTING.md) for more information.


== Contributing ==

Want to submit a bug report or feature request?  Check out our [contributing guidelines](https://github.com/UCF/Coronavirus-Utilities/blob/master/CONTRIBUTING.md) for more information.  We'd love to hear from you!
