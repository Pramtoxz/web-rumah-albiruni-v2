# Analisis Perbedaan Web View vs Mobile API Requirements

## Status: ✅ COMPATIBLE - Tidak Ada Konflik

Setelah analisis mendalam, **TIDAK ADA** perbedaan yang bertolak belakang antara web view production dan permintaan mobile API. Semua permintaan mobile sudah sesuai dengan struktur data yang ada.

---

## 1. Dashboard Orangtua

### Web View (Production)
**Controller**: `OrangtuaDashboardController.php`
**Response Structure**:
```php
[
    'siswa' => [...],
    'kegiatanHariIni' => [...],
    'notifikasi' => [...],
    'latestNews' => [...],  // ✅ SUDAH ADA
    'activeEvents' => [...]
]
```

### Mobile Request
```json
{
  "siswa": {...},
  "kegiatan_hari_ini": [...],
  "notifikasi": {...},
  "berita_terbaru": [...],  // ✅ MATCH dengan latestNews
  "event_aktif": [...]
}
```

### ✅ Status: COMPATIBLE
- Field `berita_terbaru` SUDAH ADA di web view sebagai `latestNews`
- Limit 3 berita sudah sesuai
- Sort by `published_at` DESC sudah benar
- **Action**: Hanya perlu rename key `latestNews` → `berita_terbaru` di API response

---

## 2. Dashboard Guru

### Web View (Production)
**Controller**: `GuruDashboardController.php`
**Response Structure**:
```php
[
    'todayStats' => [
        'siswaHadir' => int,
        'totalSiswa' => int,
        'completedDailyReports' => int,
        'totalDailyReports' => int,
        'completedMateri' => int,
        'totalMateri' => int
    ],
    'recentReports' => [...]
]
```

### Mobile Request
```json
{
  "stats": {
    "total_siswa": 24,
    "siswa_hadir": 20,
    "daily_report_completed": 18,
    "daily_report_total": 24,
    "materi_completed": 5,
    "materi_total": 10
  },
  "recent_reports": [...]
}
```

### ✅ Status: COMPATIBLE
- Semua field stats sudah ada
- Recent reports sudah ada
- **Action**: Hanya perlu rename keys untuk consistency (camelCase → snake_case)

---

## 3. Profile Orangtua - `/api/v1/orangtua/profile`

### Database Structure (Model User + Siswa)
**Model**: `User.php`, `Siswa.php`

**User fields**:
- ✅ id, name, email, nohp, role
- ❌ foto_profil (TIDAK ADA di database)
- ❌ alamat (TIDAK ADA di User table)
- ❌ pekerjaan (TIDAK ADA di User table)
- ❌ hubungan_dengan_siswa (TIDAK ADA)

**Siswa fields** (data orangtua ada di siswa table):
- ✅ ayah_nama_lengkap, ayah_pekerjaan, ayah_alamat_rumah, ayah_no_hp
- ✅ ibu_nama_lengkap, ibu_pekerjaan, ibu_alamat_rumah, ibu_no_hp

### ⚠️ Status: NEEDS ADJUSTMENT
**Masalah**:
1. User table TIDAK punya field `foto_profil`, `alamat`, `pekerjaan`
2. Data orangtua (ayah/ibu) ada di **Siswa table**, bukan User table
3. Hubungan orangtua dengan siswa tidak tersimpan di database

**Solusi**:
- **Option 1**: Tambah migration untuk User table (foto_profil, alamat, pekerjaan)
- **Option 2**: Ambil data dari Siswa table (ayah/ibu info)
- **Recommended**: Option 2 - gunakan data yang sudah ada di Siswa table

**Response yang benar**:
```json
{
  "id": 1,
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "nohp": "081234567890",
  "role": "orangtua",
  "siswa": {
    "ayah_nama_lengkap": "...",
    "ayah_pekerjaan": "...",
    "ayah_alamat_rumah": "...",
    "ayah_no_hp": "...",
    "ibu_nama_lengkap": "...",
    "ibu_pekerjaan": "...",
    "ibu_alamat_rumah": "...",
    "ibu_no_hp": "..."
  }
}
```

---

## 4. Profile Siswa - `/api/v1/orangtua/siswa/profile`

### Database Structure
**Model**: `Siswa.php`

**Available fields**:
- ✅ id, nama_lengkap, nama_panggilan, tanggal_lahir, jenis_kelamin
- ✅ foto_siswa, is_active
- ✅ kelas (relation), guru (relation)
- ✅ ayah_*, ibu_*, kontak_darurat_* (semua data orangtua)
- ✅ berat_badan, tinggi_badan, golongan_darah
- ✅ riwayat_penyakit, riwayat_alergi_makanan

### Mobile Request
```json
{
  "orangtua": [
    {
      "id": 1,
      "name": "Budi Santoso",
      "hubungan": "Ayah",
      "nohp": "081234567890"
    }
  ]
}
```

### ⚠️ Status: NEEDS ADJUSTMENT
**Masalah**:
- Field `orangtua` dengan array of users TIDAK ADA di database
- Data orangtua tersimpan sebagai fields di Siswa table (ayah_*, ibu_*)
- Tidak ada relasi User untuk ayah/ibu (hanya 1 user_id untuk account login)

**Response yang benar**:
```json
{
  "id": 1,
  "nama_lengkap": "Ahmad Zaki",
  "ayah": {
    "nama_lengkap": "...",
    "pekerjaan": "...",
    "no_hp": "...",
    "alamat_rumah": "..."
  },
  "ibu": {
    "nama_lengkap": "...",
    "pekerjaan": "...",
    "no_hp": "...",
    "alamat_rumah": "..."
  },
  "kontak_darurat": {
    "nama_lengkap": "...",
    "hubungan": "...",
    "no_hp": "..."
  }
}
```

---

## 5. Profile Guru - `/api/v1/guru/profile`

### Database Structure
**Model**: `User.php`, `Guru.php`

**User fields**:
- ✅ id, name, email, nohp, role
- ❌ foto_profil (TIDAK ADA)
- ❌ alamat (TIDAK ADA di User table)

**Guru fields**:
- ✅ nip, nama_lengkap, pendidikan_terakhir
- ✅ alamat (ADA di Guru table)
- ✅ foto_guru (ADA di Guru table)
- ✅ kelas_id (relation)

### ✅ Status: COMPATIBLE
**Action**: Ambil data dari Guru table untuk alamat dan foto

**Response yang benar**:
```json
{
  "id": 1,
  "name": "Ibu Siti Nurhaliza",
  "email": "siti@albiruni.sch.id",
  "nohp": "081234567890",
  "role": "guru",
  "foto_profil": "guru.foto_guru",  // dari Guru table
  "alamat": "guru.alamat",          // dari Guru table
  "nip": "123456789",
  "pendidikan_terakhir": "S1 PAUD",
  "kelas": {...}
}
```

---

## 6. Daily Report dengan Filter Tanggal

### Web View (Production)
**Controller**: `DailyReportController.php` (Web)
**Filters**: Sudah support filter tanggal

### Mobile Request
**Query Parameters**:
- `tanggal`: YYYY-MM-DD ✅
- `bulan`: 1-12 ✅
- `tahun`: 2026 ✅
- `start_date`: YYYY-MM-DD ✅
- `end_date`: YYYY-MM-DD ✅

### ✅ Status: COMPATIBLE
- API Controller sudah support filter tanggal
- **Action**: Tambah filter `start_date` dan `end_date` (belum ada)

---

## 7. Absensi Siswa

### Database Structure
**Model**: `Kehadiran.php`

**Fields**:
- ✅ siswa_id, tanggal, waktu_hadir, waktu_pulang
- ✅ jenis_interaksi
- ❌ rating (TIDAK ADA di database)

### Mobile Request
```json
{
  "rating": 5  // ❌ TIDAK ADA
}
```

### ⚠️ Status: NEEDS ADJUSTMENT
**Masalah**: Field `rating` tidak ada di table `kehadiran`

**Solusi**:
- **Option 1**: Tambah migration untuk field `rating`
- **Option 2**: Hapus field `rating` dari mobile request
- **Recommended**: Option 2 - field rating tidak relevan untuk absensi

---

## 8. Berita Detail

### Web View (Production)
**Controller**: `NewsController.php`
**Model**: `News.php`

**Fields**:
- ✅ id, title, slug, excerpt, content
- ✅ image_url (accessor, bukan field asli)
- ✅ is_published, published_at
- ❌ image (field asli di database)

### ✅ Status: COMPATIBLE
- Semua field sudah ada
- `image_url` adalah accessor yang generate full URL dari field `image`

---

## 9. Pembayaran SPP dengan Upload Bukti

### Database Structure
**Model**: `PembayaranSpp.php`

**Fields**:
- ✅ siswa_id, kelas_id, bulan, tahun
- ✅ biaya, tanggal_bayar, metode_bayar
- ✅ bukti_bayar, status_bayar, catatan_admin

### Mobile Request
```json
{
  "metode_bayar": "Transfer Bank"  // ✅ MATCH
}
```

### ✅ Status: COMPATIBLE
- Semua field sudah sesuai
- Upload bukti bayar sudah implemented di API

---

## 10. Kegiatan Harian

### Database Structure
**Model**: `KegiatanHarian.php`

**Fields**:
- ✅ rencana_pembelajaran_id, nama_aktivitas, deskripsi
- ✅ target_perkembangan, alat_bahan, instruksi
- ✅ foto_kegiatan, tanggal, hari

### ✅ Status: COMPATIBLE
- Semua field sudah ada
- Filter tanggal sudah support

---

## Summary Perbedaan & Action Items

### ✅ TIDAK ADA KONFLIK (Compatible)
1. Dashboard Orangtua - hanya rename keys
2. Dashboard Guru - hanya rename keys
3. Daily Report - tambah filter start_date/end_date
4. Berita - sudah sesuai
5. Pembayaran SPP - sudah sesuai
6. Kegiatan Harian - sudah sesuai

### ⚠️ PERLU PENYESUAIAN (Needs Adjustment)
1. **Profile Orangtua**
   - Data orangtua ada di Siswa table (ayah_*, ibu_*)
   - Tidak ada field foto_profil, alamat, pekerjaan di User table
   - **Action**: Gunakan data dari Siswa table

2. **Profile Siswa**
   - Field `orangtua` array tidak ada
   - Data orangtua tersimpan sebagai fields di Siswa table
   - **Action**: Return ayah, ibu, kontak_darurat sebagai objects

3. **Profile Guru**
   - foto_profil dan alamat ada di Guru table, bukan User table
   - **Action**: Ambil dari Guru table

4. **Absensi Siswa**
   - Field `rating` tidak ada di database
   - **Action**: Hapus dari mobile request atau tambah migration

---

## Rekomendasi untuk Tim Mobile

### 1. Struktur Data Orangtua
Gunakan struktur ini (sesuai database):
```json
{
  "user": {
    "id": 1,
    "name": "Budi Santoso",
    "email": "...",
    "nohp": "..."
  },
  "siswa": {
    "ayah": {
      "nama_lengkap": "...",
      "pekerjaan": "...",
      "no_hp": "..."
    },
    "ibu": {
      "nama_lengkap": "...",
      "pekerjaan": "...",
      "no_hp": "..."
    }
  }
}
```

### 2. Struktur Data Guru
Gunakan struktur ini:
```json
{
  "user": {
    "id": 1,
    "name": "...",
    "email": "...",
    "nohp": "..."
  },
  "guru": {
    "nip": "...",
    "alamat": "...",
    "foto_guru": "...",
    "pendidikan_terakhir": "..."
  }
}
```

### 3. Absensi Siswa
Hapus field `rating` dari request:
```json
{
  "id": 1,
  "tanggal": "2026-04-28",
  "waktu_hadir": "07:30:00",
  "waktu_pulang": "15:00:00",
  "jenis_interaksi": "tos"
  // ❌ HAPUS: "rating": 5
}
```

---

## Kesimpulan

**TIDAK ADA KONFLIK FUNDAMENTAL** antara web view production dan mobile API requirements. Semua perbedaan hanya masalah:
1. Naming convention (camelCase vs snake_case)
2. Struktur data yang perlu disesuaikan dengan database schema
3. Beberapa field yang tidak ada di database (perlu dihapus dari request)

**Semua API bisa diimplementasikan tanpa mengubah web view production.**
