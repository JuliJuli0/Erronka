<?php
session_start();
include "konexioa.php";

// 1. Saioa hasita dagoela egiaztatu
if (!isset($_SESSION["user_nan"])) {
    echo json_encode([]);
    exit;
}

$nan = $_SESSION["user_nan"];

// 2. SQL kontsulta: Paketeen informazioa eta haien egoera lortu
// BANAKETA taulan egoerarik ez badago, 'zain' balioa emango dio (COALESCE)
$sql = "
SELECT 
  P.pakete_id,
  P.helbidea,
  P.hartzailea,
  COALESCE(B.egoera, 'zain') AS egoera
FROM PAKETEA P
INNER JOIN BANAKETA B ON P.pakete_id = B.pakete_id
WHERE B.nan_langilea = ?
";

$stmt = mysqli_prepare($conexion, $sql);

if ($stmt) {
    // Parametroa lotu eta kontsulta exekutatu
    mysqli_stmt_bind_param($stmt, "s", $nan);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    $paketeak = [];

    // Emaitza bakoitza array-an gorde
    while ($row = mysqli_fetch_assoc($result)) {
        $paketeak[] = $row;
    }

    // 3. Goiburua ezarri eta datuak JSON formatuan bueltatu
    header("Content-Type: application/json");
    echo json_encode($paketeak);

    mysqli_stmt_close($stmt);
} else {
    // Errorea badago, array huts bat bidali
    echo json_encode([]);
}

mysqli_close($conexion);
?>