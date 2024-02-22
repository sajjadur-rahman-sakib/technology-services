<?php
session_start();
$username = $_SESSION['username'];

if (!isset($username)) {
    header('location:index.php');
}

if (isset($_GET['logout'])) {
    unset($username);
    session_destroy();
    header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="contaner">
        <div class="navbar item">
            <div class="blank"></div>
            <h1>Admin Panel</h1>
            <div class="logout">
                <a href="./admin.php?logout">Logout</a>
            </div>
        </div>
        <div class="content item">

            <a href="./servicelog.php" class="edit box"><h3>Service Log</h3></a>

            <a href="./alluser.php" class="edit box"><h3>Customer List</h3></a>

            <a href="./manageuser.php" class="approve box"><h3>Update List</h3></a>


        </div>
    </div>
</body>
</html>