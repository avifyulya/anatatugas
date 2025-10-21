<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

include("connection.php");

$error_message = "";
$nim = $name = $birth_city = $faculty = $department = $gpa = "";
$birth_date = 1;
$birth_month = "1";
$birth_year = 1996;

$arr_month = [
    "1" => "Januari", "2" => "Februari", "3" => "Maret", "4" => "April",
    "5" => "Mei", "6" => "Juni", "7" => "Juli", "8" => "Agustus",
    "9" => "September", "10" => "Oktober", "11" => "Nopember", "12" => "Desember"
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = trim($_POST["nim"]);
    $name = trim($_POST["name"]);
    $birth_city = trim($_POST["birth_city"]);
    $birth_date = $_POST["birth_date"];
    $birth_month = $_POST["birth_month"];
    $birth_year = $_POST["birth_year"];
    $faculty = $_POST["faculty"];
    $department = trim($_POST["department"]);
    $gpa = trim($_POST["gpa"]);

    // Validasi sederhana
    if (empty($nim)) $error_message .= "- NIM belum diisi.<br>";
    elseif (!preg_match("/^[0-9]{8}$/", $nim)) $error_message .= "- NIM harus 8 digit angka.<br>";

    if (empty($name)) $error_message .= "- Nama belum diisi.<br>";
    if (empty($birth_city)) $error_message .= "- Tempat lahir belum diisi.<br>";
    if (empty($department)) $error_message .= "- Jurusan belum diisi.<br>";
    if (!is_numeric($gpa) || $gpa <= 0) $error_message .= "- IPK harus berupa angka > 0.<br>";

    if ($error_message === "") {
        $birth_date_full = "$birth_year-$birth_month-$birth_date";
        $query = "INSERT INTO student VALUES ('$nim','$name','$birth_city','$birth_date_full','$faculty','$department',$gpa)";
        $result = mysqli_query($connection, $query);
        if ($result) {
            header("Location: student_view.php?message=" . urlencode("Mahasiswa $name berhasil ditambahkan!"));
            exit;
        } else {
            die("Query gagal: " . mysqli_error($connection));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Mahasiswa</title>
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
    margin-top: 20px;
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
    <h1>Tambah Data Mahasiswa</h1>
    <nav>
        <a href="student_view.php">Tampil</a>
        <a href="student_add.php">Tambah</a>
        <a href="logout.php">Logout</a>
    </nav>

    <?php if ($error_message !== ""): ?>
        <div class="error"><?= $error_message ?></div>
    <?php endif; ?>

    <form action="student_add.php" method="post">
        <fieldset>
            <legend>Form Tambah Mahasiswa</legend>
            <p><label>NIM:</label><input type="text" name="nim" value="<?= $nim ?>" placeholder="8 digit angka"></p>
            <p><label>Nama:</label><input type="text" name="name" value="<?= $name ?>"></p>
            <p><label>Tempat Lahir:</label><input type="text" name="birth_city" value="<?= $birth_city ?>"></p>
            <p><label>Tanggal Lahir:</label>
                <select name="birth_date">
                    <?php for($i=1;$i<=31;$i++): ?>
                        <option value="<?= $i ?>" <?= ($i==$birth_date)?"selected":"" ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
                <select name="birth_month">
                    <?php foreach($arr_month as $key=>$val): ?>
                        <option value="<?= $key ?>" <?= ($key==$birth_month)?"selected":"" ?>><?= $val ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="birth_year">
                    <?php for($i=1990;$i<=date('Y');$i++): ?>
                        <option value="<?= $i ?>" <?= ($i==$birth_year)?"selected":"" ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </p>
            <p><label>Fakultas:</label>
                <select name="faculty">
                    <option value="FTIB" <?= ($faculty=="FTIB")?"selected":"" ?>>FTIB</option>
                    <option value="FTEIC" <?= ($faculty=="FTEIC")?"selected":"" ?>>FTEIC</option>
                </select>
            </p>
            <p><label>Jurusan:</label><input type="text" name="department" value="<?= $department ?>"></p>
            <p><label>IPK:</label><input type="text" name="gpa" value="<?= $gpa ?>" placeholder="Gunakan titik untuk desimal"></p>
            <p><input type="submit" value="Tambah Data"></p>
        </fieldset>
    </form>
</div>
</body>
</html>
