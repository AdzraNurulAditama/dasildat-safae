<?php

$host = "localhost";
$user = "root";
$pass = "190606";
$db   = "gaming_ml";

$conn = mysqli_connect($host,$user,$pass,$db);

if(!$conn){
    die("Koneksi gagal");
}