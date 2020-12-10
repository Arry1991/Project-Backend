<?php
/*This program will read the file, and
**get the information from the file. The
**information form file is store in the 2D
**array. Use the loop to get the value from
**array and store it in the database.
*/

//include the file to connect with mysql 
require_once 'mysqlConn.php';
	
$conn = new mysqli($hn, $un, $pw, $db);
	
//error message
if ($conn->connect_error) {
	echo "Connection failed";
}
	
//Create table if it does not exits
// the table is called users with three fields
$query = "CREATE TABLE IF NOT EXISTS patron (
  id int NOT NULL AUTO_INCREMENT,
  first_name VARCHAR(50) NOT NULL,
  last_name VARCHAR(50) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
	hash_password VARCHAR(255) NULL,
  PRIMARY KEY(id))";

//run the create query and if the 
//query fails send error message.
$result = $conn->query($query); 
if (!$result){
	echo "The query failed. /n";
}
//This 2D array which will hold value read from the file.
//Always define the array to prevent error.
$fileArray = [0 => ["","","",""]]; 
$row= 0; // this is for the outer part of the array 
	
$fh = fopen("../file/patron.txt", 'r'); //open the file and read form it. 

//Use the while to read till the end of the file 
while(!feof($fh)){
	
	$line = trim(fgets($fh)); // get every line 
	$array = explode(",", $line); //than splits the data and store it in the array

	//Use the for loop to store the data in 2D array
	for($i=0; $i < sizeof($array); ++$i){
		$fileArray[$row][$i] = $array[$i]; //store the data 
	} 
	$row++; //Increment here so it read as a row
}

fclose($fh); //make sure to close the file
//print_r ($fileArray);

//mysqli placeholder method with the use of the for loop.
//The loop will help with insert data into mysql database. 
for ($row=0; $row<sizeof($fileArray); ++$row){
		
	$stmt = $conn->prepare('INSERT INTO patron VALUES(?,?,?,?,?)');
		
	$stmt->bind_param('issss', $patron_id, $first_name, $last_name, $email, $hash_password);

	$patron_id = NULL;
	$first_name = $fileArray[$row][0];
	$last_name = $fileArray[$row][1];
	$email = $fileArray[$row][2];
	$hash_password = password_hash("abc123", PASSWORD_DEFAULT);
		
	$stmt->execute(); //execute
	//use the printf to check if the row is affect and display it with email
	printf("Row is affected %d with email {$email}", $stmt->affected_rows);
	echo "\n";
  }
  
  //close statement
  $stmt->close();
  $conn->close();

?>