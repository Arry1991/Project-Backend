<?php
/*This file has the variable for connecting
**with mysql database.
*/
$hn = 'localhost';
$un = 'root';
$pw = 'mysql';
$db = 'covid19';

//connect to the database
$conn = mysqli_connect($hn, $un, $pw, $db);

//check for connect error with the DB
//and send the error
if (!$conn) {
  die("Connection Failed: ". mysqli_connect_error());
}
