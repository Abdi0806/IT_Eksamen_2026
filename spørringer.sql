Dette er Querys. IKKE .SQL kode - jeg forstår ikke .sql kode enda

-- Hent alle land (brukes i stem.php)
SELECT * FROM land;

-- Hent alle år til dropdown (brukes i resultat.php)
SELECT * FROM mgp_aar ORDER BY Aar DESC;

-- Hent stemmeresultater med prosent for valgt år (resultat.php)
SELECT l.LandNavn,
       COUNT(s.StemmeID) as AntallStemmer,
       ROUND(
           COUNT(s.StemmeID) * 100.0 / 
           (SELECT COUNT(*) FROM stemme WHERE AarID = 1),
       1) as Prosent
FROM land l
LEFT JOIN stemme s 
    ON l.LandID = s.LandID AND s.AarID = 1
GROUP BY l.LandID, l.LandNavn
ORDER BY AntallStemmer DESC;

-- Samme spørring men med parameter (det du faktisk bruker)
SELECT l.LandNavn,
       COUNT(s.StemmeID) as AntallStemmer,
       ROUND(
           COUNT(s.StemmeID) * 100.0 / 
           (SELECT COUNT(*) FROM stemme WHERE AarID = ?),
       1) as Prosent
FROM land l
LEFT JOIN stemme s 
    ON l.LandID = s.LandID AND s.AarID = ?
GROUP BY l.LandID, l.LandNavn
ORDER BY AntallStemmer DESC;

-- Sjekk om IP allerede har stemt (stem.php)
SELECT COUNT(*) as antall
FROM bruker b
JOIN stemme s ON b.BrukerID = s.BrukerID
WHERE b.IPAdresse = '127.0.0.1'
AND s.AarID = 1;

-- Samme med parameter (det ekte)
SELECT COUNT(*) as antall
FROM bruker b
JOIN stemme s ON b.BrukerID = s.BrukerID
WHERE b.IPAdresse = ?
AND s.AarID = ?;

-- Legg inn bruker
INSERT INTO bruker (IPAdresse)
VALUES ('127.0.0.1');

-- Med parameter (det ekte)
INSERT INTO bruker (IPAdresse)
VALUES (?);

-- Legg inn stemme
INSERT INTO stemme (BrukerID, LandID, AarID)
VALUES (1, 1, 1);

-- Med parameter (det ekte)
INSERT INTO stemme (BrukerID, LandID, AarID)
VALUES (?, ?, ?);
``