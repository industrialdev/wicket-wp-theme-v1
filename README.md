# Wicket WordPress Theme

## Dev Environment Setup Notes
* Clone repo
* Run `yarn` to install node dependencies
* Run `yarn gulp` (or just `gulp` if you have it installed globally on your system) to freshly build the scripts and styles, and kickoff the 'watch' command while you make frontend file changes

## Developer Helpers:
* Add `?bootstrap` to your URL to temporarily enqueue Bootstrap via CDN for purposes of migrating from legacy Bootstrap styles
* Add `?breakpoints` to your URL to show what Tailwind breakpoint you're currently viewing with an indicator in the bottom-right corner
* In your PHP code, you can use `wicket_write_log ( $message )` to print anything to the debug.log, including arrays, objects, and strings. Helpful if you don't want to print_r things to the screen wrapped in `<pre>` tags, _but_ you can also use `write_log ( $message, true )` to do just that with less code 🙂
  * Note that we had to add "wicket_" to the front of the function name because other plugins sometimes use a simpler version of "write_log" for their own debugging purposes
* "Deprecated" and "Notice" error logs have been hidden via an mu-plugin
* `wicket_generate_structured_menu( $wp_nav_items_array )` accepts an array of WP nav items (grabbed from a function like `wp_get_nav_menu_items()`, for example), and returns a structured associative array of menu items, with child items nested in the array (currently up to 3 levels). It also calculates the number of children and grandchildren items below each item and saves them in the array, making menu rendering much easier and requiring less looping in the template.
* Add `?colours` to your URL to show all colours available to Tailwind that were generated from the `theme.json` file. 
  * If the names or varieties of the colours are changed in either `theme.json` or `tailwind.config.js`, you can use `?generate_colours_array` and the looping function in `header.php` to generate a new array of colours to place inside the `?colours` conditional inside `header.php`.
  * If this ever needs to be removed to make the CSS file _slightly_ smaller (as it doesn't generate that much CSS), a find/replace could be done to replace "bg-" with something like "bghide-" in that block of code, which would make Tailwind ignore it at build time. 
