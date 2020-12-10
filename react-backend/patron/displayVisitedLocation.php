<?php
/*This file will look for all of the location 
**patron visited and put as json formate. 
*/
header('Content-Type: application/json');

//start the session
session_start();

//Check if the patron is logged in
if (isset($_SESSION['patron_id'])) {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';

  //set the patron id
  $patron_id = $_SESSION['patron_id'];

  //search by the patron id and also will us get the date
  $query = "SELECT business.name, business.type, business.email, business.phone, business.street, business.town, business.town, business.zip, spreadsheet.temperature, spreadsheet.sheet_date
  from ((spreadsheet 
  INNER JOIN patron
  ON spreadsheet.patron_id = patron.id)
  INNER JOIN business
  ON spreadsheet.business_id = business.id)
  WHERE spreadsheet.patron_id = ? 
  ORDER BY spreadsheet.sheet_date DESC";
  $stmt = mysqli_stmt_init($conn);

  //if the query does not run
  if (!mysqli_stmt_prepare($stmt, $query)) {
    //terminate the program
    die("Fatal error the spreadsheet select query did not run");
  } else {

    //bind the variable to prepare the statement
    mysqli_stmt_bind_param($stmt, "i", $patron_id);

    //Check if the statement executed
    if (mysqli_stmt_execute($stmt)) {

      //prepare the result
      $result = mysqli_stmt_get_result($stmt);

      //Make array which get encode it into json
      //which will take business address info and spreadsheet date.
      $display_table = array();

      //help with increment
      $i = 0;

      //this loop will get all the info to display on the table
      while ($row = mysqli_fetch_assoc($result)) {

        //store the data inside the array
        $display_table[$i] = ["name" => $row['name'], "type" => $row['type'], "email" => $row['email'], "phone" => $row['phone'], "street" => $row['street'], "town" => $row['town'], "zip" => $row['zip'], "temperature" => $row['temperature'], "sheet_date" => $row['sheet_date']];

        //increment
        $i++;
      }
    } else {
      //display error if the query did not execute
      echo "The query did not execute";
    }


    //encode the array into json formate
    $json = json_encode($display_table, JSON_PRETTY_PRINT);

    //if the there nothing inside the json
    if ($json) {
      //now echo it 
      echo $json;
    } else {
      echo "There is not data inside the json";
    }
  }

  //free the memory
  mysqli_stmt_free_result($stmt);

  //close the statement
  mysqli_stmt_close($stmt);

  //close the connection
  mysqli_close($conn);
} else {
  die("Please Log-in");
}
