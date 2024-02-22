<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

   $username = $_POST['username'];
   $pass = $_POST['password'];
   $sql="SELECT * FROM `admin` WHERE username = '$username' AND password = '$pass'";
   $select = mysqli_query($conn,$sql) or die('query failed');

   if(mysqli_num_rows($select) ==1){
      $row = mysqli_fetch_assoc($select);
      $_SESSION['username'] = $row['username'];
      header('location:admin.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <div class="container">

        <a href="" class="btnhome"></a>
        <div class="form">
            <h2>Admin Login</h2>
            <form action="" method="post">
                <div class="item">
                    <label for="email">
                        <h3>Email:</h3>
                    </label>
                    <input required type="username" name="username" placeholder="enter username...">
                </div>
                <div class="item">
                    <label for="">
                        <h3>Password:</h3>
                    </label>
                    <input required type="password" name="password" placeholder="enter password...">
                </div>
                <div class="btn">
                    <input type="submit" name="submit">
                </div>
                <?php
                     if(isset($message)){
                        foreach($message as $message){
                           echo '<div class="message" onclick="this.remove();">
                           <h3 style="color:red;">'.$message.'</h3></div>';
                        }
                     }
               ?>
            </form>
        </div>
    </div>

</body>

</html>