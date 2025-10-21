<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include("connection.php");

if (!isset($_GET["nim"])) {
    header("Location: student_view.php");
    exit;
}

$nim = mysqli_real_escape_string($connection, $_GET["nim"]);
$query = "DELETE FROM student WHERE nim='$nim'";
$result = mysqli_query($connection, $query);

if ($result) {
    $msg = urlencode("Mahasiswa dengan NIM $nim berhasil dihapus.");
    header("Location: student_view.php?message=$msg");
} else {
    $msg = urlencode("Gagal menghapus data: " . mysqli_error($connection));
    header("Location: student_view.php?message=$msg");
}

mysqli_close($connection);
?>