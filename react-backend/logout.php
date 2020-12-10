<?php 
/*This file will logout the user if the
**user is logged-in. If the user is not 
**logged-in send error message 
*/

//????
require_once 'function.php';

session_start();

if (isset($_SESSION['owner_id'])){
  session_unset();
  session_destroy();
}
else{
  die("Fatal error");
}
?>