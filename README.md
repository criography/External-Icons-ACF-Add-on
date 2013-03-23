External-Icons-ACF-Add-on
=========================

Extends [Advanced Custom Fields](http://www.advancedcustomfields.com/) with ability to define checkbox set, using images found in specified directory as their labels.
The idea behind it was to allow any user to pick 1 or more icons, without the need of importing dozens of them into Wordpress, or asking anyone to recognise text labels instead of their actual visual representation.

The build is rather simple and there's a lot of potential for improvement, like ordering, sizing, etc.
And, well, I'm not much of a backend dev anyway(;




Usage
-----

Place the file somewhere in your theme and initiate the field with the snippet below in your `functions.php`:

```php
if( function_exists( 'register_field' ) ){
	register_field('Icons_field', dirname(__File__) . '/[path_to_the_file]/icons.php');
}
```
