<?php
// Ambil pesan sukses dari session
$success = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

// Baca data pendaftar dari file JSON
$pendaftar = baca_pendaftar();
?>

<div class="max-w-6xl mx-auto px-4 py-10">

    <!-- Judul Halaman -->
    <h1 class="text-4xl font-bold text-center text-gray-900 mb-10">
        Hasil Pendaftaran Beasiswa
    </h1>

    <!-- Pesan Sukses -->
    <?php if (!empty($success)): ?>
    <div class="mb-8 bg-green-50 border border-green-300 rounded-xl p-5" id="success-alert">
        <div class="flex items-center gap-3">
            <div class="bg-green-100 rounded-full p-2">
                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-green-800 text-base"><?php echo htmlspecialchars($success); ?></h4>
                <p class="text-green-600 text-sm mt-0.5">Data Anda telah tersimpan dalam sistem.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($pendaftar)): ?>
    <!-- Kosong -->
    <div class="bg-white border border-gray-200 rounded-xl p-16 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Pendaftaran</h3>
        <p class="text-gray-500 mb-6">Silakan daftarkan diri Anda melalui halaman pendaftaran.</p>
        <a href="index.php?page=daftar" class="inline-flex items-center gap-2 bg-kampus-primary hover:bg-kampus-dark text-white font-bold px-8 py-3 rounded-full">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Daftar Sekarang
        </a>
    </div>
    <?php else: ?>

    <!-- Tabel Hasil -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        <!-- Header Tabel -->
        <div class="bg-kampus-primary border-b-4 border-kampus-secondary px-6 py-4 flex items-center justify-between">
            <h2 class="text-white font-bold text-lg">Data Pendaftar Beasiswa</h2>
            <span class="bg-white/20 text-white text-sm font-semibold px-3 py-1 rounded-full" id="total-count">
                <?php echo count($pendaftar); ?> Data
            </span>
        </div>

        <!-- Toolbar: Search + Sort -->
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/>
                </svg>
                <input type="text" id="search-input"
                    placeholder="Cari nama, email, atau beasiswa..."
                    class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-900 outline-none focus:border-kampus-primary focus:bg-white">
            </div>
            <!-- Sort -->
            <div class="flex items-center gap-2 shrink-0">
                <label class="text-sm font-semibold text-gray-600 whitespace-nowrap">Urutkan:</label>
                <select id="sort-col" class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-900 outline-none focus:border-kampus-primary cursor-pointer">
                    <option value="">-- Kolom --</option>
                    <option value="0">No</option>
                    <option value="1">Nama</option>
                    <option value="2">Email</option>
                    <option value="4">Semester</option>
                    <option value="5">IPK</option>
                    <option value="6">Pilihan Beasiswa</option>
                    <option value="9">Tanggal Daftar</option>
                </select>
                <select id="sort-dir" class="bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 text-sm text-gray-900 outline-none focus:border-kampus-primary cursor-pointer">
                    <option value="asc">A → Z / Terkecil</option>
                    <option value="desc">Z → A / Terbesar</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="tabel-hasil">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-4 py-3 text-left font-bold text-gray-700 whitespace-nowrap cursor-pointer select-none hover:bg-gray-100" onclick="sortByCol(0)">
                            No <span class="sort-icon text-gray-400">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left font-bold text-gray-700 whitespace-nowrap cursor-pointer select-none hover:bg-gray-100" onclick="sortByCol(1)">
                            Nama <span class="sort-icon text-gray-400">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left font-bold text-gray-700 whitespace-nowrap cursor-pointer select-none hover:bg-gray-100" onclick="sortByCol(2)">
                            Email <span class="sort-icon text-gray-400">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left font-bold text-gray-700 whitespace-nowrap">No. HP</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-700 whitespace-nowrap cursor-pointer select-none hover:bg-gray-100" style="min-width:110px" onclick="sortByCol(4)">
                            Semester <span class="sort-icon text-gray-400">↕</span>
                        </th>
                        <th class="px-4 py-3 text-center font-bold text-gray-700 whitespace-nowrap cursor-pointer select-none hover:bg-gray-100" onclick="sortByCol(5)">
                            IPK <span class="sort-icon text-gray-400">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left font-bold text-gray-700 whitespace-nowrap cursor-pointer select-none hover:bg-gray-100" onclick="sortByCol(6)">
                            Pilihan Beasiswa <span class="sort-icon text-gray-400">↕</span>
                        </th>
                        <th class="px-4 py-3 text-left font-bold text-gray-700 whitespace-nowrap">Berkas</th>
                        <th class="px-4 py-3 text-center font-bold text-gray-700 whitespace-nowrap">Status Ajuan</th>
                        <th class="px-4 py-3 text-left font-bold text-gray-700 whitespace-nowrap cursor-pointer select-none hover:bg-gray-100" onclick="sortByCol(9)">
                            Tanggal Daftar <span class="sort-icon text-gray-400">↕</span>
                        </th>
                    </tr>
                </thead>
                <tbody id="tabel-body">
                    <?php foreach ($pendaftar as $index => $data): ?>
                    <tr class="border-b border-gray-100 hover:bg-kampus-light/30">
                        <td class="px-4 py-4 text-gray-600 font-semibold"><?php echo $index + 1; ?></td>
                        <td class="px-4 py-4 text-gray-900 font-semibold"><?php echo htmlspecialchars($data['nama']); ?></td>
                        <td class="px-4 py-4 text-gray-600"><?php echo htmlspecialchars($data['email']); ?></td>
                        <td class="px-4 py-4 text-gray-600"><?php echo htmlspecialchars($data['no_hp']); ?></td>
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            <span class="bg-blue-50 text-blue-700 font-semibold text-xs px-2.5 py-1 rounded-full whitespace-nowrap">
                                Semester <?php echo $data['semester']; ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="bg-green-50 text-green-700 font-bold text-sm px-2.5 py-1 rounded-lg">
                                <?php echo number_format($data['ipk'], 2); ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 text-gray-900 font-medium"><?php echo htmlspecialchars($data['pilihan_beasiswa']); ?></td>
                        <td class="px-4 py-4">
                            <?php if (!empty($data['berkas'])): ?>
                            <span class="inline-flex items-center gap-1 text-kampus-primary text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"/>
                                </svg>
                                <?php echo htmlspecialchars($data['berkas']); ?>
                            </span>
                            <?php else: ?>
                            <span class="text-gray-400 text-sm">Tidak ada</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 bg-yellow-50 border border-yellow-200 text-yellow-700 font-semibold text-xs px-3 py-1.5 rounded-full whitespace-nowrap">
                                <span class="w-2 h-2 bg-yellow-500 rounded-full"></span>
                                <?php echo htmlspecialchars($data['status_ajuan']); ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 text-gray-500 text-sm whitespace-nowrap"><?php echo $data['tanggal_daftar']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Info tidak ada hasil search -->
            <div id="no-results" class="hidden px-6 py-10 text-center text-gray-500 text-sm">
                Tidak ditemukan data yang sesuai dengan kata kunci pencarian.
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
/* =============================================
   SEARCH — filter baris tabel berdasarkan input
   ============================================= */
const searchInput   = document.getElementById('search-input');
const tabelBody     = document.getElementById('tabel-body');
const noResults     = document.getElementById('no-results');
const totalCount    = document.getElementById('total-count');

function filterTabel() {
    if (!tabelBody) return;
    const keyword = searchInput.value.toLowerCase().trim();
    const rows    = tabelBody.querySelectorAll('tr');
    let visible   = 0;

    rows.forEach(row => {
        // Kolom yang dicari: Nama(1), Email(2), No.HP(3), Pilihan Beasiswa(6)
        const searchCols = [1, 2, 3, 6];
        const text = searchCols.map(i => {
            const cell = row.cells[i];
            return cell ? cell.innerText.toLowerCase() : '';
        }).join(' ');

        const match = keyword === '' || text.includes(keyword);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    if (noResults) noResults.classList.toggle('hidden', visible > 0);
    if (totalCount) {
        const total = rows.length;
        totalCount.textContent = keyword ? `${visible} / ${total} Data` : `${total} Data`;
    }
}

if (searchInput) searchInput.addEventListener('input', filterTabel);

/* =============================================
   SORT — urutkan baris berdasarkan kolom
   ============================================= */
let currentSortCol = -1;
let currentSortDir = 'asc';

function sortByCol(colIndex) {
    if (!tabelBody) return;

    // Toggle arah jika klik kolom yang sama
    if (currentSortCol === colIndex) {
        currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
    } else {
        currentSortCol = colIndex;
        currentSortDir = 'asc';
    }

    // Sinkron dengan dropdown
    const sortColEl = document.getElementById('sort-col');
    const sortDirEl = document.getElementById('sort-dir');
    if (sortColEl) sortColEl.value = colIndex;
    if (sortDirEl) sortDirEl.value = currentSortDir;

    doSort(colIndex, currentSortDir);
    updateSortIcons(colIndex, currentSortDir);
    renumberRows();
}

function doSort(colIndex, dir) {
    if (!tabelBody) return;
    const rows = Array.from(tabelBody.querySelectorAll('tr'));

    rows.sort((a, b) => {
        const aText = a.cells[colIndex] ? a.cells[colIndex].innerText.trim() : '';
        const bText = b.cells[colIndex] ? b.cells[colIndex].innerText.trim() : '';

        // Coba parse numerik (kolom No, Semester, IPK)
        const aNum = parseFloat(aText.replace(',', '.'));
        const bNum = parseFloat(bText.replace(',', '.'));
        const isNum = !isNaN(aNum) && !isNaN(bNum);

        let cmp = isNum ? aNum - bNum : aText.localeCompare(bText, 'id');
        return dir === 'asc' ? cmp : -cmp;
    });

    rows.forEach(row => tabelBody.appendChild(row));
}

function updateSortIcons(activeCol, dir) {
    document.querySelectorAll('.sort-icon').forEach((el, i) => {
        // Map icon index to actual column index
        const colMap = [0, 1, 2, 4, 5, 6, 9];
        el.textContent = colMap[i] === activeCol
            ? (dir === 'asc' ? '↑' : '↓')
            : '↕';
        el.style.color = colMap[i] === activeCol ? '#2f6b3f' : '#9ca3af';
    });
}

function renumberRows() {
    if (!tabelBody) return;
    const visibleRows = Array.from(tabelBody.querySelectorAll('tr')).filter(r => r.style.display !== 'none');
    visibleRows.forEach((row, i) => {
        if (row.cells[0]) row.cells[0].textContent = i + 1;
    });
}

// Dropdown sort
const sortColEl = document.getElementById('sort-col');
const sortDirEl = document.getElementById('sort-dir');

function applyDropdownSort() {
    const col = parseInt(sortColEl.value);
    const dir = sortDirEl.value;
    if (isNaN(col)) return;
    currentSortCol = col;
    currentSortDir = dir;
    doSort(col, dir);
    updateSortIcons(col, dir);
    renumberRows();
}

if (sortColEl) sortColEl.addEventListener('change', applyDropdownSort);
if (sortDirEl) sortDirEl.addEventListener('change', applyDropdownSort);
</script>
