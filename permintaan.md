# API Requirements untuk Tim Backend

## Dokumen ini berisi daftar API endpoint yang dibutuhkan oleh aplikasi mobile React Native

---

## STATUS ENDPOINT DARI POSTMAN COLLECTION

Berdasarkan review file `postman_collection.json`, berikut status endpoint yang sudah ada:

### ✅ SUDAH ADA (Endpoint exists):
- `/api/v1/orangtua/dashboard` - GET
- `/api/v1/orangtua/profile` - GET & PUT
- `/api/v1/orangtua/siswa/profile` - GET
- `/api/v1/orangtua/daily-report` - GET (dengan query params: tanggal, start_date, end_date)
- `/api/v1/orangtua/daily-report/{id}` - GET
- `/api/v1/orangtua/kegiatan-harian` - GET
- `/api/v1/orangtua/pembayaran` - GET
- `/api/v1/orangtua/pembayaran/{id}/upload` - POST
- `/api/v1/orangtua/absensi` - GET
- `/api/v1/orangtua/berita` - GET
- `/api/v1/orangtua/berita/{slug}` - GET
- `/api/v1/guru/dashboard` - GET
- `/api/v1/guru/profile` - GET & PUT

### ⚠️ PERLU PERBAIKAN:
1. **`/api/v1/orangtua/dashboard`** - Response TIDAK memiliki field `berita_terbaru`
   - Saat ini: Response structure tidak terdokumentasi di Postman
   - Yang dibutuhkan: Tambahkan field `berita_terbaru: News[]` di response
   - Limit: 3-5 berita terbaru saja, sorted by `published_at` DESC
   - **INI YANG PALING PENTING** - Tanpa ini, berita tidak akan muncul di home screen

2. **`/api/v1/orangtua/profile` - PUT** - Hanya support JSON, belum support multipart/form-data
   - Saat ini: Content-Type: application/json
   - Yang dibutuhkan: Content-Type: multipart/form-data untuk upload foto profil

3. **`/api/v1/guru/profile` - PUT** - Hanya support JSON, belum support multipart/form-data
   - Saat ini: Content-Type: application/json
   - Yang dibutuhkan: Content-Type: multipart/form-data untuk upload foto profil

### 📝 CATATAN IMPLEMENTASI MOBILE:
- Home screen sudah diimplementasikan dengan API integration
- News section sudah ada di home screen dengan horizontal scroll
- Komponen NewsCard sudah dibuat dan siap digunakan
- Saat ini berita tidak muncul karena field `berita_terbaru` tidak ada di response `/orangtua/dashboard`
- Begitu backend menambahkan field `berita_terbaru`, berita akan langsung muncul tanpa perlu perubahan di mobile

---

## 1. Dashboard Orangtua - `/api/v1/orangtua/dashboard`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Response yang dibutuhkan**:
```json
{
  "success": true,
  "data": {
    "siswa": {
      "id": 1,
      "nama_lengkap": "Ahmad Zaki",
      "nama_panggilan": "Zaki",
      "foto_siswa": "https://example.com/foto.jpg",
      "is_active": true,
      "kelas": {
        "id": 1,
        "nama_kelas": "Kelas A - TK"
      },
      "guru": {
        "id": 1,
        "nama_lengkap": "Ibu Siti"
      }
    },
    "kegiatan_hari_ini": [
      {
        "id": 1,
        "nama_aktivitas": "Bermain puzzle dan menggambar",
        "deskripsi": "Kegiatan motorik halus",
        "target_perkembangan": "Meningkatkan motorik halus",
        "tanggal": "2026-04-28",
        "hari": "Selasa"
      }
    ],
    "notifikasi": {
      "daily_report_count": 2,
      "pembayaran_pending": 1
    },
    "berita_terbaru": [
      {
        "id": 1,
        "title": "Libur Hari Raya Idul Fitri 2026",
        "slug": "libur-hari-raya-idul-fitri-2026",
        "excerpt": "Sekolah akan libur mulai tanggal 20-30 Maret 2026",
        "content": "Full content here...",
        "image_url": "https://example.com/berita.jpg",
        "is_published": true,
        "published_at": "2026-04-20T10:00:00Z",
        "created_at": "2026-04-20T10:00:00Z"
      }
    ],
    "event_aktif": []
  },
  "message": "Dashboard data retrieved successfully"
}
```

**PENTING**: 
- Field `berita_terbaru` WAJIB ada di response (array, bisa kosong)
- Limit berita terbaru: 3-5 berita saja
- Sort by `published_at` DESC

---

## 2. Dashboard Guru - `/api/v1/guru/dashboard`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Response yang dibutuhkan**:
```json
{
  "success": true,
  "data": {
    "stats": {
      "total_siswa": 24,
      "siswa_hadir": 20,
      "daily_report_completed": 18,
      "daily_report_total": 24,
      "materi_completed": 5,
      "materi_total": 10
    },
    "recent_reports": [
      {
        "id": 1,
        "siswa_id": 1,
        "tanggal": "2026-04-28",
        "mood": "Senang",
        "activity": "Bermain puzzle",
        "is_final": true,
        "siswa": {
          "id": 1,
          "nama_lengkap": "Ahmad Zaki",
          "nama_panggilan": "Zaki"
        },
        "created_at": "2026-04-28T08:00:00Z"
      }
    ]
  },
  "message": "Dashboard data retrieved successfully"
}
```

---

## 3. Profile Orangtua - `/api/v1/orangtua/profile`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Response yang dibutuhkan**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Budi Santoso",
    "email": "budi@example.com",
    "nohp": "081234567890",
    "role": "orangtua",
    "foto_profil": "https://example.com/profile.jpg",
    "alamat": "Jl. Contoh No. 123",
    "pekerjaan": "Wiraswasta",
    "hubungan_dengan_siswa": "Ayah",
    "created_at": "2026-01-01T00:00:00Z"
  },
  "message": "Profile retrieved successfully"
}
```

**Method**: PUT  
**Auth**: Required (Bearer Token)  
**Content-Type**: multipart/form-data

**Request Body**:
```
name: "Budi Santoso"
email: "budi@example.com"
nohp: "081234567890"
alamat: "Jl. Contoh No. 123"
pekerjaan: "Wiraswasta"
foto_profil: File (optional, image/jpeg, image/png, max 2MB)
```

---

## 4. Profile Siswa - `/api/v1/orangtua/siswa/profile`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Response yang dibutuhkan**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "nama_lengkap": "Ahmad Zaki",
    "nama_panggilan": "Zaki",
    "tanggal_lahir": "2022-05-15",
    "jenis_kelamin": "Laki-laki",
    "foto_siswa": "https://example.com/siswa.jpg",
    "alamat": "Jl. Contoh No. 123",
    "is_active": true,
    "kelas": {
      "id": 1,
      "nama_kelas": "Kelas A - TK"
    },
    "guru": {
      "id": 1,
      "nama_lengkap": "Ibu Siti"
    },
    "orangtua": [
      {
        "id": 1,
        "name": "Budi Santoso",
        "hubungan": "Ayah",
        "nohp": "081234567890"
      },
      {
        "id": 2,
        "name": "Siti Aminah",
        "hubungan": "Ibu",
        "nohp": "081234567891"
      }
    ],
    "riwayat_kesehatan": {
      "alergi": "Tidak ada",
      "penyakit_bawaan": "Tidak ada",
      "golongan_darah": "O"
    },
    "created_at": "2026-01-01T00:00:00Z"
  },
  "message": "Student profile retrieved successfully"
}
```

**Note**: 
- Orangtua hanya bisa **LIHAT** (GET) data siswa, **TIDAK BISA EDIT**
- Edit profile siswa hanya bisa dilakukan oleh Admin/Sekolah melalui web dashboard

---

## 5. Profile Guru - `/api/v1/guru/profile`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Response yang dibutuhkan**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Ibu Siti Nurhaliza",
    "email": "siti@albiruni.sch.id",
    "nohp": "081234567890",
    "role": "guru",
    "foto_profil": "https://example.com/guru.jpg",
    "alamat": "Jl. Guru No. 456",
    "nip": "123456789",
    "pendidikan_terakhir": "S1 Pendidikan Anak Usia Dini",
    "kelas": {
      "id": 1,
      "nama_kelas": "Kelas A - TK"
    },
    "created_at": "2026-01-01T00:00:00Z"
  },
  "message": "Profile retrieved successfully"
}
```

**Method**: PUT  
**Auth**: Required (Bearer Token)  
**Content-Type**: multipart/form-data

**Request Body**:
```
name: "Ibu Siti Nurhaliza"
email: "siti@albiruni.sch.id"
nohp: "081234567890"
alamat: "Jl. Guru No. 456"
foto_profil: File (optional, image/jpeg, image/png, max 2MB)
```

---

## 6. Daily Report dengan Filter Tanggal

### Orangtua - `/api/v1/orangtua/daily-report`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Query Parameters**:
- `tanggal` (optional): Format YYYY-MM-DD (contoh: 2026-04-28)
- `bulan` (optional): 1-12
- `tahun` (optional): 2026
- `start_date` (optional): Format YYYY-MM-DD
- `end_date` (optional): Format YYYY-MM-DD

**Contoh Request**:
```
GET /api/v1/orangtua/daily-report?tanggal=2026-04-28
GET /api/v1/orangtua/daily-report?bulan=4&tahun=2026
GET /api/v1/orangtua/daily-report?start_date=2026-04-01&end_date=2026-04-30
```

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "siswa_id": 1,
      "tanggal": "2026-04-28",
      "mood": "Senang",
      "activity": "Bermain puzzle dan menggambar",
      "sarapan_pagi": "Nasi goreng",
      "sarapan_status": 3,
      "makan_siang": "Nasi + ayam",
      "makan_siang_status": 3,
      "snack_sore": "Biskuit",
      "snack_status": 2,
      "minum_air_putih": "500ml",
      "minum_susu": "200ml",
      "tidur_siang": true,
      "tidur_siang_durasi": "1 jam",
      "bak": true,
      "bak_frekuensi": 3,
      "bab": true,
      "bab_frekuensi": 1,
      "kebutuhan_besok": "Bawa buku gambar",
      "catatan_khusus": "Anak sangat aktif hari ini",
      "catatan_insiden": null,
      "foto_kegiatan": [
        "https://example.com/foto1.jpg",
        "https://example.com/foto2.jpg"
      ],
      "is_final": true,
      "emosi": [
        {
          "id": 1,
          "nama_emosi": "Senang"
        }
      ],
      "created_at": "2026-04-28T08:00:00Z",
      "updated_at": "2026-04-28T14:00:00Z"
    }
  ],
  "message": "Daily reports retrieved successfully"
}
```

### Guru - `/api/v1/guru/daily-report`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Query Parameters**:
- `tanggal` (optional): Format YYYY-MM-DD
- `siswa_id` (optional): Filter by siswa ID
- `start_date` (optional): Format YYYY-MM-DD
- `end_date` (optional): Format YYYY-MM-DD

---

## 7. Absensi Siswa (untuk Orangtua)

### `/api/v1/orangtua/absensi`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Query Parameters**:
- `bulan` (optional): 1-12
- `tahun` (optional): 2026
- `start_date` (optional): Format YYYY-MM-DD
- `end_date` (optional): Format YYYY-MM-DD

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "siswa_id": 1,
      "tanggal": "2026-04-28",
      "waktu_hadir": "07:30:00",
      "waktu_pulang": "15:00:00",
      "jenis_interaksi": "Diantar orangtua",
      "rating": 5,
      "created_at": "2026-04-28T07:30:00Z"
    }
  ],
  "message": "Attendance data retrieved successfully"
}
```

---

## 8. Berita Detail - `/api/v1/orangtua/berita/{slug}`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Libur Hari Raya Idul Fitri 2026",
    "slug": "libur-hari-raya-idul-fitri-2026",
    "excerpt": "Sekolah akan libur mulai tanggal 20-30 Maret 2026",
    "content": "<p>Full HTML content here...</p>",
    "image_url": "https://example.com/berita.jpg",
    "is_published": true,
    "published_at": "2026-04-20T10:00:00Z",
    "created_at": "2026-04-20T10:00:00Z",
    "updated_at": "2026-04-20T10:00:00Z"
  },
  "message": "News detail retrieved successfully"
}
```

---

## 9. Pembayaran SPP dengan Upload Bukti

### `/api/v1/orangtua/pembayaran/{id}/upload`

**Method**: POST  
**Auth**: Required (Bearer Token)  
**Content-Type**: multipart/form-data

**Request Body**:
```
bukti_bayar: File (image/jpeg, image/png, max 5MB)
tanggal_bayar: "2026-04-28"
metode_bayar: "Transfer Bank"
```

**Response**:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "siswa_id": 1,
    "bulan": 4,
    "tahun": 2026,
    "biaya": 500000,
    "tanggal_bayar": "2026-04-28",
    "metode_bayar": "Transfer Bank",
    "bukti_bayar": "https://example.com/bukti/bukti123.jpg",
    "status_bayar": "menunggu_verifikasi",
    "created_at": "2026-04-28T10:00:00Z"
  },
  "message": "Payment proof uploaded successfully"
}
```

---

## 10. Kegiatan Harian - `/api/v1/orangtua/kegiatan-harian`

**Method**: GET  
**Auth**: Required (Bearer Token)

**Query Parameters**:
- `tanggal` (optional): Format YYYY-MM-DD
- `bulan` (optional): 1-12
- `tahun` (optional): 2026

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "rencana_pembelajaran_id": 1,
      "nama_aktivitas": "Bermain puzzle",
      "deskripsi": "Kegiatan motorik halus dengan puzzle 20 pieces",
      "target_perkembangan": "Meningkatkan motorik halus dan konsentrasi",
      "alat_bahan": "Puzzle 20 pieces, meja, kursi",
      "instruksi": "Bimbing anak untuk menyusun puzzle",
      "foto_kegiatan": "https://example.com/kegiatan.jpg",
      "tanggal": "2026-04-28",
      "hari": "Selasa"
    }
  ],
  "message": "Daily activities retrieved successfully"
}
```

---

## Catatan Penting untuk Tim Backend:

### 1. Format Tanggal
- Semua tanggal menggunakan format ISO 8601: `YYYY-MM-DDTHH:mm:ssZ`
- Timezone: Asia/Jakarta (WIB)
- Query parameter tanggal: `YYYY-MM-DD`

### 2. Image Upload
- Max size: 5MB
- Format: JPEG, PNG, WebP
- Return full URL (bukan relative path)
- Gunakan CDN jika memungkinkan

### 3. Pagination (jika data banyak)
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100,
    "last_page": 5
  }
}
```

### 4. Error Response Format
```json
{
  "success": false,
  "message": "Error message here",
  "errors": [
    "Specific error 1",
    "Specific error 2"
  ]
}
```

### 5. Status HTTP Codes
- 200: Success
- 201: Created
- 400: Bad Request
- 401: Unauthorized
- 403: Forbidden
- 404: Not Found
- 422: Validation Error
- 500: Server Error

---

## Priority List (Urutan Implementasi)

### HIGH PRIORITY (Butuh Segera):
1. ✅ Dashboard Orangtua - field `berita_terbaru` WAJIB ada
2. ✅ Dashboard Guru - stats lengkap
3. ⏳ Profile Orangtua (GET & PUT) - orangtua bisa edit profile sendiri
4. ⏳ Profile Siswa (GET only) - orangtua hanya bisa lihat, tidak bisa edit
5. ⏳ Profile Guru (GET & PUT) - guru bisa edit profile sendiri
6. ⏳ Daily Report dengan filter tanggal (start_date, end_date)

### MEDIUM PRIORITY:
7. ⏳ Absensi dengan filter tanggal
8. ⏳ Upload bukti bayar SPP
9. ⏳ Kegiatan harian dengan filter

### LOW PRIORITY:
10. Pagination untuk list yang panjang
11. Search & filter advanced

---

**Kontak Mobile Developer**: [Your Contact]  
**Last Updated**: 2026-04-28
