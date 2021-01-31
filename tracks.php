<?php


require(__DIR__ . '/vendor/autoload.php');

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$id = $_GET['playlist'];
if ($id == "") {
    header('Location: index.php');
}


$pdo = new PDO($_ENV['PDO_CONNECTION_STRING']);

$sql = "SELECT playlists.name as playlist, playlist_track.track_id, tracks.name, albums.title AS album, artists.name AS artist, tracks.unit_price, genres.name AS genre FROM playlists
INNER JOIN playlist_track ON playlists.id = ? AND playlists.id = playlist_track.playlist_id
INNER JOIN tracks ON playlist_track.track_id = tracks.id
INNER JOIN albums ON tracks.album_id = albums.id
INNER JOIN artists ON albums.artist_id = artists.id
INNER JOIN genres ON tracks.genre_id = genres.id";

$statement = $pdo->prepare($sql);
$statement->execute([$id]);
$tracks = $statement->fetchAll(PDO::FETCH_OBJ);


$playlistQuery = "SELECT playlists.name FROM playlists WHERE playlists.id = ?";
$playlistStatement = $pdo->prepare($playlistQuery);
$playlistStatement->execute([$id]);
$playlist = $playlistStatement->fetch();

?>



<h1>Tracks in <?php echo $playlist[0] ?>:</h1>
<?php echo (count($tracks) == 0)  ? "No tracks found for {$playlist[0]}"  : "" ?>
<?php foreach ($tracks as $track) : ?>
    <h4><?php echo $track->name ?></h4>
    <ul>
        <li>Album: <?php echo $track->album ?></li>
        <li>Artist: <?php echo $track->artist ?></li>
        <li>Price: <?php echo $track->unit_price ?></li>
        <li>Genre: <?php echo $track->genre ?></li>
    </ul>
    <br />
<?php endforeach ?>