<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Hasil Pendaftaran</title>
    <style>
        body { font-family: Arial; max-width: 700px; margin: 40px auto; }
        .card { background: #f9f9f9; padding: 20px; border-radius: 8px; border: 1px solid #ddd; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
        table { width: 100%; margin: 0 auto; border-collapse: collapse; }
        td { padding: 8px; }
        .label { font-weight: bold; width: 150px; text-align: left; }
        .btn { display: inline-block; background: #2196F3; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; margin-right: 5px; }
        .btn-update { background: #ffc107; color: black; }
        .btn-delete { background: #f44336; }
        .btn:hover { opacity: 0.9; }
        h3 { margin-top: 40px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: center; }
    </style>
</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include 'koneksi.php';

    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $prodi = $_POST['prodi'];
    $semester = $_POST['semester'];
    $alamat = $_POST['alamat'];

    mysqli_query($conn, "INSERT INTO pendaftaran (nim, nama, email, prodi, semester, alamat) 
        VALUES ('$nim','$nama','$email','$prodi','$semester','$alamat')");
    
    echo "<div class='success'>✓ Pendaftaran berhasil! Data Anda telah tersimpan.</div>";
}

$hasil = mysqli_query($conn, "SELECT * FROM pendaftaran ORDER BY id DESC LIMIT 1");
$data = mysqli_fetch_assoc($hasil);
?>

<div class="card">
    <h2>Detail Pendaftaran</h2>
    <table>
        <tr><td class="label">NIM</td><td><?= $data['nim']; ?></td></tr>
        <tr><td class="label">Nama Lengkap</td><td><?= $data['nama']; ?></td></tr>
        <tr><td class="label">Email</td><td><?= $data['email']; ?></td></tr>
        <tr><td class="label">Program Studi</td><td><?= $data['prodi']; ?></td></tr>
        <tr><td class="label">Semester</td><td><?= $data['semester']; ?></td></tr>
        <tr><td class="label">Alamat</td><td><?= $data['alamat']; ?></td></tr>
        <tr><td class="label">Tanggal Daftar</td><td><?= $data['tanggal_daftar']; ?></td></tr>
    </table>

    <div style="margin-top:20px;">
        <a href="form.php" class="btn">Daftar Lagi</a>
        <a href="update.php?id=<?= $data['id']; ?>" class="btn btn-update">Update</a>
        <a href="delete.php?id=<?= $data['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus data ini?');">Delete</a>
    </div>
</div>

<h3>📜 Histori Pendaftaran</h3>
<table>
    <tr>
        <th>ID</th>
        <th>NIM</th>
        <th>Nama</th>
        <th>Prodi</th>
        <th>Semester</th>
        <th>Tanggal Daftar</th>
    </tr>
    <?php
    $histori = mysqli_query($conn, "SELECT * FROM pendaftaran ORDER BY id DESC");
    while ($row = mysqli_fetch_assoc($histori)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nim']}</td>
                <td>{$row['nama']}</td>
                <td>{$row['prodi']}</td>
                <td>{$row['semester']}</td>
                <td>{$row['tanggal_daftar']}</td>
              </tr>";
    }
    ?>
</table>
</body>
</html>
