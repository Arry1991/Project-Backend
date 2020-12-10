<?php
/*This file will modify business info that 
**user want to modify.
*/

//start session
session_start();

//check if the user is logged in
if (isset($_SESSION['owner_id'])) {

  //check if the user selected a business
  if (isset($_SESSION['business_id'])) {
    //include the file to connect with mysql 
    require_once '../mysqlConn.php';
    require_once '../function.php';

    //set the business id
    $id = $_SESSION['business_id'];

    $query = "SELECT * FROM business where id = ?";
    $stmt  = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $query)) {
      die("Fatal error with the query");
    } else {
      //actually running the query
      mysqli_stmt_bind_param($stmt, "i", $id);

      //execute the 
      mysqli_stmt_execute($stmt);

      //get result
      $result = mysqli_stmt_get_result($stmt);

      $row = mysqli_fetch_assoc($result);
    }
    //declare variable for business
    $name = $type = $email = $phone = $description = $street = $town = $zip  = $county = "";

    //Post variable here and fill in the post variable name
    $name = htmlspecialchars($_POST['name']);
    $type = htmlspecialchars($_POST['type']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $description = htmlspecialchars($_POST['description']);
    $street = htmlspecialchars($_POST['street']);
    $town = htmlspecialchars($_POST['town']);
    $zip  = htmlspecialchars($_POST['zip']);
    $county = htmlspecialchars($_POST['county']);
    $alert = htmlspecialchars($_POST['alert']);

    //display all of the post variable
    echo $name . " " . $type . " " . $email . " " . $phone . " " . $description . " " . $street . " " . $town . " " . $zip  . " " . $county . " " . " " . $alert;

    //If any variable is not change then keep the default one
    if ($name === "undefined" || empty($name)){
      //business name
      $name = $row['name'];
    }

    //business type
    if ($type === "undefined" || empty($type)){
      $type = $row['type'];
    }

    //business email
    if ($email === "undefined" || empty($email)){
      $email = $row['email'];
    }
    
    //business phone number
    if ($phone=== "undefined" || empty($phone)){
      $phone = $row['phone'];
    }

    //business description
    if ($description === "undefined" || empty($description)){
      $description = $row['description'];
    }

    //business address
    if ($street === "undefined" || empty($street)){
      //business street
      $street = $row['street'];
    }

    //business town
    if ($town === "undefined" || empty($town)){
      $town = $row['town'];
    }
    
    //business zip
    if ($zip === "undefined" || empty($zip)){
      $zip = $row['zip'];
    }

    //county
    if ($county === "undefined" || empty($county)){
      $county = $row['county'];
    }

    //alert
    if ($alert === "undefined" || empty($alert)){
      $alert = $row['alert'];
    }else{
      //now we have to change that boolean 
      //variable into 1 for true and 0 for false

      //so if the want to send email automatically
      //then use 0
      if($alert === "false"){
        $alert = 0;
      }else if ($alert === "true"){
        $alert = 1;
      }else{
        die("it was not true nor false");
      }
    }
    //update the business the table
    $query = "UPDATE business SET name = ?, type = ?, email = ?, phone = ? , description = ?, street = ?, town = ?, zip = ?, county = ?, alert = ? where id = ?";
    $stmt = mysqli_stmt_init($conn);

    //if the query failed
    if (!mysqli_stmt_prepare($stmt, $query)) {
      die("Fatal error the business query failed");
    } else {
      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "sssssssssii", $name, $type, $email, $phone, $description, $street, $town, $zip, $county, $alert, $id);

      //check if the statement executed
      if (mysqli_stmt_execute($stmt)) {
        echo "Edit Complete";
      } else {
        echo "Something was not enter right";
        die(http_response_code(401));
      }


      //free the memory
      mysqli_stmt_free_result($stmt);

      //close the statement
      mysqli_stmt_close($stmt);

      //send 200 message
      var_dump(http_response_code(200));
    }

    //close the connection
    mysqli_close($conn);
  } else {
    die("Select a business");
  }
} else {
  die("Please login");
}
