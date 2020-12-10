<?php
/*This file will select a business and start a session for that business.
**It good to test if the street exits in the database before starting the session.
**Street is unique value so it will be easy to identify the business.
**Talk to your team if they want send data as json or post (most likely post).
**if using post make sure the user cannot edit the info
*/
header("Access-Control-Allow-Origin: *"); //see if you can remove the * star sign and add json application/json
$json = file_get_contents("php://input"); //you can remove this line and see what happen
$_POST = json_decode($json, true); //rue make as an associated array 

if ($_SERVER["REQUEST_METHOD"] === "POST") {

  //Start session
  session_start();

  //check if the user is logged-in
  if (isset($_SESSION['owner_id'])) {

    //include the file to connect with mysql 
    require_once '../mysqlConn.php';
    require_once '../function.php';

    //Unset the business session to 
    //start new session
    if (isset($_SESSION['business_id'])) {
      unset($_SESSION['business_id']);
    }

    //declare the variable
    $street = "";

    //Post variables 
    $street = htmlspecialchars($_POST['street']);

    //check if the post is empty
    if (empty($street)) {
      die("Fatal error, value was enter");
    } else {

      //check if the street exits
      $query = "SELECT * FROM business where street = ?";
      $stmt = mysqli_stmt_init($conn);

      //if the query does not run
      if (!mysqli_stmt_prepare($stmt, $query)) {
        die("Fatal error with query");
      } else {
        //prepare the statement by binding variable
        mysqli_stmt_bind_param($stmt, "s", $street);

        //execute the statement
        mysqli_stmt_execute($stmt);

        //get the result to check the data
        $result = mysqli_stmt_get_result($stmt);

        //now check if the street match is affected
        if ($row = mysqli_fetch_assoc($result)) {

          //now check if the street match
          if ($street === $row['street']) {

            //store the session 
            $_SESSION['business_id'] = $row['id'];

            //display successful message
            echo "You have selected";
          } else {

            //if the street did not match
            echo "Fatal error when matching the street";
          }
        } else {

          //if no data is fetch
          echo "No data was fetch";
        }
      }
    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);

    //close the connection
    mysqli_close($conn);
  } else {

    //if they are not logged-in
    die("You must login");
  }
} else {

  //send error if the user try to get inside
  //the website without clicking the button
  die(http_response_code(404));
}
