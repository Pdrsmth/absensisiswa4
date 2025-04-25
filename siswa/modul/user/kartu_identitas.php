<?php
@session_start();
require '../../../vendor/autoload.php';
include '../../../config/db.php';
use Picqer\Barcode\BarcodeGeneratorHTML;

// Cek apakah siswa sudah login
if (!isset($_SESSION['siswa'])) {
    ?>
    <script>
        alert('Maaf! Anda Belum Login!!');
        window.location = '../../../user.php';
    </script>
    <?php
    exit;
}

// Ambil data siswa berdasarkan ID dari parameter URL
$id = $_GET['id']; // Mendapatkan ID siswa dari parameter URL
$sql = mysqli_query($con, "SELECT * FROM tb_siswa 
    INNER JOIN tb_mkelas ON tb_siswa.id_mkelas = tb_mkelas.id_mkelas
    WHERE tb_siswa.id_siswa = '$id'") or die(mysqli_error($con));
$data = mysqli_fetch_array($sql);

// Generate barcode
$generator = new BarcodeGeneratorHTML();
$barcode = $generator->getBarcode($data['nis'], $generator::TYPE_CODE_128);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Identitas Siswa</title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.min.css">
    <style>
        body {
        font-family: Arial, sans-serif;
        background: white; /* Gradasi biru tua ke biru muda */
        padding: 20px;
    }

    .card-container {
    position: relative; /* Membuat elemen absolut berada dalam konteks container */
    width: 600px;
    height: 310px;
    margin: 20px auto;
    border: 1px solid #000;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    
    background-color: #e3f2fd; 
}




        .card-header {
            background: linear-gradient(to bottom, #0a74da, #89c2d9);
            text-align: center;
            padding: 10px;
            position: relative;
        }

        .logo-container {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 70px;
            height: 70px;
        }

        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
            margin-left: 90px; /* Menyesuaikan dengan posisi logo */
        }

        .header-text h4 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header-text p {
            margin: 5px 0;
            font-size: 12px;
        }

        .card-body {
    padding: 10px 20px; /* Kurangi padding untuk menghemat ruang */
    display: flex;
    align-items: center;
}

.card-footer {
    text-align: center;
    font-size: 12px;
    padding: 5px; /* Kurangi padding pada footer */
    background-color: #f9f9f9;
    line-height: 0.4;
}


        .card-body .photo-section {
            flex: 1;
            text-align: center;
        }

        .card-body .photo-section img {
            width: 120px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }

        .card-body .data-section {
            flex: 2;
            padding-left: 20px;
        }

        

        .rules-header {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 10px; /* Jarak dari list */
    margin-top: 20px; /* Jarak dari elemen sebelumnya */
}


.rules-list {
    font-size: 14px;
    line-height: 1.8;
    margin: 0 auto; /* Rata tengah list */
    padding-left: 30px; /* Indentasi list */
    max-width: 500px; /* Lebar maksimum untuk list */
}


.data-section {
    flex: 2;
    padding-left: 20px; /* Sesuaikan padding kiri */
    margin-top: -50px; /* Geser elemen lebih ke atas */
    background-color: #e3f2fd; 
}

.data-section table {
    margin: 0; /* Hilangkan margin default tabel */
    padding: 0; /* Hilangkan padding default tabel */
    font-size: 14px; /* Sesuaikan ukuran font jika perlu */
    background-color: #e3f2fd; 
}

.data-section table th, 
.data-section table td {
    padding: 3px 5px; /* Buat padding lebih kecil untuk jarak antar baris */
    vertical-align: top; /* Teks sejajar dengan atas */
    background-color: #e3f2fd; 
}


/* Tulisan di bawah foto */
.photo-caption {
    background-color: #e3f2fd; /* Latar belakang semi-transparan */
    padding: 5px 10px; /* Jarak dalam elemen */
    border-radius: 10px; /* Sudut melengkung */
    text-align: center; /* Teks rata tengah */
    max-width: 400px; /* Lebar maksimum elemen */
    margin: 5px auto; /* Tengah secara horizontal *
    color: #fff; /* Warna teks putih */
    font-family: 'Arial', sans-serif; /* Font sederhana */
    font-size: 12px; /* Ukuran teks */
    font-weight: bold;
    line-height: 1.5; /* Jarak antar baris */
}

.photo-caption p {
    margin: 0; /* Hilangkan margin bawaan pada paragraf */
}


/* Bagian tanda tangan di pojok kanan bawah */
.footer-signature {
    position: absolute;
    bottom: 10px;
    right: 20px;
    text-align: right;
    font-size: 12px;
}
.footer-signature-container {
    text-align: left; /* Rata kiri untuk semua elemen dalam div */
    margin: 20px 0;   /* Menambahkan jarak luar untuk div */
}
.data-section {
            margin-top: -10px;
        }

        .barcode-section {
            margin-top: 5px;
            text-align: center;
        }

        .barcode-section img {
            margin-top: 10px;
        }
        .barcode-section {
    margin-top: 5px;
    text-align: center;
    display: flex;
    justify-content: center; /* Menambahkan properti untuk mengatur konten di tengah */
}




    </style>
</head>

<body>
    <!-- Kartu Tampak Depan -->
    <div class="card-container">
        <div class="card-header">
            <!-- Div untuk logo -->
            <div class="logo-container">
                <img src="../../../assets/img/SMA.png" alt="Logo">
            </div>
            <!-- Div khusus untuk tulisan -->
            <div class="header-text">
                <h4>SMA Negeri 1 Pangkalan Koto Baru</h4>
                <p>Jl. Lintas Sumbar-Riau Kec.Pangkalan Koto Baru Kab.Lima Puluh Kota Kode Pos 26272</p>
                <p>Email: sma1pangkalan@gmail.com | Telp: 081222333444</p>
            </div>
        </div>
        <div class="card-body">
            <!-- Bagian Foto Siswa -->
            <div class="photo-section">
                <img src="../../../assets/img/user/<?= $data['foto'] ?>" alt="Foto Siswa">
                <!-- Tambahkan div untuk tulisan di bawah foto -->
        <div class="photo-caption">
            <p>Berlaku Selama Menjadi Siswa</p>
        </div>
            </div>
            <!-- Bagian Data Siswa -->
            <div class="data-section">
                <table class="table table-borderless">
                    <tr>
                        <th width="180px">Nama</th>
                        <td>: <?= $data['nama_siswa'] ?></td>
                    </tr>
                    <tr>
                        <th>NIS</th>
                        <td>: <?= $data['nis'] ?></td>
                    </tr>
                    <tr>
    <th>Jenis Kelamin</th>
    <td>: <?= ($data['jk'] == 'L') ? 'Laki-laki' : 'Perempuan'; ?></td>
</tr>

                        <th>Tempat, Tanggal Lahir</th>
                        <td>: <?= $data['tempat_lahir'] ?>, <?= $data['tgl_lahir'] ?></td>
                    </tr>
                    <tr>
                                                    <th>Tahun Angkatan</th>
                                                    <td>: <?= $data['th_angkatan'] ?></td>
                                                </tr>
                    <tr>
                        <th>Alamat</th>
                        <td>: <?= $data['alamat'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer">
        
</div>
    </div>

    <!-- Kartu Tampak Belakang -->
    <div class="card-container">
        <div class="card-header">
            <!-- Div untuk logo -->
            <div class="logo-container">
                <img src="../../../assets/img/SMA.png" alt="Logo">
            </div>
            <!-- Div khusus untuk tulisan -->
            <div class="header-text">
                <h4>SMA Negeri 1 Pangkalan Koto Baru</h4>
                <p>Jl. Lintas Sumbar-Riau Kec.Pangkalan Koto Baru Kab.Lima Puluh Kota Kode Pos 26272</p>
                <p>Email: sma1pangkalan@gmail.com | Telp: 081222333444</p>
            </div>
        </div>
        <div class="card-body">
    <!-- Tambahkan elemen header Tata Tertib -->
    <ul class="rules-list">
        <li>Bertaqwa kepada Tuhan Yang Maha Esa.</li>
        <li>Menjaga persatuan dan kerukunan antar pelajar.</li>
        <li>Belajar hidup berorganisasi untuk meningkatkan mental, moral, dan budi pekerti yang luhur.</li>
        <li>Menjadi generasi penerus bangsa yang kreatif, aktif, dan bertanggung jawab.</li>
    </ul>
</div>
<div class="barcode-section">
            <?= $barcode ?>
        </div>


    </div>

    <!-- Tombol Kembali -->
    <div class="back-button" style="text-align: center; margin-top: 20px;">
        <a href="profil_siswa.php" class="btn btn-primary">Kembali</a>
    </div>
    
</body>

</html>
