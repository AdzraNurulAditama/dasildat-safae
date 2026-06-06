<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "gaming_ml";

/* =========================
DATABASE CONNECTION
========================= */

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db
);

/* =========================
CHECK CONNECTION
========================= */

if(!$conn){

    die(
        "Koneksi database gagal: "
        . mysqli_connect_error()
    );
}

/* =========================
SET UTF8
========================= */

mysqli_set_charset(
    $conn,
    "utf8"
);

?>