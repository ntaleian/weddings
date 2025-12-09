<?php
// Test database connection and create database if needed
$servername = "localhost";
$username = "root";
$password = "root";
$port = 8889;

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, "", $port);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    echo "Connected successfully to MySQL server\n";
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS watoto_wedding";
    if ($conn->query($sql) === TRUE) {
        echo "Database watoto_wedding created successfully\n";
    } else {
        echo "Error creating database: " . $conn->error . "\n";
    }
    
    // Select the database
    $conn->select_db("watoto_wedding");
    
    // Show existing tables
    $result = $conn->query("SHOW TABLES");
    if ($result) {
        echo "Tables in watoto_wedding database:\n";
        while ($row = $result->fetch_array()) {
            echo "- " . $row[0] . "\n";
        }
        if ($result->num_rows == 0) {
            echo "No tables found - ready for migrations\n";
        }
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
