<?php
$host = "localhost"; // hosten for databaseforbindelsen, som vanligvis er "localhost
$bruker = "root"; // brukernavnet for databaseforbindelsen, som vanligvis er "root" for lokale utviklingsmiljøer
$passord = ""; // passordet for databaseforbindelsen, som ofte er tomt for lokale utviklingsmiljøer
$database = "mgp"; // navnet på databasen som skal brukes, i dette tilfellet "mgp" som inneholder tabellene for MGP-applikasjonen

$conn = new mysqli($host, $bruker, $passord, $database); // oppretter en ny MySQLi-forbindelse til databasen ved hjelp av de angitte tilkoblingsparametrene (host, bruker, passord og database)

if ($conn->connect_error) {
    die("Tilkobling feilet: " . $conn->connect_error);
}
?>