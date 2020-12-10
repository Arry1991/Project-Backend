<?php
/*This file will get a business info base on 
**the selected business and then send put inside
**the email.  
*/

//Use the select to get business information
$query = "SELECT * FROM business where id = ?";
$stmt = mysqli_stmt_init($conn);

//if the business query failed
if (!mysqli_stmt_prepare($stmt, $query)) {
  //display error
  die("Fatal error the business select query failed");
} else {
  //bind the variable to prepare the statement
  mysqli_stmt_bind_param($stmt, "i", $business_id);

  //execute the statement
  if (mysqli_stmt_execute($stmt)) {

    //get result
    $result = mysqli_stmt_get_result($stmt);

    //get the fetch array to set the data
    $business_row = mysqli_fetch_assoc($result);
  } else {
    //error for execute
    echo "Something went wrong with execute ";
  }
}

//free the memory
mysqli_stmt_free_result($stmt);

//close the statement
mysqli_stmt_close($stmt);
