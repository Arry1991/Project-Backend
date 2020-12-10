<?php

require_once 'mysqlConn.php';

$conn = new mysqli($hn, $un, $pw, $db);

//error message
if ($conn->connect_error) {
  echo "Connection failed";
}

//Drop table if it does not exits
$query = "DROP TABLE IF EXISTS spreadsheet";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}

//Drop table if it does not exits
$query = "DROP TABLE IF EXISTS review";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}

//Drop table if it does not exits
$query = "DROP TABLE IF EXISTS notification";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}

//Drop table if it does not exits
$query = "DROP TABLE IF EXISTS patron";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}

//Drop table if it does not exits
$query = "DROP TABLE IF EXISTS business";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}

//Drop table if it does not exits
$query = "DROP TABLE IF EXISTS business_owner";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}