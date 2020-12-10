<?php
/*This file have the all the function
*/

// function sanitize($field){
//   $field = str_replace(' ', '', $field);
//   $field = trim(strip_tags($field));
// }

//This function will check if the phone number
//matches the formate

// function validatePhone($field){
//   $field = filter_var($field, FILTER_SANITIZE_NUMBER_INT);
//   $pater = "/[^0-9]/";
//   $field = validateAll($field);
//   if(preg_match($pater, $field)){
//     return "";
//   }else{
//     return "Invalided Phone number ";
//   }
// }

/*Any function above this comment 
**need to be worked on.*/


//this function will strip the html and php tags.
//it will put slashes and trim white space. It
//will also make everything lowercase.
function validateAll($field){
  $field = trim((strip_tags($field)));
  return $field;
}

//This function will remove any space 
//and it will remove semicolon, which
//can be use in sql injection.
function spaceSemicolon($field){
  $field = str_replace(' ', '', $field);
  $field = str_replace(';', '', $field);
  return $field;
}

//This function will sanitize email and
//check if the email has validate formate
function validateEmail($field){
  $field = filter_var($field, FILTER_SANITIZE_EMAIL);
  if (filter_var($field,FILTER_VALIDATE_EMAIL)){
    return "";
  }
  return "Invalided email ";
}

//This function will check if the field
//only contain alphabets only
function validateName($field){
  $field = validateAll($field);
  if (ctype_alpha($field)) {
    return "";
  }else{
    return "Make sure the value only contain alphabet";
  }
}


//This function will sanitize and validate url
function validateURL($field){
  $field = filter_var($field, FILTER_SANITIZE_URL);
  if(filter_var($field, FILTER_VALIDATE_URL)){
    return "";
  }else{
    return "Invalided URL ";
  }
}
