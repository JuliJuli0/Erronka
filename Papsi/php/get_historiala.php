<?php
session_start();
include "konexioa.php";

// 1. Erabiltzailea saioa hasita duela egiaztatu
if (!isset($_SESSION["user_nan"])) {
    echo json_encode([]);
    exit;
}

$nan = $_SESSION["user_nan"];

// 2. SQL kontsulta: 'historiala' eta 'paketea' taulak lotu (JOIN) hartzailearen izena lortzeko
$sql = "SELECT h.data_ordua, h.azalpena, h.pakete_id, p.hartzailea 
        FROM historiala h
        JOIN paketea p ON h.pakete_id = p.pakete_id
        WHERE h.nan_langilea = ? 
        ORDER BY h.data_ordua DESC";

$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    // Parametroak lotu eta exekutatu
    mysqli_stmt_bind_param($stmt, "s", $nan);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $datos = [];
    // Emaitzak array batean gorde
    while ($row = mysqli_fetch_assoc($result)) {
        $datos[] = $row;
    }

    // 3. Datuak JSON formatuan bidali JavaScript-era
    echo json_encode($datos);
    
    mysqli_stmt_close($stmt);
} else {
    // Errore kasuan array huts bat bidali
    echo json_encode([]);
}

mysqli_close($conexion);
?>