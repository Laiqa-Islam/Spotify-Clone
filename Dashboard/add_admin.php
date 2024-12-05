<?php

include "connection.php";
session_start();

// Checking Userlogin
if (!isset($_SESSION['name'])) {
  header("location: login.php");
}


// Adding Admin Info
if(isset($_POST['addAdmin'])){
  $name = $_POST['adminName'];
  $email = $_POST['adminEmail'];
  $password = $_POST['adminPassword'];
  $confirm_password = $_POST['confirmPassword'];

  // Checking for password match
  if($password!==$confirm_password){
    echo "<script>
            alert('Passwords does not match');
          </script>";         
  } else{

    // Hashing Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO `admin`(`adminName`, `email`, `adminpassword`) VALUES ('$name','$email','$hashed_password')";
    $result = mysqli_query($conn, $query);
    if ($result) {
      echo "<script>
              alert('Admin added successfully');
            </script>";
  } else {
      echo "<script>
              alert('Error: " . mysqli_error($conn) . "');
            </script>";
  }

  }
}


?>




<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
  </style>
</head>

<body>
  <nav>
  <div class="logo">
      <div class="heading invert">
       <img src="iconmonstr-spotify-1.svg" alt=""> <span>Spotify</span>
      </div>
      <h5 style="font-weight: 400;">Dashboard</h5>
    </div>
    <div class="profile">
    <div class="profile-image">
        <?= $_SESSION['name']?>
      </div>
      <div class="profile-dropdown">
        <a href="logout.php">LogOut</a>
      </div>
      </div>
    </div>
  </nav>

  <main>
    <aside>
      <ul>
        <li><a href="index.php">Add Songs</a></li>
        <li><a href="add_admin.php">Add Admin</a></li>
        <li><a href="song_list.php">Songs Available</a></li>
      </ul>
    </aside>

    <section>
        <div class="adminPost">
            <form method="post">
                <h4>Add Admin</h4>
                <div>
                    <label for="name">Name:</label><br>
                <input type="text" placeholder="Enter Name..." name="adminName" id="name"><br><br>

                <label for="email">Email:</label><br>
                <input type="email" placeholder="Enter Email..." name="adminEmail" id="email"><br><br>

                <label for="password">Password:</label><br>
                <input type="password" name="adminPassword" id="password" placeholder="Enter Password..."><br><br>

                <label for="password">Confirm Password:</label><br>
                <input type="password" name="confirmPassword" id="password" placeholder="Enter Password..."><br><br>
                </div>

                <div>
                    <button name="addAdmin" type="submit">Add Admin</button>
                </div>
            </form>
        </div>
    </section>

    
  </main>
  <script src="script.js"></script>
</body>

</html>