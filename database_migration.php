<?php
/*
 * for create tables
 * */


/** Create User Table  */
// sql to create table
$sql = "CREATE TABLE IF NOT EXISTS  users (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(30) NOT NULL,
password VARCHAR(191) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table MyGuests created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

/** End User */

/** Create Session Table */
$sql = "CREATE TABLE IF NOT EXISTS  sessions (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
token VARCHAR(191) NOT NULL,
user_id INT(6),
valid_till TIMESTAMP,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table MyGuests created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
/** End Session */


//

/** Create transactions Table */

$sql = "CREATE TABLE IF NOT EXISTS  transactions (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(191) NOT NULL,
amount VARCHAR(191) NOT NULL,
description VARCHAR(250) NOT NULL,
ref_id VARCHAR(250) NOT NULL,
reg_no INT(10),
user_id INT(6),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table MyGuests created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

/** Create End  */


/** Create logs Table */

$sql = "CREATE TABLE IF NOT EXISTS  logs (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
title VARCHAR(191)  NULL,
payload LONGTEXT  NULL,
user_id INT(6),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table MyGuests created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
/** Create End Table */



?>