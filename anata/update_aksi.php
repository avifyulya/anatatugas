<?php
include 'koneksi.php';

$id = $_POST['id'];
$nim = $_POST['nim'];
$nama = $_POST['nama'];
$email = $_POST['email'];
$prodi = $_POST['prodi'];
$semester = $_POST['semester'];
$alamat = $_POST['alamat'];

mysqli_query($conn, "UPDATE pendaftaran SET 
    nim='$nim', 
    nama='$nama', 
    email='$email', 
    prodi='$prodi', 
    semester='$semester', 
    alamat='$alamat' 
    WHERE id='$id'");

header("Location: proses.php");
exit;
?>
