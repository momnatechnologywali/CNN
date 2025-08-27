<?php
$host = 'localhost';
$dbname = 'dbpwxbkw10gnnh';
$username = 'uoguylmp9pmy3';
$password = 'yv4ex1jxw2g1';
 
try {
    $conn = new mysqli($host, $username, $password, $dbname);
 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database connection error: " . $e->getMessage());
}
?>
