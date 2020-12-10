<?php
/*This file will allow the patron to write review
**and rate the business only if they have been to that
**business.
*/

//start the session
session_start();

//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //now check if the user click the submit button submitton 
  
  //include the file to connect with mysql 
  require_once '../mysqlConn.php';

  //declare the variable
  $mask_rating = $social_distance_rating =  $sanitize_rating = $comment = "";

  //declare post variable
  $mask_rating = htmlspecialchars($_POST['mask_rating']);
  $social_distance_rating = htmlspecialchars($_POST['social_distance_rating']);
  $sanitize_rating = htmlspecialchars($_POST['sanitize_rating']);
  $comment = htmlspecialchars($_POST['comment']);

  if (empty($mask_rating) || empty($social_distance_rating) || empty($sanitize_rating) || empty($comment)) {
    //display error if the value are empty
    die("Make sure all the values are enter");
  }else{
    //set the patron id
    $patron_id = $_SESSION['patron_id'];
    
  } 

  //check if the patron allow to write the review
} else {
  //send error message
  die("Please Log-in");
}