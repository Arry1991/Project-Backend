<?php
/*This file will modify owner info that 
**owner wants to modify.
*/

//start session
session_start();

//check if teh user is logged in
if (isset($_SESSION['owner_id'])) {

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';
  require_once '../function.php';

  //set the owner id
  $id = $_SESSION['owner_id'];

  //declare variable here
  $first_name = $last_name = $email = $oldPassword = $newPassword = "";

  $query = "SELECT * FROM business_owner where id = ?";
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

  //Post variable here and fill in the post variable name
  $first_name = htmlspecialchars($_POST['first_name']);
  $last_name = htmlspecialchars($_POST['last_name']);
  $email = htmlspecialchars($_POST['email']);
  $oldPassword = htmlspecialchars($_POST['oldPassword']);
  $newPassword = htmlspecialchars($_POST['newPassword']);

  echo $first_name . " " . $last_name . " " . $email . " " . $oldPassword . " " . $newPassword;
  
  //if any of the variable are empty set it to old one
  if ($first_name === "undefined" || empty($first_name)) {
    $first_name = $row['first_name'];
  }

  //check if the last is empty
  if ($last_name === "undefined" || empty($last_name)) {
    $last_name = $row['last_name'];
  }

  //check if the email is empty
  if ($email === "undefined" || empty($email)) {
    $email = $row['email'];
  }

  //check if the old password is set
  if ($oldPassword !== "undefined" && !empty($oldPassword)) {

    //this flag will check if the password
    //is empty so if has different update
    if ($newPassword !== "undefined" && !empty($newPassword)) {
      //now check if the old password verify
      if (password_verify($oldPassword, $row['hash_password'])) {
        $flag = true;
      } else {
        //if password did not match
        $flag = false;
        echo "Password did not match";
        die(http_response_code(401));
      }
    } else {
      //if the new password is not set
      $flag = false;
      echo "New password was not enter";
      die(http_response_code(401));
    }
  } else {
    //if the old password is not enter 
    $flag = false;
  }

  //if the use want to change the password 
  //and the password match
  if ($flag === true) {
    //update the password
    $query = "UPDATE business_owner SET first_name = ?, last_name = ?, email = ?, hash_password = ?
   where id = ?";
  } else {
    //if they do not want to change the password
    $query = "UPDATE business_owner SET first_name = ?, last_name = ?, email = ? where id = ?";
  }

  $stmt = mysqli_stmt_init($conn);

  //if the query failed
  if (!mysqli_stmt_prepare($stmt, $query)) {
    die("Fatal error the owner query failed");
  } else {

    //if the password is confirm
    if ($flag === true) {
      //hash the new password 
      $hash_password = password_hash($newPassword, PASSWORD_DEFAULT);

      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $last_name, $email, $hash_password, $id);
    }else {
      //bind the variable to prepare the statement
      mysqli_stmt_bind_param($stmt, "sssi", $first_name, $last_name, $email, $id);
    }


    //Check if the statement got executed
    if (mysqli_stmt_execute($stmt)) {
      //send successful message
      echo "Successful";
    } else {
      //this might be if the email is taken
      echo "Email is taken";
      die(http_response_code(401));
    }

    //free the memory
    mysqli_stmt_free_result($stmt);

    //close the statement
    mysqli_stmt_close($stmt);
  }

  //close the connection
  mysqli_close($conn);
} else {
  die("Please login");
}
