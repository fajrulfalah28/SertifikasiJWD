<?php
session_start();

// ── Helper JSON ─────────────────────────────────────────────
define('DATA_DIR',  __DIR__ . '/data');
define('DATA_FILE', DATA_DIR . '/pendaftar.json');

function baca_pendaftar(): array {
    if (!file_exists(DATA_FILE)) return [];
    $json = file_get_contents(DATA_FILE);
    return json_decode($json, true) ?? [];
}

function simpan_pendaftar(array $data): void {
    if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0777, true);
    file_put_contents(DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
// ────────────────────────────────────────────────────────────

// Routing halaman
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Proses form pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'daftar') {
        $errors = [];

        // Validasi nama
        $nama = trim($_POST['nama'] ?? '');
        if (empty($nama)) {
            $errors[] = 'Nama wajib diisi!';
        }

        // Validasi email
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            $errors[] = 'Email wajib diisi!';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid!';
        }

        // Validasi nomor HP
        $no_hp = trim($_POST['no_hp'] ?? '');
        if (empty($no_hp)) {
            $errors[] = 'Nomor HP wajib diisi!';
        } elseif (!preg_match('/^[0-9]+$/', $no_hp)) {
            $errors[] = 'Nomor HP hanya boleh berisi angka!';
        }

        // Validasi semester
        $semester = intval($_POST['semester'] ?? 0);
        if ($semester < 1 || $semester > 8) {
            $errors[] = 'Semester harus antara 1 sampai 8!';
        }

        // Validasi IPK
        $ipk = floatval($_POST['ipk'] ?? 0);
        if ($ipk < 3.0) {
            $errors[] = 'IPK harus minimal 3.0 untuk mendaftar beasiswa!';
        }

        // Validasi pilihan beasiswa
        $pilihan_beasiswa = $_POST['pilihan_beasiswa'] ?? '';
        if (empty($pilihan_beasiswa)) {
            $errors[] = 'Pilihan beasiswa wajib dipilih!';
        }

        // Validasi upload berkas
        $berkas_nama = '';
        if (isset($_FILES['berkas']) && $_FILES['berkas']['error'] === UPLOAD_ERR_OK) {
            $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png', 'zip'];
            $file_ext = strtolower(pathinfo($_FILES['berkas']['name'], PATHINFO_EXTENSION));
            if (!in_array($file_ext, $allowed_ext)) {
                $errors[] = 'Format berkas harus PDF, JPG, PNG, atau ZIP!';
            } else {
                // Buat folder uploads jika belum ada
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true);
                }
                $berkas_nama = time() . '_' . $_FILES['berkas']['name'];
                move_uploaded_file($_FILES['berkas']['tmp_name'], 'uploads/' . $berkas_nama);
            }
        } else {
            $errors[] = 'Berkas syarat wajib diupload!';
        }

        if (empty($errors)) {
            // Baca data lama dari JSON, tambah entry baru, lalu simpan kembali
            $semua = baca_pendaftar();
            $data = [
                'id'               => count($semua) + 1,
                'nama'             => $nama,
                'email'            => $email,
                'no_hp'            => $no_hp,
                'semester'         => $semester,
                'ipk'              => $ipk,
                'pilihan_beasiswa' => $pilihan_beasiswa,
                'berkas'           => $berkas_nama,
                'status_ajuan'     => 'Belum Di Verifikasi',
                'tanggal_daftar'   => date('d-m-Y H:i:s')
            ];
            $semua[] = $data;
            simpan_pendaftar($semua);
            $_SESSION['success'] = 'Pendaftaran beasiswa berhasil!';
            header('Location: index.php?page=hasil');
            exit;
        } else {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            header('Location: index.php?page=daftar');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KampusKuAja - Sistem Pendaftaran Beasiswa Online untuk mahasiswa berprestasi">
    <title>KampusKuAja - Sistem Pendaftaran Beasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'jakarta': ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        'kampus': {
                            'primary': '#2f6b3f',
                            'secondary': '#7fb77e',
                            'light': '#e8f5e9',
                            'dark': '#1b4d2e',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { min-height: 100vh; display: flex; flex-direction: column; }
        .main-content { flex: 1; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #7fb77e; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #2f6b3f; }

        /* File input custom */
        .file-input-wrapper { position: relative; overflow: hidden; cursor: pointer; }
        .file-input-wrapper input[type=file] {
            position: absolute; left: 0; top: 0; opacity: 0;
            width: 100%; height: 100%; cursor: pointer;
        }



        /* Disabled state styling */
        .field-disabled {
            opacity: 0.5;
            pointer-events: none;
        }

    </style>
</head>
<body class="bg-gray-50 font-jakarta">

    <!-- Header / Navbar -->
    <header class="bg-kampus-primary border-b-4 border-kampus-secondary shadow-lg">
        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between flex-wrap gap-4">
            <a href="index.php" class="text-white text-2xl font-bold tracking-tight hover:opacity-90 transition-opacity">
                KampusKuAja
            </a>
            <nav class="flex items-center gap-4">
                <a href="index.php?page=daftar"
                   class="<?php echo ($page === 'daftar' || $page === 'home') ? 'bg-white text-black font-bold' : 'border border-white text-white hover:bg-white hover:text-kampus-primary font-normal'; ?> px-8 py-3 rounded-full text-base transition-all duration-300 inline-block">
                    Daftar
                </a>
                <a href="index.php?page=hasil"
                   class="<?php echo $page === 'hasil' ? 'bg-white text-black font-bold' : 'border border-white text-white hover:bg-white hover:text-kampus-primary font-normal'; ?> px-8 py-3 rounded-full text-base transition-all duration-300 inline-block">
                    Hasil
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <?php
        switch ($page) {
            case 'hasil':
                include 'pages/hasil.php';
                break;
            case 'daftar':
            case 'home':
            default:
                include 'pages/daftar.php';
                break;
        }
        ?>
    </main>

    <!-- Footer -->
    <footer class="bg-kampus-primary border-kampus-secondary">
        <div class="max-w-7xl mx-auto py-4 text-center">
            <p class="text-white text-sm">Copyright @kampuskuaja.ac.id</p>
        </div>
    </footer>

</body>
</html>
