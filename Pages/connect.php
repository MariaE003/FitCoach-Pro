<?php
$host="localhost";
$dbname="fitcoach";
$username="root";
$password="";

$connect=mysqli_connect($host,$username,$password,$dbname);

if (!$connect){
    die("la connexion non reussite !".mysqli_connect_error());
}
// else{
// echo "done";
// }
?>