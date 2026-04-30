<?php
session_start();
include "konexioa.php";

$id             = $_POST["id"];
$estado         = $_POST["estado"];
$nan            = $_SESSION["user_nan"];
$azalpena_extra = isset($_POST["azalpena"]) ? trim($_POST["azalpena"]) : "";

// Jasotako egoeraren arabera zer egin erabaki
if ($estado === "biltegian") {
    // 1. KUDEATZAILE BAT BILATU (LANGILEA taularen arabera)
    $sql_kud = "SELECT nan FROM LANGILEA WHERE rola = 'kudeatzailea' LIMIT 1";
    $res_kud = mysqli_query($conexion, $sql_kud);
    $row_kud = mysqli_fetch_assoc($res_kud);
    
    // Kudeatzaileren bat aurkitzen badu, hari esleitzen dio paketea.
    // Bestela, 34567890C NAN-a erabiliko du babes gisa.
    $nan_destino = ($row_kud) ? $row_kud['nan'] : "34567890C"; 

    // 2. EGUNERATU: Paketea 'zain' egoerara itzultzen da eta jabez aldatzen da
    $sql = "UPDATE BANAKETA SET egoera = 'zain', nan_langilea = ? WHERE pakete_id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nan_destino, $id);
    
    $log_status = "⚠️ Arazoa: Biltegira itzulia";

} elseif ($estado === "banatzen") {
    // BANATZEN HASI
    $sql = "UPDATE BANAKETA SET egoera = 'banatzen' WHERE pakete_id = ? AND nan_langilea = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $id, $nan);
    $log_status = "🚚 Banatzen hasita";

} elseif ($estado === "entregatua") {
    // ENTREGATUA
    $sql = "UPDATE BANAKETA SET egoera = 'entregatua', data_entrega = NOW() WHERE pakete_id = ? AND nan_langilea = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $id, $nan);
    $log_status = "✅ Entregatua";

} else {
    echo "error: egoera ezezaguna";
    exit;
}

// Aldaketa gauzatu eta historialera gehitu
if (mysqli_stmt_execute($stmt)) {
    // Historialean gorde (log_id AUTO_INCREMENT da)
    $final_azalpena = ($azalpena_extra !== "") 
        ? $log_status . " | Oharra: " . $azalpena_extra 
        : $log_status;

    $sql_log = "INSERT INTO HISTORIALA (data_ordua, azalpena, pakete_id, nan_langilea) VALUES (NOW(), ?, ?, ?)";
    $stmt_log = mysqli_prepare($conexion, $sql_log);
    mysqli_stmt_bind_param($stmt_log, "sss", $final_azalpena, $id, $nan);
    mysqli_stmt_execute($stmt_log);

    echo "ok";
} else {
    echo "error: " . mysqli_error($conexion);
}

mysqli_close($conexion);
?>