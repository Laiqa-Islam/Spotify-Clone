<?php

include "connection.php";
session_start();

// Checking Userlogin
if (!isset($_SESSION['name'])) {
  header("location: login.php");
}


// Selecting All The Genre
$genreS = "SELECT * FROM `category`";
$genreSresult = mysqli_query($conn, $genreS);


// Selecting All The Artists 
$artistS = "SELECT * FROM `artists`";
$artistSresult = mysqli_query($conn, $artistS);

// Adding Genres
if (isset($_POST['add-genre'])) {
  $genre_name = $_POST['genre'];
  $genreSql = "INSERT INTO `category`(`genre`) VALUES ('$genre_name')";
  $genreQuery = mysqli_query($conn, $genreSql);
  if ($genreQuery) {
    echo '
    <script>
        alert("Genre Inserted Successfully");
    </script>
    ';
  } else {
    echo '
    <script>
        alert("Something Went Wrong");
    </script>
    ';
  }
}


// Adding Artists
if (isset($_POST['add-artist'])) {
  $artist_name = $_POST['artist'];
  $artistSql = "INSERT INTO `artists`(`artists`) VALUES ('$artist_name')";
  $artistQuery = mysqli_query($conn, $artistSql);
  if ($artistQuery) {
    echo '
    <script>
        alert("Artist Inserted Successfully");
    </script>
    ';
  } else {
    echo '
    <script>
        alert("Something Went Wrong");
    </script>
    ';
  }
}

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
    $songSql = "INSERT INTO `songs`(`song_name`, `genre`, `artist`, `release_date`,`song_info`) VALUES ('$song','$genre','$artist','$release', '$fileName')";
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
      <div class="cat-art">
        <form method="post">
          <div class="category">
            <div><input type="text" placeholder="Enter Genre" name="genre"></div>
            <div><button name="add-genre" class="add-genre">Add Genre</button></div>
          </div>
        </form>
        <form method="post">
          <div class="artist category">
            <div><input type="text" name="artist" placeholder="Enter Artist"></div>
            <div><button name="add-artist" class="add-artist">Add Artist</button></div>
          </div>
        </form>

      </div>


      <div class="songs">
        <form method="post" enctype="multipart/form-data">
          <h4>Add Songs</h4>
          <div class="song-input">

            <div>
              <input type="text" name="song-name" placeholder="Enter Song Name">
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
              <input type="date" name="releaseDate" id="releaseDate">
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