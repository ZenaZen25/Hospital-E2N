<?php
// 1. Includi la connessione al database (quella che abbiamo visto prima)
require_once "../utils/db_connect.php";
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 2. Recupero e pulizia base (trim toglie spazi inutili)
    $lastname  = trim($_POST["lastname"] ?? '');
    $firstname = trim($_POST["firstname"] ?? '');
    $birthdate = trim($_POST["birthdate"] ?? '');
    $phone     = trim($_POST["phone"] ?? '');
    $mail      = trim($_POST["mail"] ?? '');

    // 3. Validazione
    if (empty($lastname) || empty($firstname) || empty($birthdate) || empty($mail)) {
        $errors[] = "Tutti i campi obbligatori devono essere compilati.";
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Formato email non valido.";
    }

    // 4. Se non ci sono errori, inseriamo nel database
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO patients (lastname, firstname, birthdate, phone, mail) 
                    VALUES (:lastname, :firstname, :birthdate, :phone, :mail)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':lastname'  => $lastname,
                ':firstname' => $firstname,
                ':birthdate' => $birthdate,
                ':phone'     => $phone,
                ':mail'      => $mail
            ]);

            // Successo! Redirigiamo alla lista (Esercizio 2)
            header("Location: liste-patients.php?success=1");
            exit();
        } catch (PDOException $e) {
            $errors[] = "Errore durante l'inserimento: " . $e->getMessage();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital E2N</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <header>
        <a href="../index.php">
            <h1>HOSPITAL E2N</h1>
        </a>
      
        <nav>
            <a href="2-liste-des-patient.php">Liste Patients</a>
            <a href="1-ajout-patient.php">Ajouter Patient</a>

            <a href="6-liste-rendezvous.php">Liste RDV</a>
            <a href="5-ajout-rdv.php">Ajouter RDV</a>

            <a href="ajout-patient-rendez-vous">Ajout-patient-rendez-vous</a>
        </nav>
    </header>
    <main>
