<?php

include "connection.php";
session_start();

// Checking User Login
if (!isset($_SESSION['name'])) {
    header("location: login.php");
    exit();
}

// Selecting All Artists
$artistS = "SELECT * FROM `artists`";
$artistSresult = mysqli_query($conn, $artistS);




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song List</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
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
        <div class="search">
            <form method="get">
                <input name="search_text" type="text" placeholder="What song do you wanna find?">
                <button class="search_btn" name="search_btn"><i class="fa-solid fa-magnifying-glass"></i></button>
            </form>
        </div>
        <div class="profile">
            <div class="profile-image">
                <?= htmlspecialchars($_SESSION['name']); ?>
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

        <section class="songs_select">
            <form method="get">
                <select name="artistID" id="searchByArtist" onchange="this.form.submit()">
                    <option value="">Sort By Artist</option>
                    <?php
                    while ($row = mysqli_fetch_array($artistSresult)) {
                        $selected = (isset($_GET['artistID']) && $_GET['artistID'] == $row[0]) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($row[0]) . "' $selected>" . htmlspecialchars($row[1]) . "</option>";
                    }
                    ?>
                </select>

                <button class="All_Songs" name="all_songs">All Songs</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Song Name</th>
                        <th>Artist</th>
                        <th>Genre</th>
                        <th>Release Date</th>
                        <th>Delete</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (isset($_GET['artistID']) && !empty($_GET['artistID'])) {
                        // Filter songs based on selected artist
                        $artistName = $_GET['artistID'];
                        $stmt = $conn->prepare("SELECT songs.id, songs.song_name, artists.artists AS artist_name, category.genre AS genre_name, songs.release_date 
                                                FROM `songs`
                                                INNER JOIN `category` ON songs.genre = category.id
                                                INNER JOIN `artists` ON songs.artist = artists.id
                                                WHERE artists.id = ?");
                        $stmt->bind_param("i", $artistName);
                        $stmt->execute();
                        $result = $stmt->get_result();


                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['song_name']) . "</td>
                                    <td><strong>" . htmlspecialchars($row['artist_name']) . "</strong></td>
                                    <td>" . htmlspecialchars($row['genre_name']) . "</td>
                                    <td>" . htmlspecialchars($row['release_date']) . "</td>
                                    <td><a href='delete.php?id=" . htmlspecialchars($row['id']) . "'><i class='fa fa-trash'></i></a></td>
                                    <td><a href='edit.php?id=" . htmlspecialchars($row['id']) . "'><i class='fa fa-edit'></i></a></td>
                                  </tr>";
                        }   // search bar
                    } elseif(isset($_GET['search_btn'])) {
                        $search = $_GET['search_text'];
                        $search = mysqli_real_escape_string($conn, $search);
                        $search = "%$search%";
                        $searchQuery = "SELECT songs.id, songs.song_name, artists.artists AS artist_name, category.genre AS genre_name, songs.release_date 
                FROM `songs`
                INNER JOIN `category` ON songs.genre = category.id
                INNER JOIN `artists` ON songs.artist = artists.id
                WHERE songs.song_name LIKE ? OR category.genre LIKE ? OR songs.id LIKE ? OR artists.artists LIKE ?";
                        $stmt = $conn->prepare($searchQuery);
                        $searchLike = "%$search%";
                        $stmt->bind_param('ssss', $searchLike, $searchLike, $searchLike, $searchLike);
                        $stmt->execute();
                        $searchQueryResult = $stmt->get_result();

                        if ($searchQueryResult->num_rows > 0) {
                            while ($rowsearch = $searchQueryResult->fetch_assoc()) {
                                echo "<tr>
                            <td>" . htmlspecialchars($rowsearch['id']) . "</td>
                            <td>" . htmlspecialchars($rowsearch['song_name']) . "</td>
                            <td><strong>" . htmlspecialchars($rowsearch['artist_name']) . "</strong></td>
                            <td>" . htmlspecialchars($rowsearch['genre_name']) . "</td>
                            <td>" . htmlspecialchars($rowsearch['release_date']) . "</td>
                            <td><a href='delete.php?id=" . htmlspecialchars($rowsearch['id']) . "'><i class='fa fa-trash'></i></a></td>
                            <td><a href='edit.php?id=" . htmlspecialchars($rowsearch['id']) . "'><i class='fa fa-edit'></i></a></td>
                          </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>No results found</td></tr>";
                        }
                    }elseif (isset($_GET['all_songs'])){
                        // Display all songs
                        $sql = "SELECT songs.id, songs.song_name, artists.artists AS artist_name, category.genre AS genre_name, songs.release_date 
                                FROM `songs`
                                INNER JOIN `category` ON songs.genre = category.id
                                INNER JOIN `artists` ON songs.artist = artists.id";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['song_name']) . "</td>
                                    <td>" . htmlspecialchars($row['artist_name']) . "</td>
                                    <td>" . htmlspecialchars($row['genre_name']) . "</td>
                                    <td>" . htmlspecialchars($row['release_date']) . "</td>
                                    <td><a href='delete.php?id=" . htmlspecialchars($row['id']) . "'><i class='fa fa-trash'></i></a></td>
                                    <td><a href='edit.php?id=" . htmlspecialchars($row['id']) . "'><i class='fa fa-edit'></i></a></td>
                                  </tr>";
                        }
                    } else {
                        // Display all songs if no artist is selected
                        $sql = "SELECT songs.id, songs.song_name, artists.artists AS artist_name, category.genre AS genre_name, songs.release_date 
                                FROM `songs`
                                INNER JOIN `category` ON songs.genre = category.id
                                INNER JOIN `artists` ON songs.artist = artists.id";
                        $result = mysqli_query($conn, $sql);

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['id']) . "</td>
                                    <td>" . htmlspecialchars($row['song_name']) . "</td>
                                    <td>" . htmlspecialchars($row['artist_name']) . "</td>
                                    <td>" . htmlspecialchars($row['genre_name']) . "</td>
                                    <td>" . htmlspecialchars($row['release_date']) . "</td>
                                    <td><a href='delete.php?id=" . htmlspecialchars($row['id']) . "'><i class='fa fa-trash'></i></a></td>
                                    <td><a href='edit.php?id=" . htmlspecialchars($row['id']) . "'><i class='fa fa-edit'></i></a></td>
                                  </tr>";
                        }
                    }



                    ?>
                </tbody>
            </table>
        </section>

        <script src="script.js"></script>
    </main>
</body>

</html>