<?php
    $servername = "localhost"; // Servername, auf dem die Datenbank ist.
    $username = "root"; // Benutzername für Datenbankzugriff
    $password = ""; // Passwort für Datenbankzugriff

    try{
        $conn = new PDO("mysql:host=$servername;dbname=galerie",$username, $password);
        // Eine Verbindung zur Datenbank wird hergestellt
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e) {
        // Bei einem Fehler wird eine Fehlermeldung ausgegeben
        echo "Connection failed: ". $e->getMessage();
    }
?> 