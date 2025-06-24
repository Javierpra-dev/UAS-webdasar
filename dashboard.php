<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$username = $_SESSION['username'];

// Counter login per user
if (!isset($_SESSION['login_counter'])) {
    $_SESSION['login_counter'] = [];
}
if (!isset($_SESSION['login_counter'][$username])) {
    $_SESSION['login_counter'][$username] = 1;
} else {
    if (!isset($_SESSION['counter_incremented'])) {
        $_SESSION['login_counter'][$username]++;
        $_SESSION['counter_incremented'] = true;
    }
}
$login_ke = $_SESSION['login_counter'][$username];

// Reset flag jika reload tanpa login ulang
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_GET['ubah']) && !isset($_GET['hapus'])) {
    unset($_SESSION['counter_incremented']);
}

// Inisialisasi daftar user jika belum ada
if (!isset($_SESSION['daftar'][$username])) {
    $_SESSION['daftar'][$username] = [];
}

// Hapus data
if (isset($_GET['hapus'])) {
    $idx = (int)$_GET['hapus'];
    if (isset($_SESSION['daftar'][$username][$idx])) {
        array_splice($_SESSION['daftar'][$username], $idx, 1);
    }
    header('Location: dashboard.php');
    exit;
}

// Siapkan data untuk ubah
$edit_mode = false;
$edit_nama = '';
$edit_umur = '';
$edit_idx = null;
if (isset($_GET['ubah'])) {
    $idx = (int)$_GET['ubah'];
    if (isset($_SESSION['daftar'][$username][$idx])) {
        $edit_mode = true;
        $edit_nama = $_SESSION['daftar'][$username][$idx]['nama'];
        $edit_umur = $_SESSION['daftar'][$username][$idx]['umur'];
        $edit_idx = $idx;
    }
}

// Pesan error
$error = '';

// Proses tambah/ubah data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama']) && isset($_POST['umur'])) {
    $nama = trim($_POST['nama']);
    $umur = (int)$_POST['umur'];
    if ($nama !== '' && $umur > 0) {
        if (isset($_POST['edit_idx']) && $_POST['edit_idx'] !== '') {
            // Proses update
            $idx = (int)$_POST['edit_idx'];
            if (isset($_SESSION['daftar'][$username][$idx])) {
                // Cek duplikat selain index yang sedang diubah
                $duplikat = false;
                foreach ($_SESSION['daftar'][$username] as $i => $row) {
                    if ($i !== $idx && strtolower($row['nama']) === strtolower($nama) && $row['umur'] == $umur) {
                        $duplikat = true;
                        break;
                    }
                }
                if ($duplikat) {
                    $error = 'Data dengan nama dan umur yang sama sudah ada!';
                } else {
                    $_SESSION['daftar'][$username][$idx] = [
                        'nama' => $nama,
                        'umur' => $umur
                    ];
                    header('Location: dashboard.php');
                    exit;
                }
            }
        } else {
            // Proses tambah
            $duplikat = false;
            foreach ($_SESSION['daftar'][$username] as $row) {
                if (strtolower($row['nama']) === strtolower($nama) && $row['umur'] == $umur) {
                    $duplikat = true;
                    break;
                }
            }
            if ($duplikat) {
                $error = 'Data dengan nama dan umur yang sama sudah ada!';
            } else {
                $_SESSION['daftar'][$username][] = [
                    'nama' => $nama,
                    'umur' => $umur
                ];
                header('Location: dashboard.php');
                exit;
            }
        }
    }
}

$daftar = $_SESSION['daftar'][$username];
function keteranganUmur($umur) {
    if ($umur >= 0 && $umur <= 10) return 'Anak-anak';
    else if ($umur >= 11 && $umur <= 20) return 'Remaja';
    else if ($umur >= 21 && $umur <= 40) return 'Dewasa';
    else if ($umur > 40) return 'Tua';
    else return '-';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="welcome">Selamat datang <?= htmlspecialchars($username) ?> Ke-<?= $login_ke ?></div>
  <div class="card">
    <?php if ($error): ?>
      <div style="color:red; font-size:0.95em; margin-bottom:8px; text-align:center; font-family:serif;">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>
    <form method="POST" action="">
      <table style="border:none; background:none; box-shadow:none; width:100%;">
        <tr><td colspan="2" class="title">DAFTAR</td></tr>
        <tr>
          <td style="text-align:left; width:60px;">Nama</td>
          <td style="text-align:right;"><input type="text" name="nama" required value="<?= htmlspecialchars($edit_nama) ?>"></td>
        </tr>
        <tr>
          <td style="text-align:left;">Umur</td>
          <td style="text-align:right;"><input type="number" name="umur" min="1" required value="<?= htmlspecialchars($edit_umur) ?>"></td>
        </tr>
        <?php if ($edit_mode): ?>
        <input type="hidden" name="edit_idx" value="<?= $edit_idx ?>">
        <?php endif; ?>
        <tr>
          <td colspan="2" class="full-row" style="text-align:center;">
            <input type="submit" value="<?= $edit_mode ? 'UBAH' : 'PROSES' ?>" class="btn-proses">
            <a href="logout.php" class="btn-logout">LOGOUT</a>
            <?php if ($edit_mode): ?>
              <a href="dashboard.php" class="btn-logout" style="background:#888; color:#fff; border-color:#888;">BATAL</a>
            <?php endif; ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
  <div class="table-card">
    <table>
      <tr>
        <th>Nama</th>
        <th>Umur</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
      <?php foreach($daftar as $i => $row): ?>
      <tr>
        <td><?= htmlspecialchars($row['nama']) ?></td>
        <td><?= htmlspecialchars($row['umur']) ?></td>
        <td><?= keteranganUmur($row['umur']) ?></td>
        <td>
          <a href="?hapus=<?= $i ?>" class="link-aksi" onclick="return confirm('Yakin hapus data ini?')">hapus</a>
          <a href="?ubah=<?= $i ?>" class="link-aksi">ubah</a>
        </td>
      </tr>
      <?php endforeach; ?>
      <?php if (count($daftar) == 0): ?>
      <tr><td colspan="4" class="full-row">Belum ada data pendaftar.</td></tr>
      <?php endif; ?>
    </table>
  </div>
</body>
</html>