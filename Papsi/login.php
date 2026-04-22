<?php
session_start();
require_once "php/konexioa.php";

$mezua = "";

// 1. Inprimakia bidali den egiaztatu
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $erabiltzailea = trim($_POST["usuario"]);
    $pasahitza = trim($_POST["password"]);

    // Datu-basean langilea bilatu
    $sql = "SELECT nan, erabiltzailea, pasahitza, rola 
            FROM LANGILEA 
            WHERE erabiltzailea = ? 
            LIMIT 1";

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $erabiltzailea);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($fila = mysqli_fetch_assoc($result)) {

        // 2. Pasahitza egiaztatu (Proiektu honetan testu arruntean dago)
        if ($pasahitza === $fila["pasahitza"]) {

            // 3. Rola egiaztatu: Banatzaileek bakarrik dute sarbidea
            if ($fila["rola"] === "banatzailea") {

                // Saioaren aldagaiak definitu
                $_SESSION["user_nan"] = $fila["nan"];
                $_SESSION["username"] = $fila["erabiltzailea"];
                $_SESSION["rola"] = $fila["rola"];

                // Dashboard-era bideratu
                header("Location: dashboard.php");
                exit;

            } else {
                $mezua = "Ez zara banatzailea";
            }

        } else {
            $mezua = "Pasahitza okerra";
        }

    } else {
        $mezua = "Erabiltzailea ez da existitzen";
    }
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Banaketa</title>
    <link rel="stylesheet" href="css/login.css">
</head>

<body>

<div class="background">

    <div class="card">

        <h1>Banaketa</h1>
        <p class="subtitle">Sartu zure Panelera</p>

        <form method="POST" action="login.php">

            <input type="text" name="usuario" placeholder="Erabiltzailea" required>
            <input type="password" name="password" placeholder="Pasahitza" required>

            <button type="submit">Sartu</button>

        </form>

        <?php if ($mezua): ?>
            <p style="color:#ff4d4d; margin-top:15px; font-weight:bold;">
                <?= $mezua ?>
            </p>
        <?php endif; ?>

    </div>

</div>

</body>
</html>