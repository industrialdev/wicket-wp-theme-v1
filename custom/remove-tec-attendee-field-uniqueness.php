<?php 

// This is meant to make the email and name fields on TEC event attendee fields not need to be unique
// https://app.asana.com/0/1209072571612469/1209151073097135

/**
 * When you set the Individual Attendee Collection functionality to required (https://i.imgur.com/jJCQhPm.png), each attendee will have to provide their personal details, 
 * and the name and email fields must be unique. However, this may not be ideal in certain scenarios, such as when parents purchase tickets for themselves and their children. 
 * In such cases, parents may not have individual email addresses for each child. To solve this issue, you can use the following code snippet to remove the uniqueness requirement 
 * from the name and email fields. This will allow parents to use the same name and email address for all attendees.
 */

add_filter( 'tribe_tickets_plus_attendee_registration_iac_fields', 'tec_tickets_plus_disable_iac_unique', 10, 3 ); 
function tec_tickets_plus_disable_iac_unique( $fields, $ticket_iac_setting, $ticket_id ) { 
  $fields['name']['classes']['tribe-tickets__form-field--unique'] = false; 
  $fields['email']['classes']['tribe-tickets__form-field--unique'] = false; 
  return $fields; 
}
