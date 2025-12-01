<?php
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'db2419804';

    $conn = new mysqli($host, $user, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>