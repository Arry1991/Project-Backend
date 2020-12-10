<?php
/*This file will check which patron were presented
**at the business location base on the given date.
**Then look for the email of the patron. 
*/

//Check if the user have click the post method button
if ($_SERVER["REQUEST_METHOD"] === "POST") {

  //Start session
  session_start();

  //check if user is logged-in
  if (isset($_SESSION['owner_id'])) {

    //check which business is selected
    if (isset($_SESSION['business_id'])) {
      //include the file to connect with mysql 
      require_once 'email.php';
      require_once 'mysqlConn.php';
      require_once 'function.php';

      //declare the variable
      $date = "";

      //Post variables 
      $date = htmlspecialchars($_POST['dateOfCase']);
      echo $date;
      //$subject = htmlentities($_POST['subject']);
      //$message =htmlentities($_POST['message']);

      //check if the date is empty
      if (empty($date)) {
        //make sure the user enter the value
        die("Make sure all the value are enter");
      } else {
        //store the session value
        $business_id = $_SESSION['business_id'];

        //This query will help us get the patron id form the
        //spreadsheet. Which will help us get the email of the
        //patron form the patron table.
        $spreadsheet_query = "SELECT * FROM spreadsheet where business_id = ? and sheet_date = ?";
        $spreadsheet_stmt = mysqli_stmt_init($conn);

        //if the query does not run
        if (!mysqli_stmt_prepare($spreadsheet_stmt, $spreadsheet_query)) {
          die("Fatal error the spreadsheet select query did not run");
        } else {

          //bind the pass value
          mysqli_stmt_bind_param($spreadsheet_stmt, "is", $business_id, $date);

          //execute the statement
          mysqli_stmt_execute($spreadsheet_stmt);

          //get the result 
          $result = mysqli_stmt_get_result($spreadsheet_stmt);

          //if no data is fetch that mean there is 
          //no data available for that date
          if (!$result) {
            //you might have to change the errorrrrr
            die("No data for that date ");
          } else {
            //this array will store the patron id
            $patron_id_array = array();

            //help us iterate in the while loop
            $i = 0;

            //put the fetch result in the row array.
            //use the while loop to read till the data is empty
            while ($row = mysqli_fetch_assoc($result)) {

              //store the patron id
              $patron_id_array[$i] = $row['patron_id'];

              //increment
              $i++;
            }

            //for some reason if the something went wrong
            if (empty($patron_id_array)) {
              die("Fatal error with array");
            }

            //now make the array have unique value so there
            //is only one id of the patron
            $unique_patron_id = array_unique($patron_id_array);

            //sort the array so it will make it 
            //easier on the while loop
            sort($unique_patron_id);
          }

          //free the memory
          mysqli_stmt_free_result($spreadsheet_stmt);

          //close the statement
          mysqli_stmt_close($spreadsheet_stmt);

          //I think we right the select first than bind the parameter in the loop.
          $patron_query = "SELECT * FROM patron where id = ?";
          $patron_stmt = mysqli_stmt_init($conn);

          //if the prepare statement fail
          if (!mysqli_stmt_prepare($patron_stmt, $patron_query)) {
            die("Fatal error the patron query failed 118");
          } else {

            //just for the sake of it store the patron email
            //in a array.
            $patron_email_array = array();

            //use the for loop to look inside the patron table
            //and return the email of the patron
            for ($i = 0; $i < sizeof($unique_patron_id); $i++) {

              //bind the statement
              mysqli_stmt_bind_param($patron_stmt, "i", $unique_patron_id[$i]);

              //excite the statement
              mysqli_stmt_execute($patron_stmt);

              //get the result for the prepare statement
              $result = mysqli_stmt_get_result($patron_stmt);

              //fetch rows
              $row = mysqli_fetch_assoc($result);

              //put it inside the array
              $patron_email_array[$i] = $row['email'];

              //echo it just for the sack of it
              //echo $unique_patron_id[$i] . " " . $patron_email_array[$i] . "\n";
              
              //email setting
              $mail->setFrom('phpseniorproject@gmail.com', 'Email Test');
              $mail->addAddress($row['email']);               // Name is optional
            }

            //the subject and email
            $mail->Subject = 'Website subject';
            $mail->Body    = 'This is website test';

            //send the mail
            $mail->send();
            
            //encode json file
            //$json = json_encode($patron_email_array, JSON_PRETTY_PRINT);

            //send mail is going inside the loop, the first par is $row['email'], the send one is $subject, and the third one is email
           
            //free the memory
            mysqli_stmt_free_result($patron_stmt);

            //close the statement
            mysqli_stmt_close($patron_stmt);
          }
        }
      }

      //close the connection
      mysqli_close($conn);
    } else {
      die("Select a business");
    }
  } else {
    //if they are not logged-in
    die("You must login");
  }
} else {
  //send error if the user try to get inside
  //the website without clicking the button
  die(http_response_code(404));
}
