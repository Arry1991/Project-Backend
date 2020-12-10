<?php
/*This file will register a new patron if they
**do not exits in the database. Then it will store 
**patron id, temp, and data  with corresponding
**business id inside the spreadsheet table.
*/

//This will check if the user click the post method submit button
if ($_SERVER['REQUEST_METHOD'] === "POST") {

  //start session
  session_start();

  //check if the user is logged-in
  if (isset($_SESSION['owner_id'])) {

    //check if the business is selected
    if (isset($_SESSION['business_id'])) {
      //include the file to connect with mysql 
      require_once '../mysqlConn.php';
      require_once '../function.php';

      //declare the variable
      $first_name = $last_name = $email = $temperature = $date = "";

      //Variable which will hold post value.
      //This variable are for patron
      $first_name = htmlspecialchars($_POST['firstName']);
      $last_name = htmlspecialchars($_POST['lastName']);
      $email = htmlspecialchars($_POST['email']);

      //These variable are for spreadsheet 
      $temperature = htmlspecialchars($_POST['temp']);
      $date = htmlspecialchars($_POST['date']);

      //if no data is entered
      if (empty($first_name) || empty($last_name) || empty($email) || empty($temperature) || empty($date)) {
        //display error if the value are empty
        die("Make sure all the values are enter");
      } else {

        //store the id in a variable
        $business_id = $_SESSION['business_id'];

        //Select all the field from the table and
        //run the query.
        $query = "SELECT * FROM patron where email = ?";
        $stmt  = mysqli_stmt_init($conn);

        //check if the query failed for the patron 
        if (!mysqli_stmt_prepare($stmt, $query)) {
          die("Fatal error the query did not run");
        } else {

          //bind the pass value by the user
          mysqli_stmt_bind_param($stmt, "s", $email);

          //execute the statement
          mysqli_stmt_execute($stmt);

          //get the result
          $result = mysqli_stmt_get_result($stmt);

          //if the patron is already register than go into
          //the if statement to store data into the spreadsheet
          if ($row = mysqli_fetch_assoc($result)) {

            //set the patron id
            $patron_id = $row['id'];

            //Insert into the spreadsheet 
            $spreadsheet_query = "INSERT INTO spreadsheet (business_id, patron_id, temperature, sheet_date) VALUES(?,?,?,?)";
            $spreadsheet_stmt = mysqli_stmt_init($conn);

            //check if the query failed for the spreadsheet
            if (!mysqli_stmt_prepare($spreadsheet_stmt, $spreadsheet_query)) {
              die("Fatal error for insert spreadsheet query");
            } else {

              //Provide the the statement to bind, provide the type of variable and the variable itself.
              mysqli_stmt_bind_param($spreadsheet_stmt, "iiss", $business_id, $patron_id, $temperature, $date);

              //execute the data provide by the user and the sql stamens.
              mysqli_stmt_execute($spreadsheet_stmt);

              //free the memory
              mysqli_stmt_free_result($spreadsheet_stmt);

              //close the statement
              mysqli_stmt_close($spreadsheet_stmt);

              //successful
              echo "Patron added";
              var_dump(http_response_code(200));
            }
          } else {

            //if the patron is not register then register the
            //patron and get the id to put inside the spreadsheet.
            $patron_query = "INSERT INTO patron (first_name, last_name, email) VALUES(?,?,?)";
            $patron_stmt = mysqli_stmt_init($conn);

            //check if the query failed for the patron and it will prepare the statement
            if (!mysqli_stmt_prepare($patron_stmt, $patron_query)) {
              die("Fatal error for insert patron query");
            } else {

              //combine it with the variable
              mysqli_stmt_bind_param($patron_stmt, "sss", $first_name, $last_name, $email);

              //execute the data provide by the user and the sql stamens.
              mysqli_stmt_execute($patron_stmt);

              //get the patron id 
              $patron_id = mysqli_insert_id($conn);

              //close the statement
              mysqli_stmt_close($patron_stmt);

              //now insert into the spreadsheet table
              $spreadsheet_query = "INSERT INTO spreadsheet (business_id, patron_id, temperature, sheet_date) VALUES(?,?,?,?)";
              $spreadsheet_stmt = mysqli_stmt_init($conn);

              //Check if the spreadsheet query failed
              if (!mysqli_stmt_prepare($spreadsheet_stmt, $spreadsheet_query)) {
                die("Fatal error for the spreadsheet insert query");
              } else {

                //Provide the the statement to bind, provide the type of variable and the variable itself.
                mysqli_stmt_bind_param($spreadsheet_stmt, "iiss", $business_id, $patron_id, $temperature, $date);

                //execute the data provide by the user and the sql stamens.
                mysqli_stmt_execute($spreadsheet_stmt);

                //free the memory
                mysqli_stmt_free_result($spreadsheet_stmt);

                //close the statement
                mysqli_stmt_close($spreadsheet_stmt);

                //successful
                echo "Add the info";
                var_dump(http_response_code(200));
              }
            }
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
      die("Select a business");
    }
  } else {
    //if they are not logged-in.
    die("You must be login");
  }
} else {
  //send error because user try to get inside the file without clicking on the submit button
  die(http_response_code(404));
}
