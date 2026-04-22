<?php
session_start();
include "konexioa.php";

// Jaso beharreko datuak definitu
$id = $_POST["id"];
$estado = $_POST["estado"];
$nan = $_SESSION["user_nan"];

// 1. EGOERA EGUNERATU 'BANAKETA' TAULAN
// Paketearen egoera aldatzen dugu eta, 'entregatua' bada, ordua gordetzen dugu
$sql = "UPDATE BANAKETA 
        SET egoera = ?,
            data_entrega = CASE WHEN ? = 'entregatua' THEN NOW() ELSE data_entrega END
        WHERE pakete_id = ? AND nan_langilea = ?";

$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ssss", $estado, $estado, $id, $nan);

if (mysqli_stmt_execute($stmt)) {
    
    // 2. HISTORIALEAN ERREGISTRO BERRIA SARTU
    $azalpena = "Egoera aldatuta: " . $estado;
    
    // Datuak 'historiala' taulan gordetzen ditugu. 
    // 'log_id' automatikoki gehitzen denez, ez dugu hemen jartzen.
    $sql_log = "INSERT INTO historiala (data_ordua, azalpena, pakete_id, nan_langilea) 
                VALUES (NOW(), ?, ?, ?)";
    
    $stmt_log = mysqli_prepare($conexion, $sql_log);
    mysqli_stmt_bind_param($stmt_log, "sss", $azalpena, $id, $nan);
    mysqli_stmt_execute($stmt_log);

    // Dena ondo badoa, "ok" erantzuna bidali JavaScript-ari
    echo "ok";
} else {
    // Errore kasuan "error" bidali
    echo "error";
}

mysqli_close($conexion);
?>