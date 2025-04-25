<?php
@session_start();
include '../../../config/db.php';

// Cek apakah siswa sudah login
if (!isset($_SESSION['siswa'])) {
    ?> 
    <script>
        alert('Maaf! Anda belum login.');
        window.location='../../../user.php';
    </script>
    <?php
    exit;
}

// Ambil ID siswa dari sesi
$id_login = @$_SESSION['siswa'];

// Ambil data siswa dari database
$sql = mysqli_query($con, "SELECT * FROM tb_siswa 
    INNER JOIN tb_mkelas ON tb_siswa.id_mkelas = tb_mkelas.id_mkelas
    WHERE tb_siswa.id_siswa = '$id_login'") or die(mysqli_error($con));
$data = mysqli_fetch_array($sql);

// Jika tombol simpan ditekan
if (isset($_POST['simpan'])) {
    $nama_siswa = mysqli_real_escape_string($con, $_POST['nama_siswa']);
    $nis = mysqli_real_escape_string($con, $_POST['nis']);
    $jk = mysqli_real_escape_string($con, $_POST['jk']);
    $tempat_lahir = mysqli_real_escape_string($con, $_POST['tempat_lahir']);
    $tgl_lahir = mysqli_real_escape_string($con, $_POST['tgl_lahir']);
    $alamat = mysqli_real_escape_string($con, $_POST['alamat']);
    
    // Cek apakah ada file foto yang diupload
    if ($_FILES['foto']['name']) {
        $foto = $_FILES['foto']['name'];
        $tmp_name = $_FILES['foto']['tmp_name'];
        $folder = "../../../assets/img/user/";

        // Hapus foto lama jika ada
        if (file_exists($folder . $data['foto']) && $data['foto'] != '') {
            unlink($folder . $data['foto']);
        }

        // Simpan foto baru
        move_uploaded_file($tmp_name, $folder . $foto);

        // Update data termasuk foto
        $update = mysqli_query($con, "UPDATE tb_siswa SET 
            nama_siswa='$nama_siswa', 
            nis='$nis', 
            jk='$jk', 
            tempat_lahir='$tempat_lahir', 
            tgl_lahir='$tgl_lahir', 
            alamat='$alamat', 
            foto='$foto'
            WHERE id_siswa='$id_login'");
    } else {
        // Update data tanpa foto
        $update = mysqli_query($con, "UPDATE tb_siswa SET 
            nama_siswa='$nama_siswa', 
            nis='$nis', 
            jk='$jk', 
            tempat_lahir='$tempat_lahir', 
            tgl_lahir='$tgl_lahir', 
            alamat='$alamat'
            WHERE id_siswa='$id_login'");
    }

    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='profil_siswa.php';</script>";
    } else {
        echo "<script>alert('Profil gagal diperbarui!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Edit Profil | Aplikasi Presensi</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="../../../assets/img/icon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../assets/css/atlantis.min.css">
</head>

<body>
    <div class="wrapper">
        <div class="main-header">
            <!-- Add your header here -->
        </div>

        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Edit Profil</h4>
                                </div>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label>Nama Lengkap</label>
                                            <input type="text" name="nama_siswa" class="form-control" value="<?= $data['nama_siswa'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>NIS</label>
                                            <input type="text" name="nis" class="form-control" value="<?= $data['nis'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <select name="jk" class="form-control" required>
                                                <option value="L" <?= $data['jk'] == 'L' ? 'selected' : '' ?>>Laki-Laki</option>
                                                <option value="P" <?= $data['jk'] == 'P' ? 'selected' : '' ?>>Perempuan</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" class="form-control" value="<?= $data['tempat_lahir'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Tanggal Lahir</label>
                                            <input type="date" name="tgl_lahir" class="form-control" value="<?= $data['tgl_lahir'] ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <textarea name="alamat" class="form-control" required><?= $data['alamat'] ?></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Foto</label><br>
                                            <img src="../../../assets/img/user/<?= $data['foto'] ?>" alt="Foto Siswa" class="img-thumbnail mb-3" width="150">
                                            <input type="file" name="foto" class="form-control">
                                        </div>
                                        <div class="form-group text-right">
                                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                            <a href="profil_siswa.php" class="btn btn-secondary">Batal</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="footer">
                <div class="container">
                    <div class="copyright ml-auto">
                        &copy; <?= date('Y'); ?> Absensi Siswa
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../../../assets/js/core/jquery.3.2.1.min.js"></script>
    <script src="../../../assets/js/core/bootstrap.min.js"></script>
    <script src="../../../assets/js/atlantis.min.js"></script>
</body>

</html>
