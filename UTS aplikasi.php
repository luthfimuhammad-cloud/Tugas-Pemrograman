<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

$total_pemasukan   = 0;
$total_pengeluaran = 0;
$saldo_kas         = 0;
$query_kas         = false;

if (isset($koneksi) && $koneksi) {
    $query_masuk     = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total FROM kas WHERE jenis='masuk'");
    $data_masuk      = $query_masuk ? mysqli_fetch_assoc($query_masuk) : null;
    $total_pemasukan = $data_masuk['total'] ?? 0;

    $query_keluar      = mysqli_query($koneksi, "SELECT SUM(jumlah) AS total FROM kas WHERE jenis='keluar'");
    $data_keluar       = $query_keluar ? mysqli_fetch_assoc($query_keluar) : null;
    $total_pengeluaran = $data_keluar['total'] ?? 0;
    $saldo_kas = $total_pemasukan - $total_pengeluaran;
    $query_kas = mysqli_query($koneksi, "SELECT * FROM kas ORDER BY tanggal DESC, id DESC");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kas Keuangan Sederhana - KasKuApp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #f8fafc;
            --surface: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --success: #10b981;
            --success-bg: #ecfdf5;
            --danger: #ef4444;
            --danger-bg: #fef2f2;
            --warning: #f59e0b;
            --warning-bg: #fffbeb;
            --radius: 12px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-main); line-height: 1.5; }

        .navbar {
            background-color: #0f172a; color: #fff; padding: 1rem 2rem;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: var(--shadow-md); position: sticky; top: 0; z-index: 100;
        }
        .logo { display: flex; align-items: center; gap: 12px; font-size: 1.25rem; font-weight: 700; }
        .logo i { background: linear-gradient(135deg, #3b82f6, #2563eb); padding: 8px; border-radius: 8px; color: white; }
        .logo span { color: #38bdf8; }
        .user-info { display: flex; align-items: center; gap: 20px; font-size: 0.9rem; }
        .user-info span { color: #94a3b8; }
        .user-info strong { color: #f8fafc; }

        .btn-logout {
            background-color: rgba(239, 68, 68, 0.15); color: #fca5a5; padding: 8px 16px;
            text-decoration: none; border-radius: 8px; font-size: 0.85rem; font-weight: 600;
            transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 6px;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .btn-logout:hover { background-color: var(--danger); color: white; }

        .container { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; }
        .cards-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .card { background: var(--surface); border-radius: var(--radius); padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); }
        .card-icon { width: 56px; height: 56px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.35rem; flex-shrink: 0; }
        
        .card-pemasukan .card-icon { background: var(--success-bg); color: var(--success); }
        .card-pengeluaran .card-icon { background: var(--danger-bg); color: var(--danger); }
        .card-saldo .card-icon { background: #eff6ff; color: var(--primary); }

        .card-details p { font-size: 0.85rem; font-weight: 500; color: var(--text-muted); text-transform: uppercase; }
        .card-details h3 { font-size: 1.5rem; font-weight: 700; margin-top: 4px; color: var(--text-main); }

        .table-container { background: var(--surface); border-radius: var(--radius); padding: 1.5rem; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); }
        .table-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem; }
        .table-header h3 { font-size: 1.15rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
        .table-header h3 i { color: var(--primary); }

        .btn-primary {
            background-color: var(--primary); color: white; border: none; padding: 10px 18px;
            border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem;
            display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s ease;
        }
        .btn-primary:hover { background-color: var(--primary-hover); transform: translateY(-1px); }

        .table-wrapper { overflow-x: auto; border-radius: 8px; border: 1px solid var(--border-color); }
        table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.925rem; }
        th, td { padding: 14px 18px; border-bottom: 1px solid var(--border-color); }
        th { background-color: #f8fafc; color: var(--text-muted); font-weight: 600; font-size: 0.8rem; text-transform: uppercase; }
        tbody tr:hover { background-color: #f8fafc; }

        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
        .badge-masuk { background: var(--success-bg); color: var(--success); }
        .badge-keluar { background: var(--danger-bg); color: var(--danger); }

        .text-masuk { color: var(--success); font-weight: 600; }
        .text-keluar { color: var(--danger); font-weight: 600; }

        .action-btn { display: flex; gap: 8px; }
        .btn-edit, .btn-delete { border: none; padding: 6px 10px; border-radius: 6px; cursor: pointer; font-size: 0.85rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; }
        .btn-edit { background-color: var(--warning-bg); color: var(--warning); border: 1px solid rgba(245, 158, 11, 0.3); }
        .btn-edit:hover { background-color: var(--warning); color: white; }
        .btn-delete { background-color: var(--danger-bg); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }
        .btn-delete:hover { background-color: var(--danger); color: white; }

        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.5); backdrop-filter: blur(4px); justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: var(--surface); width: 100%; max-width: 480px; border-radius: var(--radius); padding: 1.75rem; box-shadow: var(--shadow-lg); border: 1px solid var(--border-color); }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; }
        .modal-header h4 { font-size: 1.15rem; font-weight: 700; }
        .close-btn { font-size: 1.25rem; cursor: pointer; color: var(--text-muted); width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        .close-btn:hover { background: #f1f5f9; color: var(--text-main); }

        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 14px; border: 1px solid var(--border-color); border-radius: 8px; outline: none; font-size: 0.9rem; background: #f8fafc; }
        .form-group input:focus, .form-group select:focus { background: #fff; border-color: var(--primary); }

        .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 1.75rem; }
        .btn-secondary { background: #f1f5f9; color: var(--text-muted); border: 1px solid var(--border-color); padding: 10px 16px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem; }
        .btn-secondary:hover { background: #e2e8f0; color: var(--text-main); }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo">
            <i class="fa-solid fa-wallet"></i>
            <div>KasKu<span>App</span></div>
        </div>
        <div class="user-info">
            <span>Selamat datang, <strong><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? 'Admin Kas') ?></strong></span>
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </div>
    </header>
    <main class="container">
        <section class="cards-grid">
            <div class="card card-pemasukan">
                <div class="card-icon"><i class="fa-solid fa-arrow-down-left"></i></div>
                <div class="card-details">
                    <p>Total Pemasukan</p>
                    <h3 id="totalPemasukan">Rp <?= number_format($total_pemasukan, 0, ',', '.') ?></h3>
                </div>
            </div>
            <div class="card card-pengeluaran">
                <div class="card-icon"><i class="fa-solid fa-arrow-up-right"></i></div>
                <div class="card-details">
                    <p>Total Pengeluaran</p>
                    <h3 id="totalPengeluaran">Rp <?= number_format($total_pengeluaran, 0, ',', '.') ?></h3>
                </div>
            </div>
            <div class="card card-saldo">
                <div class="card-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                <div class="card-details">
                    <p>Saldo Kas Saat Ini</p>
                    <h3 id="totalSaldo">Rp <?= number_format($saldo_kas, 0, ',', '.') ?></h3>
                </div>
            </div>
        </section>
        <section class="table-container">
            <div class="table-header">
                <h3><i class="fa-solid fa-receipt"></i> Riwayat Transaksi Kas</h3>
                <button class="btn-primary" onclick="openModal('add')">
                    <i class="fa-solid fa-plus"></i> Tambah Transaksi
                </button>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 130px;">Tanggal</th>
                            <th>Keterangan</th>
                            <th style="width: 140px;">Jenis</th>
                            <th style="width: 160px;">Jumlah</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if ($query_kas && mysqli_num_rows($query_kas) > 0): 
                            while ($row = mysqli_fetch_assoc($query_kas)): 
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['tanggal']) ?></td>
                            <td><?= htmlspecialchars($row['keterangan']) ?></td>
                            <td>
                                <?php if ($row['jenis'] === 'masuk'): ?>
                                    <span class="badge badge-masuk"><i class="fa-solid fa-circle-down"></i> Pemasukan</span>
                                <?php else: ?>
                                    <span class="badge badge-keluar"><i class="fa-solid fa-circle-up"></i> Pengeluaran</span>
                                <?php endif; ?>
                            </td>
                            <td class="<?= $row['jenis'] === 'masuk' ? 'text-masuk' : 'text-keluar' ?>">
                                <?= $row['jenis'] === 'masuk' ? '+ ' : '- ' ?>Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                            </td>
                            <td class="action-btn" style="justify-content: center;">
                                <!-- Update: Trigger Modal Form Edit -->
                                <button class="btn-edit" title="Edit" onclick="openModal('edit', <?= $row['id'] ?>, '<?= $row['tanggal'] ?>', '<?= htmlspecialchars(addslashes($row['keterangan'])) ?>', '<?= $row['jenis'] ?>', <?= $row['jumlah'] ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <!-- Delete: Trigger Hapus ID -->
                                <a href="proses_hapus.php?id=<?= $row['id'] ?>" class="btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php 
                            endwhile; 
                        else: 
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 20px;">Belum ada data transaksi.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <div class="modal" id="modalForm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 id="modalTitle">Tambah Transaksi Kas</h4>
                <span class="close-btn" onclick="closeModal()">&times;</span>
            </div>
            
            <form id="kasForm" action="proses_simpan.php" method="POST">
                <input type="hidden" name="id" id="transaksiId">

                <div class="form-group">
                    <label for="tanggal">Tanggal Transaksi</label>
                    <input type="date" id="tanggal" name="tanggal" required>
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan / Deskripsi</label>
                    <input type="text" id="keterangan" name="keterangan" placeholder="Contoh: Pembelian konsumsi rapat" required>
                </div>

                <div class="form-group">
                    <label for="jenis">Jenis Transaksi</label>
                    <select id="jenis" name="jenis" required>
                        <option value="masuk">Pemasukan (+)</option>
                        <option value="keluar">Pengeluaran (-)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah Nominal (Rp)</label>
                    <input type="number" id="jumlah" name="jumlah" min="1" placeholder="Contoh: 50000" required>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalForm');
        const modalTitle = document.getElementById('modalTitle');
        const kasForm = document.getElementById('kasForm');

        function openModal(mode, id = '', tanggal = '', keterangan = '', jenis = 'masuk', jumlah = '') {
            modal.style.display = 'flex';

            if (mode === 'add') {
                modalTitle.innerText = 'Tambah Transaksi Kas';
                kasForm.action = 'proses_simpan.php';
                kasForm.reset();
                document.getElementById('transaksiId').value = '';
            } else if (mode === 'edit') {
                modalTitle.innerText = 'Edit Transaksi Kas';
                kasForm.action = 'proses_edit.php';

                document.getElementById('transaksiId').value = id;
                document.getElementById('tanggal').value = tanggal;
                document.getElementById('keterangan').value = keterangan;
                document.getElementById('jenis').value = jenis;
                document.getElementById('jumlah').value = jumlah;
            }
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target === modal) {
                closeModal();
            }
        };
    </script>
</body>
</html>
