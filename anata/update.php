<?php
include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pendaftaran WHERE id='$id'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Data</title>
    <style>
        body { font-family: Arial; max-width: 500px; margin: 50px auto; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #ffc107; color: black; padding: 10px 20px; border: none; cursor: pointer; }
        button:hover { opacity: 0.8; }
    </style>
</head>
<body>
<h2>Update Form Pendaftaran</h2>
<form method="POST" action="update_aksi.php">
    <input type="hidden" name="id" value="<?= $data['id']; ?>">

    <div class="form-group">
        <label>NIM:</label>
        <input type="text" name="nim" value="<?= $data['nim']; ?>" required>
    </div>

    <div class="form-group">
        <label>Nama Lengkap:</label>
        <input type="text" name="nama" value="<?= $data['nama']; ?>" required>
    </div>

    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?= $data['email']; ?>" required>
    </div>

    <div class="form-group">
        <label>Program Studi:</label>
        <select name="prodi" required>
            <option value="Teknik Informatika" <?= $data['prodi']=="Teknik Informatika"?'selected':''; ?>>Teknik Informatika</option>
            <option value="Sistem Informasi" <?= $data['prodi']=="Sistem Informasi"?'selected':''; ?>>Sistem Informasi</option>
            <option value="Teknik Komputer" <?= $data['prodi']=="Teknik Komputer"?'selected':''; ?>>Teknik Komputer</option>
        </select>
    </div>

    <div class="form-group">
        <label>Semester:</label>
        <input type="number" name="semester" min="1" max="14" value="<?= $data['semester']; ?>" required>
    </div>

    <div class="form-group">
        <label>Alamat:</label>
        <textarea name="alamat" rows="3" required><?= $data['alamat']; ?></textarea>
    </div>

    <button type="submit">Update</button>
</form>
</body>
</html>
