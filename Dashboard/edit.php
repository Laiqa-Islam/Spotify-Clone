<?php

include "connection.php";
session_start();

// Checking Userlogin
if (!isset($_SESSION['name'])) {
  header("location: login.php");
}


// Getting Id of the song to be Edited
$idEdit = $_GET['id'];
$sqlEdit = "SELECT * FROM `songs` WHERE id= '$idEdit'";
$resultEdit = mysqli_query($conn, $sqlEdit);
$rowEdit = mysqli_fetch_array($resultEdit);


// Selecting All The Genre
$genreS = "SELECT * FROM `category`";
$genreSresult = mysqli_query($conn, $genreS);


// Selecting All The Artists 
$artistS = "SELECT * FROM `artists`";
$artistSresult = mysqli_query($conn, $artistS);

// Adding Songs
if (isset($_POST['add-song'])) {
  $song = $_POST['song-name'];
  $artist = $_POST['artistID'];
  $genre = $_POST['genreID'];
  $release = $_POST['releaseDate'];
  $fileName = $_FILES['songFile']['name'];
  $tmp = $_FILES['songFile']['tmp_name'];

  if (empty($song) || empty($artist) || empty($genre) || empty($release) || empty($fileName)) {
    echo '<script>alert("Please fill out all fields.");</script>';
  } else {
    $songSql = "UPDATE `songs` SET `song_name`='$song',`genre`='$genre',`artist`='$artist',`release_date`='$release',`song_info`='$fileName' WHERE id ='$idEdit'";
    $songQuery = mysqli_query($conn, $songSql);

    // Adding Song File To External Directory
    if ($songQuery) {
      if (move_uploaded_file($tmp, 'SongFiles/' . $fileName)) {
        echo '<script>alert("File uploaded successfully.");</script>';
      } else {
        echo '<script>alert("File upload failed.");</script>';
        echo 'Path: ' . realpath('SongFiles/');
      }
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
  </nav>

  <main>
    <aside>
      <ul>
        <li><a class="navTags" href="index.php">Add Songs</a></li>
        <li><a class="navTags" href="add_admin.php">Add Admin</a></li>
        <li><a class="navTags" href="song_list.php">Songs Available</a></li>
      </ul>
    </aside>
    <section>

        <h1>Edit</h1>
      <div class="songs">
        <form method="post" enctype="multipart/form-data">
          <h4>Add Songs</h4>
          <div class="song-input">

            <div>
              <input type="text" name="song-name" value="<?=$rowEdit[1]?>" placeholder="Enter Song Name">
            </div>

            <div>
              <select name="genreID" id="">
                <option value="">Select Genre</option>

                <?php
                while ($row = mysqli_fetch_array($genreSresult)) {

                ?>
                  <option value="<?= $row[0]; ?>"><?= $row[1]; ?></option>

                <?php
                }
                ?>
              </select>

              <select name="artistID" id="">
                <option value="">Select Artist</option>

                <?php
                while ($row = mysqli_fetch_array($artistSresult)) {

                ?>
                  <option value="<?= $row[0]; ?>"><?= $row[1]; ?></option>

                <?php
                }
                ?>
              </select>
            </div>

            <div>
              <label for="releaseDate">Release Date</label><br>
              <input type="date" name="releaseDate" id="releaseDate" value="<?=$rowEdit[4]?>">
            </div>
            <div>
              <label for="songFile">Song File</label><br>
              <input type="file" name="songFile" id="songFile">
            </div>


          </div>
          <div>
            <button name="add-song" class="add-song">Add Song</button>
          </div>
        </form>
      </div>
    </section>

  </main>

  <script src="script.js"></script>
</body>

</html>