<?php
session_start();
if (!isset($_SESSION["user_nan"])) {
    header("Location: index.php");
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
    <style>
        .header-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            gap: 15px;
            flex-wrap: wrap;
        }
        #bilatu {
            flex: 1;
            min-width: 250px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .buttons-container {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body> 
    <div class="container">
        <br/>
        <h2>📜 Zure historiala</h2>

        <div class="header-controls">
            <input type="text" id="bilatu" placeholder="Bilatu paketea edo hartzailea..." onkeyup="filtratu()">
            
            <div class="buttons-container">
                <button class="btn ok" onclick="exportatuXML()">Gorde XML</button>
                <button class="btn danger" style="background-color: #ef4444;" onclick="itzuliDashboardera()">Itzuli</button>
            </div>
        </div>

        <div id="historiala" class="historiala-container">
            </div>
    </div>

    <script src="js/historiala.js"></script>
</body>
</html>