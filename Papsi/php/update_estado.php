<?php
session_start();
include "konexioa.php";

$id             = $_POST["id"];
$estado         = $_POST["estado"];
$nan            = $_SESSION["user_nan"];
$azalpena_extra = isset($_POST["azalpena"]) ? trim($_POST["azalpena"]) : "";

// Decidir qué hacer según el estado recibido
if ($estado === "biltegian") {
    // 1. BUSCAR UN KUDEATZAILE REAL (Según tu tabla LANGILEA)
    $sql_kud = "SELECT nan FROM LANGILEA WHERE rola = 'kudeatzailea' LIMIT 1";
    $res_kud = mysqli_query($conexion, $sql_kud);
    $row_kud = mysqli_fetch_assoc($res_kud);
    
    // Si encuentra a Fatima (o cualquier jefe), se lo asigna. 
    // Si no, usa el NAN de respaldo 34567890C.
    $nan_destino = ($row_kud) ? $row_kud['nan'] : "34567890C"; 

    // 2. ACTUALIZAR: El paquete vuelve a 'zain' y cambia de dueño
    $sql = "UPDATE BANAKETA SET egoera = 'zain', nan_langilea = ? WHERE pakete_id = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $nan_destino, $id);
    
    $log_status = "⚠️ Arazoa: Biltegira itzulia";

} elseif ($estado === "banatzen") {
    // BANATU
    $sql = "UPDATE BANAKETA SET egoera = 'banatzen' WHERE pakete_id = ? AND nan_langilea = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $id, $nan);
    $log_status = "🚚 Banatzen hasita";

} elseif ($estado === "entregatua") {
    // ENTREGATU
    $sql = "UPDATE BANAKETA SET egoera = 'entregatua', data_entrega = NOW() WHERE pakete_id = ? AND nan_langilea = ?";
    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $id, $nan);
    $log_status = "✅ Entregatua";

} else {
    echo "error: estado desconocido";
    exit;
}

if (mysqli_stmt_execute($stmt)) {
    // Guardar en historial (log_id es AUTO_INCREMENT)
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