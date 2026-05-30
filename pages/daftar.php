<?php
// Ambil error dan old input dari session
$errors = $_SESSION['errors'] ?? [];
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

// IPK random dari sistem (antara 2.50 – 4.00, 2 desimal)
// Disimpan di session agar konsisten selama sesi berlangsung
if (!isset($_SESSION['ipk_system'])) {
    $ipk_raw = mt_rand(250, 400) / 100;
    $_SESSION['ipk_system'] = $ipk_raw;
}
$ipk_system = $_SESSION['ipk_system'];

// Daftar beasiswa
$daftar_beasiswa = [
    'Beasiswa Akademik' => [
        'syarat' => 'IPK minimal 3.5, Semester minimal 3',
        'ipk_min' => 3.5,
    ],
    'Beasiswa Non-Akademik' => [
        'syarat' => 'IPK minimal 3.0, Semester minimal 2',
        'ipk_min' => 3.0,
    ],
    'Beasiswa Ekonomi (BBM)' => [
        'syarat' => 'IPK minimal 3.0, Semester minimal 2',
        'ipk_min' => 3.0,
    ],
    'Beasiswa PPA (Peningkatan Prestasi Akademik)' => [
        'syarat' => 'IPK minimal 3.25, Semester minimal 3',
        'ipk_min' => 3.25,
    ],
];

// Cek apakah IPK memenuhi syarat (>= 3.0)
$ipk_eligible = $ipk_system >= 3.0;

// Encode data beasiswa untuk JS
$beasiswa_js = json_encode($daftar_beasiswa);
?>

<div class="max-w-5xl mx-auto px-4 py-10">

    <!-- Judul Halaman -->
    <h1 class="text-4xl font-bold text-center text-gray-900 mb-10">
        Daftar Beasiswa
    </h1>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
    <div class="mb-6 bg-red-50 border border-red-300 rounded-xl p-5" id="error-alert">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h4 class="font-bold text-red-700 text-base">Pendaftaran Gagal!</h4>
                <ul class="mt-2 space-y-1">
                    <?php foreach ($errors as $error): ?>
                    <li class="text-red-600 text-sm flex items-center gap-1.5">
                        <span class="w-1 h-1 bg-red-400 rounded-full"></span>
                        <?php echo htmlspecialchars($error); ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Form Pendaftaran -->
    <div>
        <form action="index.php?page=daftar" method="POST" enctype="multipart/form-data" id="formBeasiswa">
            <input type="hidden" name="action" value="daftar">
            <input type="hidden" name="ipk" id="ipk_hidden" value="<?php echo $ipk_system; ?>">

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300">
                <!-- Form Header -->
                <div class="bg-kampus-primary border-b-4 border-kampus-secondary px-6 py-4">
                    <h2 class="text-white font-bold text-lg">Registrasi Beasiswa</h2>
                </div>

                <!-- Form Fields -->
                <div class="px-6 py-5 space-y-5">

                    <!-- Nama -->
                    <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-16">
                        <label for="nama" class="font-bold text-base text-gray-900 md:w-44 shrink-0">Masukkan Nama</label>
                        <input type="text" id="nama" name="nama"
                            value="<?php echo htmlspecialchars($old['nama'] ?? ''); ?>"
                            placeholder="Masukkan nama lengkap Anda"
                            class="flex-1 bg-gray-100 rounded-lg p-3 text-base text-gray-900 border border-transparent focus:border-kampus-primary focus:bg-white focus:ring-2 focus:ring-kampus-light outline-none transition-all duration-300"
                            required>
                    </div>

                    <!-- Email -->
                    <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-16">
                        <label for="email" class="font-bold text-base text-gray-900 md:w-44 shrink-0">Masukkan Email</label>
                        <input type="email" id="email" name="email"
                            value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                            placeholder="contoh@email.com"
                            class="flex-1 bg-gray-100 rounded-lg p-3 text-base text-gray-900 border border-transparent focus:border-kampus-primary focus:bg-white focus:ring-2 focus:ring-kampus-light outline-none transition-all duration-300"
                            required>
                    </div>

                    <!-- Nomor HP -->
                    <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-16">
                        <label for="no_hp" class="font-bold text-base text-gray-900 md:w-44 shrink-0">Nomor Handphone</label>
                        <input type="text" id="no_hp" name="no_hp"
                            value="<?php echo htmlspecialchars($old['no_hp'] ?? ''); ?>"
                            placeholder="Contoh: 08123456789"
                            pattern="[0-9]+"
                            title="Hanya boleh berisi angka"
                            class="flex-1 bg-gray-100 rounded-lg p-3 text-base text-gray-900 border border-transparent focus:border-kampus-primary focus:bg-white focus:ring-2 focus:ring-kampus-light outline-none transition-all duration-300"
                            required
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <!-- Semester -->
                    <div class="flex flex-col md:flex-row md:items-center gap-3 md:gap-16">
                        <label for="semester" class="font-bold text-base text-gray-900 md:w-44 shrink-0">Semester Saat Ini</label>
                        <select id="semester" name="semester"
                            class="flex-1 bg-gray-100 rounded-lg p-3 text-base text-gray-900 border border-transparent focus:border-kampus-primary focus:bg-white focus:ring-2 focus:ring-kampus-light outline-none transition-all duration-300 cursor-pointer"
                            required>
                            <option value="" disabled <?php echo empty($old['semester']) ? 'selected' : ''; ?>>-- Pilih Semester --</option>
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo (isset($old['semester']) && $old['semester'] == $i) ? 'selected' : ''; ?>>
                                Semester <?php echo $i; ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- IPK Terakhir (Auto, muncul setelah nama-semester terisi) -->
                    <div id="ipk-row" class="<?php echo empty($old) ? 'hidden' : ''; ?> flex flex-col md:flex-row md:items-center gap-3 md:gap-16 transition-all duration-500">
                        <label for="ipk_display" class="font-bold text-base text-gray-900 md:w-44 shrink-0">IPK Terakhir</label>
                        <div class="flex-1 relative">
                            <input type="text" id="ipk_display"
                                value="<?php echo number_format($ipk_system, 2); ?>"
                                class="w-full bg-gray-100 rounded-lg p-3 text-base font-semibold border border-transparent outline-none cursor-not-allowed
                                <?php echo $ipk_eligible ? 'text-kampus-primary' : 'text-red-600 border-red-300 bg-red-50'; ?>"
                                readonly>
                            <?php if ($ipk_eligible): ?>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Memenuhi Syarat
                            </span>
                            <?php else: ?>
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                Tidak Memenuhi Syarat
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Warning jika IPK < 3.0 -->
                    <?php if (!$ipk_eligible && !empty($old)): ?>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 animate-slideDown">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-red-700 font-semibold text-sm">IPK Anda di bawah 3.0 — Anda tidak dapat melanjutkan pendaftaran beasiswa.</p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Warning IPK di bawah 3.0 (JS-driven) -->
                    <div id="ipk-warning" class="hidden bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-red-700 font-semibold text-sm">IPK Anda di bawah 3.0 — Anda tidak dapat melanjutkan pendaftaran beasiswa.</p>
                        </div>
                    </div>

                    <!-- Pilihan Beasiswa (disabled jika IPK < 3.0) -->
                    <div id="beasiswa-row" class="flex flex-col md:flex-row md:items-center gap-3 md:gap-16 <?php echo !$ipk_eligible ? 'field-disabled' : ''; ?>">
                        <label for="pilihan_beasiswa" class="font-bold text-base text-gray-900 md:w-44 shrink-0">Pilihan Beasiswa</label>
                        <select id="pilihan_beasiswa" name="pilihan_beasiswa"
                            class="flex-1 bg-gray-100 rounded-lg p-3 text-base text-gray-900 border border-transparent focus:border-kampus-primary focus:bg-white focus:ring-2 focus:ring-kampus-light outline-none transition-all duration-300 cursor-pointer"
                            <?php echo !$ipk_eligible ? 'disabled' : ''; ?>
                            required>
                            <option value="" disabled <?php echo empty($old['pilihan_beasiswa']) ? 'selected' : ''; ?>>-- Pilih Jenis Beasiswa --</option>
                            <?php foreach ($daftar_beasiswa as $nama => $info): ?>
                                <?php if ($ipk_system >= $info['ipk_min']): ?>
                                <option value="<?php echo htmlspecialchars($nama); ?>" <?php echo (isset($old['pilihan_beasiswa']) && $old['pilihan_beasiswa'] === $nama) ? 'selected' : ''; ?>>
                                    <?php echo $nama . ' (Min. IPK: ' . $info['ipk_min'] . ')'; ?>
                                </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Upload Berkas (disabled jika IPK < 3.0) -->
                    <div id="upload-row" class="flex flex-col md:flex-row md:items-center gap-3 md:gap-16 <?php echo !$ipk_eligible ? 'field-disabled' : ''; ?>">
                        <label class="font-bold text-base text-gray-900 md:w-44 shrink-0">Upload Berkas Syarat</label>
                        <div class="flex-1">
                            <label class="file-input-wrapper bg-kampus-primary hover:bg-kampus-dark text-white font-bold text-base px-8 py-3 rounded-full w-full text-center flex items-center justify-center gap-2 transition-all duration-300 cursor-pointer <?php echo !$ipk_eligible ? 'opacity-50 cursor-not-allowed' : ''; ?>">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span id="file-label">Choose File</span>
                                <input type="file" name="berkas" id="berkas"
                                    accept=".pdf,.jpg,.jpeg,.png,.zip"
                                    <?php echo !$ipk_eligible ? 'disabled' : ''; ?>
                                    onchange="updateFileName(this)">
                            </label>
                            <p class="text-gray-500 text-xs mt-2">Format yang diterima: PDF, JPG, PNG, ZIP</p>
                        </div>
                    </div>
                </div>

                <!-- Form Buttons -->
                <div class="px-6 py-4 flex items-center justify-end gap-4 border-t border-gray-100">
                    <button type="submit" id="btn-daftar"
                        class="bg-kampus-primary hover:bg-kampus-dark text-white font-bold text-base px-8 py-3 rounded-full transition-all duration-300 hover:shadow-lg active:scale-95 <?php echo !$ipk_eligible ? 'opacity-50 cursor-not-allowed' : ''; ?>"
                        <?php echo !$ipk_eligible ? 'disabled' : ''; ?>>
                        Daftar
                    </button>
                    <button type="reset" id="btn-batal"
                        class="border-2 border-kampus-primary text-kampus-primary hover:bg-kampus-primary hover:text-white font-medium text-base px-8 py-3 rounded-full transition-all duration-300 active:scale-95"
                        onclick="resetForm()">
                        Batal
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const IPK_VALUE  = <?php echo $ipk_system; ?>;
    const IPK_ELIGIBLE = <?php echo $ipk_eligible ? 'true' : 'false'; ?>;

    const namaEl     = document.getElementById('nama');
    const emailEl    = document.getElementById('email');
    const noHpEl     = document.getElementById('no_hp');
    const semesterEl = document.getElementById('semester');
    const ipkRow     = document.getElementById('ipk-row');
    const ipkWarning = document.getElementById('ipk-warning');
    const beasiswaRow = document.getElementById('beasiswa-row');
    const uploadRow  = document.getElementById('upload-row');
    const btnDaftar  = document.getElementById('btn-daftar');
    const beasiswaEl = document.getElementById('pilihan_beasiswa');
    const berkasEl   = document.getElementById('berkas');

    // Cek apakah semua 4 field awal sudah terisi
    function checkFields() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const namaOk     = namaEl.value.trim() !== '';
        const emailOk    = emailRegex.test(emailEl.value.trim());
        const hpOk       = noHpEl.value.trim() !== '' && /^[0-9]+$/.test(noHpEl.value.trim());
        const semOk      = semesterEl.value !== '';
        const allOk      = namaOk && emailOk && hpOk && semOk;

        if (allOk) {
            // Tampilkan baris IPK dengan animasi
            ipkRow.classList.remove('hidden');
            ipkRow.style.opacity = '0';
            ipkRow.style.transform = 'translateY(10px)';
            requestAnimationFrame(() => {
                ipkRow.style.transition = 'opacity 0.4s, transform 0.4s';
                ipkRow.style.opacity = '1';
                ipkRow.style.transform = 'translateY(0)';
            });

            // Tampilkan warning / aktifkan beasiswa sesuai IPK
            if (!IPK_ELIGIBLE) {
                ipkWarning.classList.remove('hidden');
                setBeasiswaDisabled(true);
            } else {
                ipkWarning.classList.add('hidden');
                setBeasiswaDisabled(false);
            }
        } else {
            ipkRow.classList.add('hidden');
            ipkWarning.classList.add('hidden');
        }
    }

    function setBeasiswaDisabled(disabled) {
        beasiswaRow.classList.toggle('field-disabled', disabled);
        uploadRow.classList.toggle('field-disabled', disabled);
        beasiswaEl.disabled = disabled;
        berkasEl.disabled   = disabled;
        btnDaftar.disabled  = disabled;
        btnDaftar.classList.toggle('opacity-50', disabled);
        btnDaftar.classList.toggle('cursor-not-allowed', disabled);
    }

    // Pasang event listener
    [namaEl, emailEl, noHpEl, semesterEl].forEach(el => {
        el.addEventListener('input', checkFields);
        el.addEventListener('change', checkFields);
    });

    // Validasi email real-time
    emailEl.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (this.value && !emailRegex.test(this.value)) {
            this.classList.add('border-red-400', 'bg-red-50');
            this.classList.remove('border-transparent');
        } else {
            this.classList.remove('border-red-400', 'bg-red-50');
        }
    });

    // Validasi nomor HP real-time
    noHpEl.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Update nama file upload
    function updateFileName(input) {
        const label = document.getElementById('file-label');
        label.textContent = input.files.length > 0 ? input.files[0].name : 'Choose File';
    }

    // Reset form
    function resetForm() {
        document.getElementById('file-label').textContent = 'Choose File';
        ipkRow.classList.add('hidden');
        ipkWarning.classList.add('hidden');
    }

    // Jalankan sekali saat load (untuk kasus old input setelah error)
    document.addEventListener('DOMContentLoaded', checkFields);
</script>
