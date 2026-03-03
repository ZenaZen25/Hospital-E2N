<?php

// Vérifier que la méthode est POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../public/1-ajout-patient.php?error=bad-method");
    exit();
}

// Vérifier que les champs existent
if (
    !isset($_POST["lastname"]) ||
    !isset($_POST["firstname"]) ||
    !isset($_POST["birthdate"]) ||
    !isset($_POST["phone"]) ||
    !isset($_POST["email"])
) {
    header("Location: ../public/1-ajout-patient.php?error=missing-input");
    exit();
}

// Vérifier qu'ils ne sont pas vides
if (
    empty(trim($_POST["lastname"])) ||
    empty(trim($_POST["firstname"])) ||
    empty(trim($_POST["birthdate"])) ||
    empty(trim($_POST["phone"])) ||
    empty(trim($_POST["email"]))
) {
    header("Location: ../public/1-ajout-patient.php?error=missing-value");
    exit();
}

// Récupération des données
$lastname   = trim($_POST["lastname"]);
$firstname  = trim($_POST["firstname"]);
$birthdate  = trim($_POST["birthdate"]);
$phone      = trim($_POST["phone"]);
$email      = trim($_POST["email"]);

require_once "../utils/db_connect.php";

try {
    // 1. Definiamo la query. Nota che usiamo :mail come segnaposto
    $sql = "INSERT INTO patients (lastname, firstname, birthdate, phone, mail)
            VALUES (:lastname, :firstname, :birthdate, :phone, :mail)";

    $stmt = $db->prepare($sql);

    // 2. Passiamo i dati. Le chiavi dell'array devono corrispondere ai segnaposti sopra
    $stmt->execute([
        ':lastname'  => $lastname,
        ':firstname' => $firstname,
        ':birthdate' => $birthdate,
        ':phone'     => $phone,
        ':mail'      => $email  // <--- QUI: la chiave deve essere ':mail' perché nella query c'è :mail
    ]);

    header("Location: ../public/1-ajout-patient.php?status=success");
    exit();

} catch (PDOException $e) {
    // Durante i test, usa questo per vedere l'errore preciso:
    die("Errore: " . $e->getMessage());
}