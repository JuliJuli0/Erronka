<?php
session_start();
include "php/konexioa.php"; 

// 1. Erabiltzailea saioa hasita duela egiaztatu
if (!isset($_SESSION["user_nan"])) {
    header("Location: login.php");
    exit;
}

$nan = $_SESSION["user_nan"];
$izena_final = "Erabiltzailea"; // Balio lehenetsia errore bat egonez gero

// 2. Langilearen izen-abizenak datu-basetik lortu
$sql_izena = "SELECT izena FROM LANGILEA WHERE nan = ?"; 
$stmt = mysqli_prepare($conexion, $sql_izena);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $nan);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultado)) {
        $izena_final = $row['izena']; 
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repartidorea | Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <script src="js/dashboard.js" defer></script>
</head>

<body>

<div class="topbar">
    <h2>🚚 <span style="color: var(--primary)">Kaixo,</span> <?php echo htmlspecialchars($izena_final); ?></h2>

    <div style="display:flex; gap:10px;">
        <button class="btn-history" onclick="joanHistorialera()">Historiala</button>
        <button class="btn-logout" onclick="logout()">Atera</button>
    </div>
</div>

<div class="stats">
    <div class="stat" style="border-color: var(--status-pending)">
        <h3 id="Zain">0</h3>
        <p>Zain</p>
    </div>
    <div class="stat" style="border-color: var(--status-progress)">
        <h3 id="banatzen">0</h3>
        <p>Banatzen</p>
    </div>
    <div class="stat" style="border-color: var(--status-done)">
        <h3 id="entregados">0</h3>
        <p>Entregatuak</p>
    </div>
</div>

<div class="container-columnak">
    <div class="columna" id="col-zain"></div>
    <div class="columna" id="col-banatzen"></div>
    <div class="columna" id="col-entregatuak"></div>
</div>

<div id="popup" class="popup">
    <div class="popup-card">
        <div class="popup-burua">
            <h2 id="m-izena">Bezeroa</h2>
            <span class="close" onclick="itxiPopup()">×</span>
        </div>
        <div class="popup-gorputza">
            <p><strong>ID:</strong> <span id="m-dni"></span></p>
            <p><strong>Direkzioa:</strong> <span id="m-helbidea"></span></p>
            <div class="popup-map" id="m-mapa"></div>
        </div>
    </div>
</div>

</body>
</html>