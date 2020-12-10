<?php
/*This file will get a business info base on 
**the selected business. So, this way the user
**can edited the business info. 
*/
header('Content-Type: application/json');

//start session
session_start();

//check if teh user is logged in
if (isset($_SESSION['patron_id'])) {

      //include the file to connect with mysql 
    require_once '../mysqlConn.php';

    //set the business id
    $patron_id = $_SESSION['patron_id'];

    //Use the select to get the business id.
    $query = "SELECT * FROM patron where id = ?";
    $stmt = mysqli_stmt_init($conn);

    //if the business query failed
    if (!mysqli_stmt_prepare($stmt, $query)) {
      //display error 
      die("Fatal error the business select query failed");
    } else {
      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "i", $patron_id);

      //execute the statement
      if (mysqli_stmt_execute($stmt)) {

        //get result
        $result = mysqli_stmt_get_result($stmt);

        //define a array to pass into json function
        $display_info = array();

        //get the fetch array to set the data
        $row = mysqli_fetch_assoc($result);

        //store business info as array
        $display_info = array("first_name" => $row['first_name'], "last_name" => $row['last_name'], "email" => $row['email']);

        //free the memory
        mysqli_stmt_free_result($stmt);

        //close the statement
        mysqli_stmt_close($stmt);

        //encode the array into json formate
        $json = json_encode($display_info, JSON_PRETTY_PRINT);

        //display the data
        echo $json;
      } else {

        //for some reason if we do not get the id 
        echo "Fatal error no data of the id";
      }
    }

    //close the connection
    mysqli_close($conn);

} else {

  //display error
  echo "Log in please";

  //if the business owner is not logged in
  die(http_response_code(404));
}
