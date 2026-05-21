<?php
require 'db.php'; // inkluderer databaseforbindelsen fra db.php



$antall = 1000; // Antall stemmer som skal legges inn
$antallLand = 10; // Vi har 10 land
$antallAar = 3; // Vi har 3 år (AarID 1, 2, 3)




for ($i = 0; $i < $antall; $i++) { // loop som kjører $antall ganger for å legge inn tilfeldige stemmer
    // Tilfeldig IP
    $ip = rand(1,255) . "." . rand(1,255) . "." . rand(1,255) . "." . rand(1,255); 
    


    // Tilfeldig land og år
    $landID = rand(1, $antallLand); // 1-10 for land
    $aarID = rand(1, $antallAar); //  2024, 2025, eller 2026



    // Lagre bruker
    $lagreBruker = $conn->prepare("INSERT INTO bruker (IPAdresse) VALUES (?)"); // forbereder SQL-spørringen for å lagre en ny bruker
    $lagreBruker->bind_param("s", $ip); // binder IP-adressen til SQL-spørringen
    $lagreBruker->execute(); // lagrer brukeren i databasen
    $brukerID = $conn->insert_id; // henter id-en til den nylig innlagte brukeren



    // Lagre stemme
    $lagreStemme = $conn->prepare("INSERT INTO stemme (BrukerID, LandID, AarID) VALUES (?, ?, ?)"); // forbereder SQL-spørringen for å lagre en stemme
    $lagreStemme->bind_param("iii", $brukerID, $landID, $aarID); // binder brukerID, landID og aarID til SQL-spørringen
    $lagreStemme->execute(); // lagrer stemmen i databasen
} 

echo "Ferdig! $antall tilfeldige stemmer er lagt inn."; // Skriver ut en melding når alle stemmene er lagt inn
?>