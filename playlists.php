<?php

// pgsql:host={host}:port={port}:dbname={dbname}:user={user}:password={password}


require(__DIR__ . '/vendor/autoload.php');

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}


$pdo = new PDO($_ENV['PDO_CONNECTION_STRING']);

//$sql="SELECT invoices.id, invoice_date, total, first_name, last_name FROM invoices INNER JOIN customers ON invoices.customer_id = customers.id";

$sql = "SELECT id, name FROM playlists";

$statement = $pdo->prepare($sql);

$statement->execute();
$playlists = $statement->fetchAll(PDO::FETCH_OBJ);

//var_dump($invoices);

?>

<h1>Select a Playlist:</h1>
<ul>
    <?php foreach ($playlists as $playlist) : ?>
        <li><a href="tracks.php?playlist=<?php echo $playlist->id ?>"><?php echo $playlist->name ?></a></li>
    <?php endforeach ?>
</ul>