<?php
session_start();

// 1. Erabiltzailea saioa hasita duela egiaztatu
// Saioa hasi gabe badago, login orrira bidaltzen dugu
if (!isset($_SESSION["user_nan"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historiala | Banaketa</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body> 
    <br/>

    <h2>📦 Entregatutako paketeak</h2>

    <input type="text" id="bilatu" placeholder="Bilatu paketea..." onkeyup="filtratu()">
    
    <br>
    
    <div class="buttons-container">
        <br>
        <button class="btn-history" onclick="exportatuXML()">Gorde XML</button>
        <button class="btn-logout" onclick="itzuliDashboardera()">Itzuli</button>
    </div>

    <div id="historiala" class="historiala-container">
        </div>

    <script src="js/historiala.js"></script>

    <br>

</body>
</html>