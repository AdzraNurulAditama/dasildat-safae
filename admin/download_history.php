```php
<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit;
}

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../index.php");
    exit;
}

include '../config/database.php';

/** @var mysqli|null $connection */
$connection = null;
if (isset($conn) && $conn instanceof mysqli) {
    $connection = $conn;
} elseif (isset($link) && $link instanceof mysqli) {
    $connection = $link;
} elseif (isset($db) && $db instanceof mysqli) {
    $connection = $db;
}

if (!$connection instanceof mysqli) {
    die('Database connection not established.');
}

$filename = "history_dataset_" . date("Y-m-d_H-i-s") . ".csv";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

$query = $connection->query("SELECT * FROM history");

if ($query && $query->num_rows > 0) {

    $first_row = $query->fetch_assoc();

    fputcsv(
        $output,
        array_keys($first_row)
    );

    fputcsv(
        $output,
        $first_row
    );

    while ($row = $query->fetch_assoc()) {

        fputcsv(
            $output,
            $row
        );

    }

}

fclose($output);
exit;
?>
