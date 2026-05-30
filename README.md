# Sistem Pendaftaran Beasiswa Online — KampusKuAja

Aplikasi web berbasis **PHP Native** untuk mengelola pendaftaran beasiswa mahasiswa secara online. Data pendaftar disimpan ke file JSON secara persisten.

---

## Informasi Proyek

| Item | Keterangan |
|------|------------|
| **Nama Proyek** | Sistem Pendaftaran Beasiswa KampusKuAja |
| **Author** | Muhammad Fajrul Falah |
| **NIM** | 2207412019 |
| **Instansi** | Politeknik Negeri Jakarta (PNJ) |
| **Versi** | 1.0.0 |
| **Tanggal** | 30 Mei 2026 |

---

## Deskripsi Singkat

Sistem ini memungkinkan mahasiswa mendaftarkan diri untuk beasiswa secara online. IPK mahasiswa ditarik otomatis dari sistem (simulasi menggunakan nilai acak per sesi). Pendaftaran hanya dapat dilanjutkan apabila IPK ≥ 3,0. Data yang masuk disimpan ke file `data/pendaftar.json` dan dapat ditampilkan, dicari, serta diurutkan di halaman hasil.

---

## Struktur Direktori

```
KampusKuAja_Muhammad_Fajrul_Falah_PNJ_2207412019/
├── index.php           # Entry point: routing, layout, proses form
├── pages/
│   ├── daftar.php      # Halaman form pendaftaran
│   └── hasil.php       # Halaman hasil pendaftaran (tabel + search + sort)
├── data/
│   └── pendaftar.json  # File penyimpanan data (dibuat otomatis)
├── uploads/            # File berkas yang diupload (dibuat otomatis)
└── README.md
```

---

## Library / Teknologi yang Digunakan

| Library / Teknologi | Keterangan | Cara Pakai |
|---------------------|-----------|------------|
| **PHP Native** | Bahasa pemrograman server-side | Tanpa framework |
| **Tailwind CSS** (CDN) | Framework CSS utility-first | `<script src="https://cdn.tailwindcss.com">` |
| **Plus Jakarta Sans** | Font dari Google Fonts | `@import` via Google Fonts CDN |
| **PHP Session** | Menyimpan IPK sesi, pesan sukses/error | `session_start()` |
| **PHP JSON** | Penyimpanan data pendaftar | `json_encode` / `json_decode` |

---

## Cara Menjalankan

### Prasyarat
- PHP >= 7.4 terinstall
- Akses internet (untuk Tailwind CDN & Google Fonts)

### Langkah Eksekusi

1. **Clone / ekstrak** folder proyek ke direktori lokal

2. **Buka terminal**, masuk ke folder proyek:
   ```bash
   cd KampusKuAja_Muhammad_Fajrul_Falah_PNJ_2207412019
   ```

3. **Jalankan PHP built-in server**:
   ```bash
   php -S localhost:8000
   ```

4. **Buka browser**, akses:
   ```
   http://localhost:8000
   ```

5. Folder `data/` dan `uploads/` akan **dibuat otomatis** saat pertama kali ada pendaftaran masuk.

---
