<?php
// Datu-basearen konexioa
$DB_HOST = 'localhost:3306';
$DB_USER = 'root';                // Aldatu zure MySQL erabiltzailearekin
$DB_PASS = 'mysql';                    // Aldatu zure MySQL pasahitzarekin
$DB_NAME = 'entrega_kudeaketa';   // Erabiliko den datu-basea

// Konexioa sortu
$conexion = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Konexioa egiaztatu
if (!$conexion) {
    die('Konexio errorea: ' . mysqli_connect_error());
}

// Karaktere-jokoa UTF-8ra ezarri
mysqli_set_charset($conexion, 'utf8mb4');