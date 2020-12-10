<?php
/*This file will register the owner and business.
**The file check if the owner email is unique and 
**than register the user. if the email is taken send 
**an error message. We are going to use prepare statement
**because it will help prevent sql injection.
*/


//Make sure the user got to this page by hitting the 
//submit button and not by typing the description.
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //Assign everything empty string
  $first_name = $last_name = $owner_email = $password = $business_name = $business_type = $business_email = $business_phone = $description = $street = $town = $zip  = $county = $alert = "";

  //include the file to connect with mysql 
  require_once '../mysqlConn.php';
  require_once '../function.php';

  //This will go in the owner table
  $first_name = htmlspecialchars($_POST['firstName']);
  $last_name = htmlspecialchars($_POST['lastName']);
  $owner_email = htmlspecialchars($_POST['ownerEmail']); //check
  $password = htmlspecialchars($_POST['password']); //check

  //This will go in the business table
  $business_name = htmlspecialchars($_POST['businessName']);
  $business_type = htmlspecialchars($_POST['businessType']);
  $business_email = htmlspecialchars($_POST['businessEmail']); //check
  $business_phone = htmlspecialchars($_POST['phone']);
  $description = htmlspecialchars($_POST['url']); //make sure to change to description
  $street = htmlspecialchars($_POST['street']); //check
  $town = htmlspecialchars($_POST['town']);
  $zip  = htmlspecialchars($_POST['zip']);
  $county = htmlspecialchars($_POST['county']);
  $alert = htmlspecialchars($_POST['alert']);

  //If any variable are empty send an error message. 
  if (empty($first_name) || empty($last_name) || empty($owner_email) || empty($password) || empty($business_name) || empty($business_type) || empty($business_email) || empty($business_phone) || empty($description) || empty($street) || empty($town) || empty($zip) || empty($county) || empty($alert)) {

    //send error message
    die("Please enter all the value");
  }

  //Check if the email exits in the database.
  $query = "SELECT * FROM business_owner WHERE email =?";
  $stmt = mysqli_stmt_init($conn); //prepare statement

  //Check if the query run
  if (!mysqli_stmt_prepare($stmt, $query)) {
    //End the program if the query does not run
    die("Fatal Error for the owner query to check if the email is taken");
  } else {

    //Provide the the statement to bind, provide the type of variable and the variable itself.
    mysqli_stmt_bind_param($stmt, "s", $owner_email);

    //execute the data provide by the user and the sql stamens.
    mysqli_stmt_execute($stmt);

    //This take the value from the database and store it in the stmt variable.  
    mysqli_stmt_store_result($stmt); //This is fetching data from the database

    //Number of result or rows.
    $row = mysqli_stmt_num_rows($stmt);

    //check if the email exits by number of row.
    //So if one row is effect that mean email exits.
    if ($row > 0) {
      //Display error
      die("Email is taken");
    } else {

      //use the place holder to add the data into the Owner table
      //Placeholder method to store the data into the table
      $query = "INSERT INTO business_owner (first_name, last_name, email, hash_password) VALUES(?,?,?,?)";
      $stmt = mysqli_stmt_init($conn); //prepare statement

      //if the query fails than run this statement
      if (!mysqli_stmt_prepare($stmt, $query)) {
        die("Fatal error for insert owner query");
      } else {
        //hast the password using the default which is the BCRYPT hash password function
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //Provide the the statement to bind, provide the type of variable and the variable itself.
        mysqli_stmt_bind_param($stmt, "ssss", $first_name, $last_name, $owner_email, $hash);

        //Check if the data got executed
        if (mysqli_stmt_execute($stmt)) {

          //get the id from this last executed inset query 
          $owner_id = mysqli_insert_id($conn);

          //change the alert to number
          if($alert === "false"){
            //send message automatically
            $alert = 0;
          }else if ($alert === "true"){
            //send message manually
            $alert = 1;
          }
          //Insert value into the business table
          $query = "INSERT INTO business(owner_id, name, type, email, phone, description, street, town, zip, county, alert) VALUES(?,?,?,?,?,?,?,?,?,?,?)";
          $stmt = mysqli_stmt_init($conn);

          //check if there is error in the previous query
          if (!mysqli_stmt_prepare($stmt, $query)) {

            //display an error message
            die("Fatal error for the business query");
          } else {

            //Provide the the statement to bind, provide the type of variable and the variable itself.
            mysqli_stmt_bind_param($stmt, "isssssssssi", $owner_id, $business_name, $business_type, $business_email, $business_phone, $description, $street, $town, $zip, $county, $alert);

            //Now check if this query executed
            if (mysqli_stmt_execute($stmt)) {

              //successfully register
              echo "Successfully Register";
              var_dump(http_response_code(200));
            } else {

              //So delete the owner data
              $query = "DELETE FROM business_owner WHERE id = '$owner_id'";
              $stmt = mysqli_stmt_prepare($conn, $query);

              //execute the delete statement
              mysqli_stmt_execute($stmt);

              //Data enter into query was not correct
              echo "Something was not enter in correct format for the business";
            }
          }
        } else {

          //Data pass into the owner query was not correct
          echo "Something was not enter in correct format for the owner";
        }

        //free the memory
        mysqli_stmt_free_result($stmt);

        //close the statement
        mysqli_stmt_close($stmt);
      }
    }
  }

  //close the connection 
  mysqli_close($conn);
} else {

  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
