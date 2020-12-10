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
	
//Create table if it does not exits the 
//table is called business with seven fields
$query = "CREATE TABLE IF NOT EXISTS business(
  id int NOT NULL AUTO_INCREMENT,
  owner_id int NOT NULL,
  name VARCHAR(50) NOT NULL,
  type VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  description VARCHAR(250) NOT NULL,
  street VARCHAR(50) NOT NULL,
  town VARCHAR(50) NOT NULL,
  zip VARCHAR(5) NOT NULL,
  county VARCHAR(50) NOT NULL,
  alert BOOLEAN,
  PRIMARY KEY (id),
  FOREIGN KEY(owner_id) REFERENCES business_owner (id))";

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
	
$fh = fopen("../file/business.txt", 'r'); //open the file and read form it. 

//Use the while loop to read till the end of the file 
while(!feof($fh)){
	
	$line = trim(fgets($fh)); // get every line and trim the appending whitespace 
	$array = explode(",", $line); //than splits the data and store it in the array

	//Use the for loop to store the data in 2D array
	for($i=0; $i < sizeof($array); ++$i){
		$fileArray[$row][$i] = $array[$i]; //store the data 
	} 
	$row++; //Increment here so it read as a row
}

fclose($fh); //make sure to close the file

//You can uncomment to see what is being store
//in there associative array. 
//print_r ($fileArray);

//mysqli placeholder method with the use of the for loop.
//The loop will help with insert data into mysql database. 
for ($row=0; $row<sizeof($fileArray); ++$row){
		
	$stmt = $conn->prepare('INSERT INTO business VALUES(?,?,?,?,?,?,?,?,?,?,?,?)');
		
  $stmt->bind_param('iisssssssssi', $id, $owner_id, $business_name, $business_type, $business_email, $business_phone, $description, $street, $town, $zip, $county, $alert);
  
    $id = NULL;
	  $owner_id = $fileArray[$row][0];;
	  $business_name = $fileArray[$row][1];
	  $business_type = $fileArray[$row][2];
	  $business_email = $fileArray[$row][3];
    $business_phone = $fileArray[$row][4];
    $description = $fileArray[$row][5];
    $street = $fileArray[$row][6];
		$town = $fileArray[$row][7];
		$zip = $fileArray[$row][8];
    $county = $fileArray[$row][9];
    $alert = 0;
		
	$stmt->execute(); //execute

	//use the printf to check if the row is affect 
	printf("Row is affected %d with business name {$business_name}.\n", $stmt->affected_rows);
	echo "\n";
  }
  
  //close statement and the connection
  $stmt->close();
  $conn->close();
?>