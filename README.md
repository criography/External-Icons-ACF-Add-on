External-Icons-ACF-Add-on
=========================

Extends [Advanced Custom Fields](http://www.advancedcustomfields.com/) with ability to define checkbox set, using images found in specified directory as their labels. It can return complete paths, icon filenames and filename-based sanitised strings to use in CSS, or wherever else you need it.

The idea behind it was to allow any user to pick 1 or more icons, without the need of importing dozens of them into Wordpress, or asking anyone to recognise text labels instead of their actual visual representation.

The build is rather simple (project specific) and there's a lot of potential for improvement, like ordering, sizing, etc.

But then again, I'm not really a backend dev anyway(;




Usage
-----
+ Upload your icons to a location of choice.
+ Place the file somewhere in your theme and initiate the field with the snippet below in your `functions.php`:

```php
if( function_exists( 'register_field' ) ){
	register_field('Icons_field', dirname(__File__) . '/[path_to_the_file]/icons.php');
}
```
+ While composing your *Custom Fields* you should be now able to see __Icons__ as field type. Selected it and fill __Icons Path__, __Filetypes__, __Allow Multiple Choices?__ and __Output Value__ options, accordingly to your requirements.


###Output###
If only one `Output Type` was chosen, the class will return a string. Otherwise expect an array per icon, e,g.:

```php
Array(
	'full_path' => '/path_to_file/filename.svg',
	'full_filename' => 'filename.svg',
	'css_class' => 'filename'
)
```


