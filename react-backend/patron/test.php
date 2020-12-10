<?php

//include the file to connect with mysql 
require_once './mysqlConn.php';

//set the patron id
$patron_id = 1;

//search by the patron id and also will us get the date
$spreadsheet_query = "SELECT * FROM spreadsheet where patron_id = ?";
$spreadsheet_stmt = mysqli_stmt_init($conn);

//if the query does not run
if (!mysqli_stmt_prepare($spreadsheet_stmt, $spreadsheet_query)) {

  //terminate teh program
  die("Fatal error the spreadsheet select query did not run");

} else {

  //bind the variable to prepare the statement
  mysqli_stmt_bind_param($spreadsheet_stmt, "i", $patron_id);

  //execute the statement
  mysqli_stmt_execute($spreadsheet_stmt);

  //prepare the result
  $spreadsheet_result = mysqli_stmt_get_result($spreadsheet_stmt);

  //if nothing is fetch than send error
  if (!$spreadsheet_result) {

    //terminate
    die("Fatal error, no data");

  } else {

    //Now search for business to get the location
    $business_query = "SELECT * FROM business where id = ?";
    $business_stmt = mysqli_stmt_init($conn);

    //if the business query fails 
    if (!mysqli_stmt_prepare($business_stmt, $business_query)) {

      //terminate 
      die("Fatal error for the business query");

    } else {

      //Make array which get encode it into json
      //which will take business address info and spreadsheet date.
      $display_table = array();

      //help with increment
      $i = 0;

      //Loop through the data and store it in a array 
      while ($spreadsheet_row = mysqli_fetch_assoc($spreadsheet_result)) {

        //prepare statement to get the business id
        mysqli_stmt_bind_param($business_stmt, "i", $spreadsheet_row['business_id']);

        //execute the statement
        mysqli_stmt_execute($business_stmt);

        //prepare the statement for the result
        $business_result = mysqli_stmt_get_result($business_stmt);

        //get the associated array for patron
        $business_row = mysqli_fetch_assoc($business_result);

        //store the data inside the array
        $display_table[$i] = ["name" => $business_row['name'], "type" => $business_row['type'], "email" => $business_row['email'], "phone" => $business_row['phone'], "street" => $business_row['street'], "town" => $business_row['town'], "zip" => $business_row['zip'], "temperature" => $spreadsheet_row['temperature'], "sheet_date" => $spreadsheet_row['sheet_date']];

        //increment
        $i++;
      }

      //free the memory
      mysqli_stmt_free_result($spreadsheet_stmt);
      mysqli_stmt_free_result($patron_stmt);

      //close the statement
      mysqli_stmt_close($spreadsheet_stmt);
      mysqli_stmt_close($patron_stmt);

      //encode the array into json formate
      $json = json_encode($display_table, JSON_PRETTY_PRINT);

      //now echo it 
      echo $json;
    }
  }
}

//close the connection
mysqli_close($conn);