<!DOCTYPE html>
<html>
<head>
    <title>Form Pendaftaran</title>
    <style>
        body { font-family: Arial; max-width: 500px; margin: 50px auto; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { background: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <h2>Form Pendaftaran Mahasiswa</h2>
    <form method="POST" action="proses.php">
        <div class="form-group">
            <label>NIM:</label>
            <input type="text" name="nim" required>
        </div>

        <div class="form-group">
            <label>Nama Lengkap:</label>
            <input type="text" name="nama" required>
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>

        <div class="form-group">
            <label>Program Studi:</label>
            <select name="prodi" required>
                <option value="">Pilih Prodi</option>
                <option value="Teknik Informatika">Teknik Informatika</option>
                <option value="Sistem Informasi">Sistem Informasi</option>
                <option value="Teknik Komputer">Teknik Komputer</option>
            </select>
        </div>

        <div class="form-group">
            <label>Semester:</label>
            <input type="number" name="semester" min="1" max="14" required>
        </div>

        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="alamat" rows="3" required></textarea>
        </div>

        <button type="submit">Daftar</button>
    </form>
</body>
</html>
