<?php
/*This file will get a business info base on 
**the selected business. So, this way the user
**can edited the business info. 
*/
header('Content-Type: application/json');

//start session
session_start();

//check if teh user is logged in
if (isset($_SESSION['owner_id'])) {

  //check if the user selected a business
  if (isset($_SESSION['business_id'])) {

    //include the file to connect with mysql 
    require_once '../mysqlConn.php';

    //set the business id
    $business_id = $_SESSION['business_id'];

    //Use the select to get the business id.
    $business_query = "SELECT * FROM business where id = ?";
    $business_stmt = mysqli_stmt_init($conn);

    //if the business query failed
    if (!mysqli_stmt_prepare($business_stmt, $business_query)) {

      //display error and make sureeeeeeee to remove thisssss
      die("Fatal error the business select query failed");
    } else {
      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($business_stmt, "i", $business_id);

      //execute the statement
      if (mysqli_stmt_execute($business_stmt)) {

        //get result
        $result = mysqli_stmt_get_result($business_stmt);

        //define a array to pass into json function
        $display_info = array();

        //get the fetch array to set the data
        $row = mysqli_fetch_assoc($result);

        //store business info as array
        $display_info = array("name" => $row['name'], "type" => $row['type'], "email" => $row['email'], "phone" => $row['phone'], "description" => $row['description'], "street" => $row['street'], "town" => $row['town'], "zip" => $row['zip'], "county" => $row['county'], "alert" => $row['alert']);

        //free the memory
        mysqli_stmt_free_result($business_stmt);

        //close the statement
        mysqli_stmt_close($business_stmt);

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

    //if the user did not select a business display the error
    echo "Select a business";
  }
} else {

  //displayyyy errorr remove this later
  echo "Log in please";

  //if the business owner is not logged in
  die(http_response_code(404));
}
