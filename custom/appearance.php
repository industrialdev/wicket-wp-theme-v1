<?php

function my_mce_before_init_insert_formats( $init_array ) {  
  // Define the style_formats array
  $style_formats = array(  
    /*
    * Each array child is a format with it's own settings
    * Notice that each array has title, block, classes, and wrapper arguments
    * Title is the label which will be visible in Formats menu
    * Block defines whether it is a span, div, selector, or inline style
    * Classes allows you to define CSS classes
    * Wrapper whether or not to add a new block-level element around any selected elements
    */
    array(
      'title' => 'Large Text',  
      'block' => 'p',  
      'classes' => 'text--large',
      'wrapper' => false,
    ),
    array(  
      'title' => 'Button',  
      'selector' => 'a',  
      'classes' => 'button button--primary'             
    )
  );  
  // Insert the array, JSON ENCODED, into 'style_formats'
  $init_array['style_formats'] = json_encode( $style_formats );
   
  return $init_array;  
} 
// Attach callback to 'tiny_mce_before_init' 
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' ); 

