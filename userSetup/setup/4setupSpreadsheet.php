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
$query = "CREATE TABLE IF NOT EXISTS spreadsheet (
  spreadsheet_id int NOT NULL AUTO_INCREMENT,
  business_id int NOT NULL,
  patron_id int NOT NULL,
  temperature VARCHAR(20) NOT NULL,
  sheet_date VARCHAR(20) NOT NULL,
  PRIMARY KEY(spreadsheet_id),
  FOREIGN KEY(business_id) REFERENCES business (id),
  FOREIGN KEY(patron_id) REFERENCES Patron (id))";

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
	
$fh = fopen("../file/spreadsheet.txt", 'r'); //open the file and read form it. 

//Use the while to read till the end of the file 
while(!feof($fh)){
	
	$line = trim(fgets($fh)); // get every line 
	$array = explode(",", $line); //than split the data and store it in the array

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
		
	$stmt = $conn->prepare('INSERT INTO spreadsheet VALUES(?,?,?,?,?)');
		
	$stmt->bind_param('iiiss', $spreadsheet_id, $business_id, $patron_id, $temperature, $sheet_date);

	$spreadsheet_id = NULL;
	$business_id = $fileArray[$row][0];
	$patron_id = $fileArray[$row][1];
	$temperature = $fileArray[$row][2];
	$sheet_date = $fileArray[$row][3];
	
	$stmt->execute(); //execute
	//use the printf to check if the row is affect.
	printf("Row is affected %d \n", $stmt->affected_rows);
	echo "\n";
  }
  
  //close statement
  $stmt->close();
  $conn->close();

?>