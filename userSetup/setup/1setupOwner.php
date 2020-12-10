<?php
/*This program will set up owner table with 
**five fields. The data is store in file so 
**the program will read the file, and
**store the data into the database. The
**information form file is store in the 2D
**array. Then it will use the loop to get 
**the value from array and store it in the database.
*/

//include the file to connect with mysql 
require_once 'mysqlConn.php';
	
$conn = new mysqli($hn, $un, $pw, $db);
	
//error message
if ($conn->connect_error) {
	echo "Connection failed";
}
	
//Create table if it does not exits
//the table is called Business_owner 
//with five fields
$query = "CREATE TABLE IF NOT EXISTS business_owner(
	id int NOT NULL AUTO_INCREMENT,
	first_name VARCHAR(50) NOT NULL,
	last_name VARCHAR(50) NOT NULL,
	email VARCHAR(100) NOT NULL UNIQUE,
	hash_password VARCHAR(255) NOT NULL,
	PRIMARY KEY(id))";

//run the query and check if the
//query fails so send error message.
$result = $conn->query($query); 
if (!$result){
	echo "The query failed. /n";
}

//This 2D array which will hold value read from the file.
//Always define the array to prevent error.
$fileArray = [0 => ["","","",""]]; 
$row= 0; // this is for the outer part of the array 

$fh = fopen("../file/owner.txt", 'r'); //open the file and read form it. 

if($fh){
//Use the while loop to read till the end of the file 
while(!feof($fh)){
	
	$line = trim(fgets($fh)); // get every line and trim the appending whitespace
	$array = explode(",", $line); //than splits the data and store it in the array
		
	//Use the for loop to store the data in 2D array
	for($i=0; $i < sizeof($array); ++$i){
		$fileArray[$row][$i] = $array[$i]; //store the data 
	} 
	++$row; //Increment here so it read as a row
}
}else{
	echo "something went wrong";
}

fclose($fh); //make sure to close the file
//print_r ($fileArray);

//mysqli placeholder method with the use of the for loop.
//The loop will help with insert data into mysql database. 
for ($row=0; $row<sizeof($fileArray); ++$row){
		
	$stmt = $conn->prepare('INSERT INTO business_owner VALUES(?,?,?,?,?)');
		
	$stmt->bind_param('issss', $id, $first_name, $last_name, $email, $hash_password);

	$id = NULL;
	$first_name = $fileArray[$row][0];
	$last_name = $fileArray[$row][1];
	$email = $fileArray[$row][2];
	$hash_password = password_hash("abc123", PASSWORD_DEFAULT);
		
	$stmt->execute(); //execute
	//use the printf to check if the row is affect and display the email. 
	printf("Row is affected %d with email {$email}. \n", $stmt->affected_rows);
	echo "\n";
  }
  
  //close statement
  $stmt->close();
  $conn->close();

?>