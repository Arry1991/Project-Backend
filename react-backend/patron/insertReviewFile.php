<?php
/*This file will allow the patron to write review
**and rate the business only if they have been to that
**business.
*/

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
} else {
  //insert statement
  $query = "INSERT INTO review (business_id, patron_id, mask_rating, social_distance_rating, sanitize_rating, comment) VALUES (?,?,?,?,?,?)";
  $stmt = mysqli_stmt_init($conn);

  //if the review insert query failed
  if (!mysqli_stmt_prepare($stmt, $query)) {
    die("Fatal error for insert review query");
  } else {
    //prepare the statement to bind the variable
    mysqli_stmt_bind_param($stmt, "iiiiis", $row['id'], $patron_id, $mask_rating, $social_distance_rating, $sanitize_rating, $comment);

    //now check if the insert executed
    if (mysqli_stmt_execute($stmt)) {
      echo "Review is inserted";
    } else {
      echo "something went wrong with the executed statement";
    }
  }
}
