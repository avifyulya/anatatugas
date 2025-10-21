<?php
$title = "Form Registrasi Mahasiswa";
$arr_month = [
    "1" => "Januari",
    "2" => "Februari",
    "3" => "Maret",
    "4" => "April",
    "5" => "Mei",
    "6" => "Juni",
    "7" => "Juli",
    "8" => "Agustus",
    "9" => "September",
    "10" => "Oktober",
    "11" => "November",
    "12" => "Desember",
];
$error_message = "";

if (isset($_POST["submit"])) {
    // Sanitize and trim all POST data
    $student_name = htmlentities(strip_tags(trim($_POST["student_name"])));
    $student_number = htmlentities(strip_tags(trim($_POST["student_number"])));
    $student_address = htmlentities(strip_tags(trim($_POST["student_address"])));
    $student_birth_date = htmlentities(strip_tags(trim($_POST["student_birth_date"])));
    $student_birth_month = htmlentities(strip_tags(trim($_POST["student_birth_month"])));
    $student_birth_year = htmlentities(strip_tags(trim($_POST["student_birth_year"])));
    $student_gender = htmlentities(strip_tags(trim($_POST["student_gender"])));
    $student_website = htmlentities(strip_tags(trim($_POST["student_website"])));
    $student_email = htmlentities(strip_tags(trim($_POST["student_email"])));
    $student_username = htmlentities(strip_tags(trim($_POST["student_username"])));
    $student_password = htmlentities(strip_tags(trim($_POST["student_password"])));
    $student_password_confirmation = htmlentities(strip_tags(trim($_POST["student_password_confirmation"])));

    // --- Validation Checks ---
    if (empty($student_name)) $error_message .= "- Nama Mahasiswa belum diisi <br>";
    if (empty($student_number)) $error_message .= "- No Induk Mahasiswa (NIM) belum diisi <br>";
    if (empty($student_address)) $error_message .= "- Alamat Mahasiswa belum diisi <br>";
    if (empty($student_website)) $error_message .= "- URL Website belum diisi <br>";

    // --- File Upload Validation ---
    $upload_error = $_FILES["student_photo"]["error"];
    
    if ($upload_error !== 0) {
        $arr_upload_error = [
            1 => '- Ukuran file foto melewati batas maksimal <br>', // UPLOAD_ERR_INI_SIZE
            2 => '- Ukuran file foto melewati batas maksimal 1MB <br>', // UPLOAD_ERR_FORM_SIZE (Note: error message seems inconsistent with value="1048576" which is 1MB)
            3 => '- File foto hanya ter-upload sebagian <br>', // UPLOAD_ERR_PARTIAL
            4 => '- Foto tidak ditemukan <br>', // UPLOAD_ERR_NO_FILE
            6 => '- Server Error (Upload Foto) <br>', // UPLOAD_ERR_NO_TMP_DIR
            7 => '- Server Error (Upload Foto) <br>', // UPLOAD_ERR_CANT_WRITE
            8 => '- Server Error (Upload Foto) <br>', // UPLOAD_ERR_EXTENSION
        ];
        // Append specific upload error message if it exists in the array
        if (isset($arr_upload_error[$upload_error])) {
            $error_message .= $arr_upload_error[$upload_error];
        } else {
             $error_message .= "- Terjadi error saat upload foto <br>"; // General fallback
        }
    } else {
        $folder_name = "folder_upload";
        $file_name = $_FILES["student_photo"]["name"];
        $file_path = "$folder_name/$file_name";

        // Check if file name already exists on server
        if (file_exists($file_path)) {
            $error_message .= "- File dengan nama sama sudah ada di server <br>";
        }

        // Check file size (redundant if error 2 handles it, but keeps the custom message)
        $file_size = $_FILES["student_photo"]["size"];
        // The HTML MAX_FILE_SIZE is 1048576 (1MB), but the error message says 700KB
        if ($file_size > 1048576) { 
            $error_message .= "- Ukuran file melebihi 1MB <br>"; // Corrected message to match MAX_FILE_SIZE
        }

        // Check if the uploaded file is an image
        $check = getimagesize($_FILES["student_photo"]["tmp_name"]);
        if ($check === false) {
            $error_message .= "- Mohon upload file gambar (gif, png, atau jpg) <br>";
        }
    }

    // --- Account Info Validation ---
    if (empty($student_email)) $error_message .= "- Email belum diisi <br>";
    if (empty($student_username)) $error_message .= "- Username belum diisi <br>";
    if (empty($student_password)) $error_message .= "- Password belum diisi <br>";
    if (empty($student_password_confirmation)) $error_message .= "- Konfirmasi Password belum diisi <br>";

    // --- Gender Handling and Checkbox Skill Handling ---
    $checked_man = "";
    $checked_woman = "";
    switch ($student_gender) {
        case 'man':
            $student_gender_text = "Pria";
            $checked_man = "checked";
            break;
        case 'woman':
            $student_gender_text = "Wanita";
            $checked_woman = "checked";
            break;
        default:
             $student_gender_text = "";
             break;
    }

    // Initialize skill variables
    $student_skill_html = ""; $student_skill_html_text = "";
    $student_skill_css = ""; $student_skill_css_text = "";
    $student_skill_js = ""; $student_skill_js_text = "";
    $student_skill_php = ""; $student_skill_php_text = "";
    $student_skill_mysql = ""; $student_skill_mysql_text = "";
    $student_skill_laravel = ""; $student_skill_laravel_text = "";
    $student_skill_react_native = ""; $student_skill_react_native_text = "";

    // Set skill variables if checked
    if (isset($_POST["student_skill_html"])) {
        $student_skill_html = "checked";
        $student_skill_html_text = "HTML";
    }
    if (isset($_POST["student_skill_css"])) {
        $student_skill_css = "checked";
        $student_skill_css_text = ", CSS";
    }
    if (isset($_POST["student_skill_js"])) {
        $student_skill_js = "checked";
        $student_skill_js_text = ", Javascript";
    }
    if (isset($_POST["student_skill_php"])) {
        $student_skill_php = "checked";
        $student_skill_php_text = ", PHP";
    }
    if (isset($_POST["student_skill_mysql"])) {
        $student_skill_mysql = "checked";
        $student_skill_mysql_text = ", MySQL";
    }
    if (isset($_POST["student_skill_laravel"])) {
        $student_skill_laravel = "checked";
        $student_skill_laravel_text = ", Laravel";
    }
    if (isset($_POST["student_skill_react_native"])) {
        $student_skill_react_native = "checked";
        $student_skill_react_native_text = ", React Native";
    }

    // --- Final Submission / Redirect ---
    if ($error_message === "") {
        $folder_name = "folder_upload";
        $tmp = $_FILES["student_photo"]["tmp_name"];
        $file_name = $_FILES["student_photo"]["name"];
        // Move uploaded file
        move_uploaded_file($tmp, "$folder_name/$file_name");
        
        // Include the process file and stop script execution
        include("registration_process.php");
        die();
    }
} else {
    // --- Initial Load (First time access) ---
    $student_name = "";
    $student_number = "";
    $student_address = "";
    $student_birth_date = 1;
    $student_birth_month = "1";
    $student_birth_year = "1990";
    $checked_man = "checked";
    $checked_woman = "";
    $student_website = "";
    $student_email = "";
    $student_username = "";
    $student_password = "";
    $student_password_confirmation = "";
    $student_skill_html = "";
    $student_skill_css = "";
    $student_skill_js = "";
    $student_skill_php = "";
    $student_skill_mysql = "";
    $student_skill_laravel = "";
    $student_skill_react_native = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Adzanil Rachmadhi Putra">
    <meta name="keyword" content="Belajar HTML, Belajar Web">
    <meta name="description" content="Halaman praktikum modul 8 mata kuliah pemrograman web di program studi sistem informasi">
    <meta name="robots" content="index, follow">
    <title><?= $title ?></title>
    <style>
        .container {
            width: 600px;
            margin: 0 auto; /* Added center alignment for better display */
        }
        .error {
            background-color: #FFECEC;
            padding: 10px 15px;
            margin: 3px 3px 20px 3px;
            border: 1px solid red;
        }
        table {
            border-collapse: collapse;
        }
        td {
            padding: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><?= $title ?></h2>
        <?php
        if ($error_message !== "") {
            echo "<div class='error'>$error_message</div>";
        }
        ?>
        <form action="registration.php" method="post" enctype="multipart/form-data">
            <fieldset>
                <legend>Biodata</legend>
                <table>
                    <tr>
                        <td>Nama Mahasiswa*</td>
                        <td>:</td>
                        <td>
                            <input type="text" name="student_name" value="<?= $student_name ?>" size="40" placeholder="Nama Anda" required>
                        </td>
                    </tr>
                    <tr>
                        <td>No Induk Mahasiswa (NIM)*</td>
                        <td>:</td>
                        <td><input type="text" name="student_number" value="<?= $student_number ?>" size="40" placeholder="NIM Anda" required></td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">Alamat Mahasiswa*</td>
                        <td style="vertical-align: top;">:</td>
                        <td><textarea name="student_address" cols="30" rows="5" placeholder="Alamat Anda" required><?= $student_address ?></textarea></td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir*</td>
                        <td>:</td>
                        <td>
                            <select name="student_birth_date" id="student_birth_date">
                                <?php
                                for ($i = 1; $i <= 31; $i++) {
                                    $date_value = str_pad($i, 2, "0", STR_PAD_LEFT);
                                    $selected = ($i == $student_birth_date) ? "selected" : "";
                                    echo "<option value='$i' $selected>$date_value</option>";
                                }
                                ?>
                            </select>

                            <select name="student_birth_month">
                                <?php
                                foreach ($arr_month as $key => $value) {
                                    $selected = ($key == $student_birth_month) ? "selected" : "";
                                    echo "<option value='$key' $selected>$value</option>";
                                }
                                ?>
                            </select>

                            <select name="student_birth_year">
                                <?php
                                for ($i = 1990; $i <= 2025; $i++) {
                                    $selected = ($i == $student_birth_year) ? "selected" : "";
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin*</td>
                        <td>:</td>
                        <td>
                            <input type="radio" name="student_gender" value="man" id="pria" <?= $checked_man ?> required><label for="pria">Pria</label>
                            <input type="radio" name="student_gender" value="woman" id="wanita" <?= $checked_woman ?>><label for="wanita">Wanita</label>
                        </td>
                    </tr>
                    <tr>
                        <td>Upload Foto*</td>
                        <td>:</td>
                        <td>
                            <input type="file" name="student_photo" id="file_upload" accept="image/*" required>
                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                        </td>
                    </tr>
                    <tr>
                        <td>URL Website*</td>
                        <td>:</td>
                        <td>
                            <input type="url" name="student_website" value="<?= $student_website ?>" size="40" placeholder="URL Website Anda" required>
                        </td>
                    </tr>
                </table>
            </fieldset>

            <br>

            <fieldset>
                <legend>Info Akun</legend>
                <table>
                    <tr>
                        <td>Email*</td>
                        <td>:</td>
                        <td><input type="email" name="student_email" value="<?= $student_email ?>" size="40" placeholder="Email Anda" required></td>
                    </tr>
                    <tr>
                        <td>Username*</td>
                        <td>:</td>
                        <td><input type="text" name="student_username" value="<?= $student_username ?>" size="40" placeholder="Username Anda" required></td>
                    </tr>
                    <tr>
                        <td>Password*</td>
                        <td>:</td>
                        <td><input type="password" name="student_password" size="40" placeholder="Password Anda" required></td>
                    </tr>
                    <tr>
                        <td>Konfirmasi Password*</td>
                        <td>:</td>
                        <td><input type="password" name="student_password_confirmation" size="40" placeholder="Konfirmasi Password Anda" required></td>
                    </tr>
                </table>
            </fieldset>

            <br>

            <fieldset>
                <legend>Kemampuan Dasar</legend>
                <table>
                    <tr>
                        <td>
                            <input type="checkbox" name="student_skill_html" value="html" id="html" <?= $student_skill_html ?>><label for="html">HTML</label>
                            <input type="checkbox" name="student_skill_css" value="css" id="css" <?= $student_skill_css ?>><label for="css">CSS</label>
                            <input type="checkbox" name="student_skill_js" value="javascript" id="javascript" <?= $student_skill_js ?>><label for="javascript">Javascript</label>
                            <input type="checkbox" name="student_skill_php" value="php" id="php" <?= $student_skill_php ?>><label for="php">PHP</label>
                            <input type="checkbox" name="student_skill_mysql" value="mysql" id="mysql" <?= $student_skill_mysql ?>><label for="mysql">MySQL</label>
                            <input type="checkbox" name="student_skill_laravel" value="laravel" id="laravel" <?= $student_skill_laravel ?>><label for="laravel">Laravel</label>
                            <input type="checkbox" name="student_skill_react_native" value="react_native" id="react_native" <?= $student_skill_react_native ?>><label for="react_native">React Native</label>
                        </td>
                    </tr>
                </table>
            </fieldset>

            <br>

            <div>
                <input type="reset" value="Reset">
                <input type="submit" name="submit" value="Simpan">
            </div>
        </form>
    </div>
</body>
</html>