<?php

require_once 'mysqlConn.php';

$conn = new mysqli($hn, $un, $pw, $db);

//error message
if ($conn->connect_error) {
  echo "Connection failed";
}

//Create table if it does not exits
$query = "CREATE TABLE IF NOT EXISTS notification (
  id INT NOT NULL AUTO_INCREMENT,
  business_id INT NOT NULL,
  patron_id INT NOT NULL,
  positive_Date VARCHAR(20) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(business_id) REFERENCES business (id),
  FOREIGN KEY(patron_id) REFERENCES Patron (id))";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}

//Create table if it does not exits
$query = "CREATE TABLE IF NOT EXISTS review (
  id INT NOT NULL AUTO_INCREMENT,
  business_id INT NOT NULL,
  patron_id INT NOT NULL,
  mask_rating INT NOT NULL,
  social_distance_rating INT NOT NULL,
  sanitize_rating INT NOT NULL,
  comment VARCHAR(200) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(business_id) REFERENCES business (id),
  FOREIGN KEY(patron_id) REFERENCES Patron (id))";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query);
if (!$result) {
  echo "The query failed. /n";
}