<?php
require 'db.php';



// Hent alle år for dropdown
$aar = $conn->query("SELECT * FROM mgp_aar ORDER BY Aar DESC"); // henter alle år fra mgp_aar-tabellen for å vise i dropdown-menyen



// Valgt år (standard er 2024 selv om det er 2 år siden)
$valgtAar = isset($_GET["aar"]) ? $_GET["aar"] : 1; // sjekker om det er sendt inn et år via GET-parameter, hvis ikke settes det til 1 (som tilsvarer 2024)



// Hent resultater for valgt år
$resultater = $conn->prepare(" 
    SELECT l.LandNavn, COUNT(s.StemmeID) as AntallStemmer, 
    ROUND(COUNT(s.StemmeID) * 100.0 / (SELECT COUNT(*) FROM stemme WHERE AarID = ?), 1) as Prosent
    FROM land l
    LEFT JOIN stemme s ON l.LandID = s.LandID AND s.AarID = ?
    GROUP BY l.LandID, l.LandNavn
    ORDER BY AntallStemmer DESC
");
$resultater->bind_param("ii", $valgtAar, $valgtAar); // binder valgt år til begge spørsmålstegn i SQL-spørringen (for å filtrere både total stemmeantall og stemmer for hvert land)
$resultater->execute(); // utfører SQL-spørringen
$rows = $resultater->get_result(); // henter resultatet av spørringen som en result-sett som kan itereres over i HTML-tabellen
?>




<!DOCTYPE html>
<html>
<head>
    <title>MGP Resultater</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>MGP Stemmeresultater</h1>

        <!-- Dropdown for år -->
        <form method="get">
            <label>Velg år:</label>
            <select name="aar" onchange="this.form.submit()">
                <?php while ($row = $aar->fetch_assoc()): ?>
                    <option value="<?= $row["AarID"] ?>" <?= $row["AarID"] == $valgtAar ? "selected" : "" ?>>
                        <?= $row["Aar"] ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>


        <br>

        <!-- Resultattabell -->
        <table border="1" cellpadding="8">
            <tr> // tabelloverskrifter
                <th>Land</th> // kolonne for landets navn
                <th>Antall stemmer</th>
                <th>Prosent</th>
            </tr>
            <?php while ($row = $rows->fetch_assoc()): ?>
            <tr>
                <td><?= $row["LandNavn"] ?></td> // viser landets navn i første kolonne
                <td><?= $row["AntallStemmer"] ?></td> // viser antall stemmer for det landet i andre kolonne
                <td><?= $row["Prosent"] ?>%</td> // viser prosentandelen av stemmene for det landet i tredje kolonne, med % etter tallet
            </tr>
            <?php endwhile; ?>
        </table>

        <br>
        <a href="stem.php">Gå til stemming</a>
    </div>
</body>
</html>