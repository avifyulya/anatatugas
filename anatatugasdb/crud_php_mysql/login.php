<?php
// Memulai session untuk digunakan saat login berhasil
session_start();

// Cek apakah form sudah disubmit
if (isset($_POST["submit"])) {
    // Sanitize dan trim input
    $username = htmlentities(strip_tags(trim($_POST["username"])));
    $password = htmlentities(strip_tags(trim($_POST["password"])));
    
    $error_message = "";

    // Validasi input
    if (empty($username)) {
        $error_message .= "- Username belum diisi <br>";
    }
    if (empty($password)) {
        $error_message .= "- Password belum diisi <br>";
    }

    // Jika tidak ada error pesan, lanjutkan ke proses otentikasi database
    if (empty($error_message)) {
        include("connection.php"); // Hubungkan ke database
        
        // Escape string untuk keamanan
        $username = mysqli_real_escape_string($connection, $username);
        $password = mysqli_real_escape_string($connection, $password);
        
        // Hash password menggunakan SHA1 (sesuai dengan yang disimpan di generate.php)
        $password_sha1 = sha1($password);

        // Query untuk mencari user di database
        $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password_sha1'";
        
        $result = mysqli_query($connection, $query);

        // Cek apakah data ditemukan
        if(mysqli_num_rows($result) == 0) {
            $error_message .= "- Username dan/atau Password tidak sesuai";
        } else {
            // Login berhasil
            $_SESSION["username"] = $username;
            
            // Bersihkan hasil query dan tutup koneksi
            mysqli_free_result($result);
            mysqli_close($connection);
            
            // Arahkan ke halaman utama setelah login
            header("Location: student_view.php");
            die(); // Hentikan eksekusi skrip
        }
        
        // Bersihkan hasil query dan tutup koneksi jika ada error
        mysqli_free_result($result);
        mysqli_close($connection);
    }
} else {
    // Inisialisasi variabel saat halaman pertama kali dimuat
    $error_message = "";
    $username = "";
    $password = "";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Mahasiswa</title>
    <link rel="stylesheet" href="assets/login.css">
    <style>
        /* Gaya dasar untuk contoh sederhana jika assets/login.css tidak tersedia */
        .container {
            width: 300px;
            margin: 50px auto;
            border: 1px solid #ccc;
            padding: 20px;
        }
        .error {
            background-color: #FFECEC;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid red;
        }
        p {
            margin-bottom: 10px;
        }
        input[type="text"], input[type="password"] {
            width: 95%;
            padding: 5px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Data Mahasiswa</h1>
        
        <?php
        if ($error_message !== "") {
            echo "<div class='error'>$error_message</div>";
        }
        ?>
        
        <form action="login.php" method="post">
            <fieldset>
                <legend>Login</legend>
                <p>
                    <label for="username">Username: </label>
                    <input type="text" name="username" id="username" 
                           value="<?php echo $username ?>" required>
                </p>
                <p>
                    <label for="password">Password: </label>
                    <input type="password" name="password" id="password" required>
                    </p>
                <p>
                    <input type="submit" name="submit" value="Log In">
                </p>
            </fieldset>
        </form>
    </div>
</body>
</html>