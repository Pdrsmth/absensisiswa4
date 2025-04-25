<?php
session_start();
include 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nis = mysqli_real_escape_string($con, $_POST['nis']);
    $nama = mysqli_real_escape_string($con, $_POST['nama_siswa']);
    $password = sha1(mysqli_real_escape_string($con, $_POST['password']));
    $tempat_lahir = mysqli_real_escape_string($con, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($con, $_POST['tgl_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($con, $_POST['jk']);
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
    $tahun_angkatan = mysqli_real_escape_string($con, $_POST['th_angkatan']);
    $id_mkelas = mysqli_real_escape_string($con, $_POST['id_mkelas']); // Ambil nilai id_mkelas dari form


    $foto = $_FILES['foto']['name'];
    $target_dir = "assets/img/user/"; // Folder tujuan upload
    $target_file = $target_dir . basename($foto);
    $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    

    // Cek apakah NIS sudah terdaftar
    $sqlCek = mysqli_query($con, "SELECT * FROM tb_siswa WHERE nis='$nis'");
    $jml = mysqli_num_rows($sqlCek);
    
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
        $query = "INSERT INTO tb_siswa (nis, nama_siswa, password, tempat_lahir, tgl_lahir, jk, alamat, foto, th_angkatan, id_mkelas, status) 
                  VALUES ('$nis', '$nama', '$password', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$alamat', '$foto', '$tahun_angkatan', '$id_mkelas', '1')";
        if (mysqli_query($con, $query)) {
            echo "<script>
            alert('Registrasi berhasil! Silakan login.');
            window.location.href = 'index.php';
            </script>";
        } else {
            echo "<script>
            alert('Registrasi gagal! Kesalahan pada database.');
            window.history.back();
            </script>";
        }
    } else {
        echo "<script>
        alert('Upload foto gagal! Periksa pengaturan folder.');
        window.history.back();
        </script>";
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register | Absensi</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/_login/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/_login/css/util.css">
    <link rel="stylesheet" type="text/css" href="assets/_login/css/main.css">
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form method="post" action="register.php" class="login100-form validate-form" enctype="multipart/form-data">
                    <span class="login100-form-title p-b-48">
                        <img src="./assets/img/LOGO1.JPG" width="100">
                    </span>
                    <span class="login100-form-title p-b-26">
                        Registrasi Siswa
                    </span>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="nis" required>
                        <span class="focus-input100" data-placeholder="NIS"></span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="nama_siswa" required>
                        <span class="focus-input100" data-placeholder="Nama Lengkap"></span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="tempat_lahir" required>
                        <span class="focus-input100" data-placeholder="Tempat Lahir"></span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="date" name="tgl_lahir" required>
                        <span class="focus-input100" data-placeholder="Tanggal Lahir"></span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <select class="input100" name="jk" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <textarea class="input100" name="alamat" required></textarea>
                        <span class="focus-input100" data-placeholder="Alamat"></span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="text" name="th_angkatan" required>
                        <span class="focus-input100" data-placeholder="Tahun Angkatan"></span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <select class="input100" name="id_mkelas" required>
                            <option value="">Pilih Kelas</option>
                            <?php
                            $kelasQuery = mysqli_query($con, "SELECT * FROM tb_mkelas");
                            while ($kelas = mysqli_fetch_assoc($kelasQuery)) {
                                echo "<option value='{$kelas['id_mkelas']}'>{$kelas['nama_kelas']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <input class="input100" type="file" name="foto" required>
                        <span class="focus-input100" data-placeholder="Foto"></span>
                    </div>

                    <div class="wrap-input100 validate-input">
                        <span class="btn-show-pass">
                            <i class="zmdi zmdi-eye"></i>
                        </span>
                        <input class="input100" type="password" name="password" required>
                        <span class="focus-input100" data-placeholder="Password"></span>
                    </div>

                    <div class="container-login100-form-btn">
                        <div class="wrap-login100-form-btn">
                            <div class="login100-form-bgbtn"></div>
                            <button type="submit" class="login100-form-btn">
                                Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="assets/_login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="assets/_login/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/_login/js/main.js"></script>
</body>
</html>
