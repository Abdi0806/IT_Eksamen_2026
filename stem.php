<?php
require 'db.php'; // inkluderer databaseforbindelsen fra db.php
session_start(); // starter en session for å kunne bruke session-variabler hvis nødvendig

$melding = ""; // variabel for å lagre meldinger som skal vises til brukeren (f.eks. om de har stemt eller ikke)



// Hent alle land
$land = $conn->query("SELECT * FROM land"); // henter alle land fra land-tabellen for å vise i dropdown-menyen i stemmeskjemaet



// Når bruker stemmer
if ($_SERVER["REQUEST_METHOD"] == "POST") { // sjekker om formen er sendt inn via POST-metoden
    $ip = $_SERVER["REMOTE_ADDR"]; // henter IP-adressen til brukeren som stemmer
    $landID = $_POST["landID"]; // henter det valgte landID fra POST-dataen som sendes inn når brukeren stemmer
    $aarID = 1; // 2026



    // Sjekk om IP allerede har stemt
    $sjekk = $conn->prepare("SELECT COUNT(*) as antall FROM bruker b        
        JOIN stemme s ON b.BrukerID = s.BrukerID 
        WHERE b.IPAdresse = ? AND s.AarID = ?");    // forbereder SQL-spørringen for å sjekke om det allerede finnes en stemme i databasen for denne IP-adressen og det valgte året
    $sjekk->bind_param("si", $ip, $aarID);          // binder IP-adressen og årID til SQL-spørringen for å sjekke om denne IP-en allerede har stemt i det valgte året
    $sjekk->execute();                  // utfører SQL-spørringen   
    $resultat = $sjekk->get_result()->fetch_assoc(); // henter resultatet av spørringen som en assosiativ array, hvor "antall" vil inneholde antall stemmer som denne IP-en har avgitt for det valgte året



    if ($resultat["antall"] > 0) { // hvis antall stemmer for denne IP-en og året er større enn 0, betyr det at brukeren allerede har stemt, og vi setter meldingen til å informere om dette
        $melding = "Du har allerede stemt i år!"; // setter meldingen som skal vises til brukeren hvis de allerede har stemt
    } else { // hvis antall stemmer er 0, betyr det at brukeren ikke har stemt ennå, og vi går videre med å lagre stemmen deres
        // Lagre bruker
        $lagreBruker = $conn->prepare("INSERT INTO bruker (IPAdresse) VALUES (?)"); // forbereder SQL-spørringen for å lagre en ny bruker med deres IP-adresse
        $lagreBruker->bind_param("s", $ip); // binder IP-adressen til SQL-spørringen for å lagre brukeren i databasen
        $lagreBruker->execute(); // utfører SQL-spørringen for å lagre brukeren i databasen
        $brukerID = $conn->insert_id; //    henter id-en til den nylig innlagte brukeren, som skal brukes for å knytte stemmen til denne brukeren i stemme-tabellen



        // Lagre stemme
        $lagreStemme = $conn->prepare("INSERT INTO stemme (BrukerID, LandID, AarID) VALUES (?, ?, ?)"); // forbereder SQL-spørringen for å lagre en stemme i stemme-tabellen, hvor vi skal lagre brukerID, landID og aarID for denne stemmen
        $lagreStemme->bind_param("iii", $brukerID, $landID, $aarID); // binder brukerID, landID og aarID til SQL-spørringen for å lagre stemmen i databasen
        $lagreStemme->execute(); // utfører SQL-spørringen for å lagre stemmen i databasen

        $melding = "Takk for din stemme!"; // setter meldingen som skal vises til brukeren etter at de har stemt, for å takke dem for deres stemme
    }
}
?>




<!DOCTYPE html>
<html>
<head>
    <title>MGP Stemming</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container"> // container-div for å holde alt innholdet på siden, som kan styles med CSS
        <h1>Stem på MGP 2026</h1>

        <?php if ($melding): ?> // sjekker om det finnes en melding å vise (f.eks. om brukeren har stemt eller ikke), og hvis det gjør det, vises meldingen i et avsnitt
            <p><?= $melding ?></p> // viser meldingen til brukeren, som kan være enten "Du har allerede stemt i år!" eller "Takk for din stemme!" avhengig av om de har stemt tidligere eller ikke
        <?php endif; ?> // avslutter if-setningen for å sjekke om det finnes en melding å vise

        <form method="post"> // form som sender data via POST-metoden når brukeren stemmer
            <label>Velg land:</label> // label for dropdown-menyen hvor brukeren velger hvilket land de vil stemme på
                <select name="landID">
                    <?php while ($row = $land->fetch_assoc()): ?>
                        <option value="<?= $row["LandID"] ?>"><?= $row["LandNavn"] ?></option>
                    <?php endwhile; ?>
                </select>
                <br><br>
            <button type="submit">Stem!</button>
        </form>
    </div>
</body>
</html>