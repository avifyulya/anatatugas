<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    die();
}

include("connection.php");

// Cek apakah ada NIM di URL
if (!isset($_GET["nim"])) {
    header("Location: student_view.php");
    die();
}

$nim = $_GET["nim"];
$query = "SELECT * FROM student WHERE nim='$nim'";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) < 1) {
    die("Data mahasiswa dengan NIM $nim tidak ditemukan!");
}

$data = mysqli_fetch_assoc($result);

$name = $data["name"];
$birth_city = $data["birth_city"];
$birth_date = date("d", strtotime($data["birth_date"]));
$birth_month = date("m", strtotime($data["birth_date"]));
$birth_year = date("Y", strtotime($data["birth_date"]));
$faculty = $data["faculty"];
$department = $data["department"];
$gpa = $data["gpa"];

$error_message = "";

// Jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $birth_city = trim($_POST["birth_city"]);
    $birth_date = $_POST["birth_date"];
    $birth_month = $_POST["birth_month"];
    $birth_year = $_POST["birth_year"];
    $faculty = $_POST["faculty"];
    $department = trim($_POST["department"]);
    $gpa = trim($_POST["gpa"]);

    if (empty($name)) $error_message .= "- Nama belum diisi.<br>";
    if (empty($birth_city)) $error_message .= "- Tempat lahir belum diisi.<br>";
    if (empty($department)) $error_message .= "- Jurusan belum diisi.<br>";
    if (!is_numeric($gpa) || $gpa <= 0) $error_message .= "- IPK harus berupa angka dan lebih dari 0.<br>";

    if ($error_message === "") {
        $birth_date_full = "$birth_year-$birth_month-$birth_date";
        $query = "UPDATE student 
                  SET name='$name', birth_city='$birth_city', birth_date='$birth_date_full',
                      faculty='$faculty', department='$department', gpa=$gpa
                  WHERE nim='$nim'";
        $result = mysqli_query($connection, $query);

        if ($result) {
            header("Location: student_view.php?message=" . urlencode("Data mahasiswa dengan NIM $nim berhasil diperbarui."));
            exit;
        } else {
            die("Query gagal dijalankan: " . mysqli_error($connection));
        }
    }
}

$arr_month = [
    "1" => "Januari", "2" => "Februari", "3" => "Maret", "4" => "April",
    "5" => "Mei", "6" => "Juni", "7" => "Juli", "8" => "Agustus",
    "9" => "September", "10" => "Oktober", "11" => "Nopember", "12" => "Desember"
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Mahasiswa</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background-color: #f9fafb;
    margin: 0;
    padding: 0;
}
.container {
    width: 90%;
    max-width: 800px;
    margin: 40px auto;
    background: #fff;
    padding: 25px 30px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h1 {
    text-align: center;
    color: #1e293b;
}
nav {
    text-align: center;
    margin-bottom: 20px;
}
nav a {
    text-decoration: none;
    color: white;
    background-color: #2563eb;
    padding: 8px 15px;
    border-radius: 6px;
    margin: 0 5px;
    transition: 0.3s;
}
nav a:hover {
    background-color: #1d4ed8;
}
fieldset {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 20px;
}
legend {
    font-weight: bold;
    color: #2563eb;
}
label {
    display: inline-block;
    width: 140px;
    font-weight: 500;
}
input[type=text], select {
    width: 70%;
    padding: 8px;
    border: 1px solid #cbd5e1;
    border-radius: 5px;
    margin: 5px 0;
}
input[type=submit] {
    background-color: #2563eb;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
}
input[type=submit]:hover {
    background-color: #1e40af;
}
.error {
    background-color: #fee2e2;
    color: #b91c1c;
    border: 1px solid #fca5a5;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 15px;
}
</style>
</head>
<body>
<div class="container">
    <h1>Edit Data Mahasiswa</h1>
    <nav>
        <a href="student_view.php">Tampil</a>
        <a href="student_add.php">Tambah</a>
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($error_message !== ""): ?>
        <div class="error"><?= $error_message ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <fieldset>
            <legend>Form Edit Mahasiswa</legend>
            <p><label>NIM:</label><strong><?= htmlspecialchars($nim) ?></strong></p>
            <p><label>Nama:</label><input type="text" name="name" value="<?= htmlspecialchars($name) ?>"></p>
            <p><label>Tempat Lahir:</label><input type="text" name="birth_city" value="<?= htmlspecialchars($birth_city) ?>"></p>
            <p><label>Tanggal Lahir:</label>
                <select name="birth_date">
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                        <option value="<?= $i ?>" <?= ($i == $birth_date) ? "selected" : "" ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <select name="birth_month">
                    <?php foreach ($arr_month as $key => $value): ?>
                        <option value="<?= $key ?>" <?= ($key == (int)$birth_month) ? "selected" : "" ?>><?= $value ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="birth_year">
                    <?php for ($i = 1990; $i <= date('Y'); $i++): ?>
                        <option value="<?= $i ?>" <?= ($i == $birth_year) ? "selected" : "" ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </p>
            <p><label>Fakultas:</label>
                <select name="faculty">
                    <option value="FTIB" <?= ($faculty == "FTIB") ? "selected" : "" ?>>FTIB</option>
                    <option value="FTEIC" <?= ($faculty == "FTEIC") ? "selected" : "" ?>>FTEIC</option>
                </select>
            </p>
            <p><label>Jurusan:</label><input type="text" name="department" value="<?= htmlspecialchars($department) ?>"></p>
            <p><label>IPK:</label><input type="text" name="gpa" value="<?= htmlspecialchars($gpa) ?>"></p>
            <p><input type="submit" value="Simpan Perubahan"></p>
        </fieldset>
    </form>
</div>
</body>
</html>
