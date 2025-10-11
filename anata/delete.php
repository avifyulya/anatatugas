<?php
include 'koneksi.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM pendaftaran WHERE id='$id'");
header("Location: proses.php");
exit;
?>