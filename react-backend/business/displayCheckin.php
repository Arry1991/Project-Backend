<?php
/*This file will display the patron info base on
**selected business. It will send info as json to 
**the front-end. 
*/
header('Content-Type: application/json');

//start the session
session_start();

//Check if the user is login
if (isset($_SESSION['owner_id'])) {

  //Check if the user selected a business
  if (isset($_SESSION['business_id'])) {
    //include the file to connect with mysql 
    require_once '../mysqlConn.php';

    //set the owner id
    $business_id = $_SESSION['business_id'];

    //This query will get patron info
    $query = "SELECT p.first_name, p.last_name, p.email, s.temperature, s.sheet_date FROM spreadsheet AS s, patron AS p where s.business_id = ? and  s.patron_id = p.id ORDER BY s.sheet_date DESC";
    $stmt = mysqli_stmt_init($conn);

    //if the query does not run
    if (!mysqli_stmt_prepare($stmt, $query)) {
      die("Fatal error the spreadsheet select query did not run");
    } else {

      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "i", $business_id);

      //execute the statement
      if (mysqli_stmt_execute($stmt)) {
        //prepare the result
        $result = mysqli_stmt_get_result($stmt);


        //Make array which get encode it into json
        //which will take patron and spreadsheet info
        $display_table = array();

        //help with increment
        $i = 0;

        //This loop will help us get the data to display on the business main page 
        while ($row = mysqli_fetch_assoc($result)) {

          //store the data inside the array
          $display_table[$i] = ["first_name" => $row['first_name'], "last_name" => $row['last_name'], "email" => $row['email'], "temperature" => $row['temperature'], "sheet_date" => $row['sheet_date']];

          //increment
          $i++;
        }

        //encode the array into json formate
        $json = json_encode($display_table, JSON_PRETTY_PRINT);

        //if no data get store
        if (!$json) {
          echo "Something went wrong with the json";
        } else {
          //now echo it 
          echo $json;
        }
      } else {
        //display an error if the insert statement
        //does not execute
        echo "The statement did not execute";
      }
    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);

    //close the connection
    mysqli_close($conn);
  } else {
    die("Please select a business");
  }
} else {
  die("Please login");
}
