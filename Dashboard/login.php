<?php

include "connection.php";
session_start();


if (isset($_POST['logCheck'])) {
  $username = $_POST['logName'];
  $password = $_POST['logPassword'];
  $sql = "SELECT * FROM `admin` WHERE (adminName=? or email=?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $username, $username);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['adminpassword'])) {
      $_SESSION['name'] = $row['adminName'];
      header("location:index.php");
      exit;
    } else {
      echo '<script>
        alert("Wrong email or password");
      </script>';
    }
  } else {
    echo '<script>
      alert("Wrong email or password");
    </script>';
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap");
  </style>
  <style>
    * {
      margin: 0;
      padding: 0;
      font-family: "Poppins", "san-serif";
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      width: 100%;
    }

    body {
      background-color: #191414;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    body>form>div {
      padding: 2vw 1vw;
      height: 50vh;
      width: 25vw;
      background-color: rgb(24, 23, 23);
      border: 1px solid white;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      border-radius: 10px;
    }

    button {
      background-color: #22bb57;
      border: none;
      padding: 0.3vw 1vw;
      border-radius: 15px;
    }

    button:hover {
      background-color: #3dc06b;
      cursor: pointer;
    }

    input {
      color-scheme: dark;
      background-color: black;
      width: 90%;
      border: none;
      color: white;
      padding: 0.5vw 1vw;
      border-radius: 15px;
      border: 1px solid rgba(255, 255, 255, 0.521);
    }

    .heading {
      display: flex;
      /* justify-content: ; */
      color: black;
      align-items: center;
      height: 40px;
      width: 90%;
    }

    h5 {
      font-weight: 400;
    }

    .invert {
      filter: invert(1);
    }
  </style>
</head>

<body>
  <form action="" method="post">
    <div>
      <div class="heading invert">
        <img src="iconmonstr-spotify-1.svg" alt=""> <span>Spotify</span>
      </div>
      <h5>Admin Dashboard</h5><br>
      <input
        type="text"
        placeholder="Enter username or email..."
        name="logName" /><br />

      <input
        type="password"
        name="logPassword"
        id=""
        placeholder="Enter password..." /><br /><br />

      <button type="submit" name="logCheck">Log in</button>
    </div>
  </form>
</body>

</html>