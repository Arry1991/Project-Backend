<?php
/*This file will get the owner info from
**the database for the owner. 
*/
header('Content-Type: application/json');

//start session
session_start();

//check if the user is logged in
if (isset($_SESSION['owner_id'])) {
  //include the file to connect with mysql 
  require_once '../mysqlConn.php';

  //set the owner id
  $id = $_SESSION['owner_id'];

  //Look for the owner base of the id
  $query = "SELECT * FROM business_owner where id = ?";
  $stmt = mysqli_stmt_init($conn);

  //if the owner query failed
  if (!mysqli_stmt_prepare($stmt, $query)) {
    die("Fatal error the business select query failed");
  } else {
    //bind the variable to prepare the statement
    mysqli_stmt_bind_param($stmt, "i", $id);

    //execute the statement
    mysqli_stmt_execute($stmt);

    //get result
    $result = mysqli_stmt_get_result($stmt);

    //define a array to pass into json function
    $owner_info = array();

    //get the fetch array to set the data
    if ($row = mysqli_fetch_assoc($result)) {

      //store info as array
      $owner_info = array("first_name" => $row['first_name'], "last_name" => $row['last_name'], "email" => $row['email']);

    } else {

      //for some reason if we do not get the id 
      die("Fatal error no data");

    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);

    //encode the array into json formate
    $json = json_encode($owner_info, JSON_PRETTY_PRINT);

    //display the data
    echo $json;
  }

  //close the connection
  mysqli_close($conn);
} else {
  die("Please login");
}
