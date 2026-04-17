# Vehicle Booking System - Mining Fleet Monitoring

Aplikasi manajemen pemesanan kendaraan untuk perusahaan tambang nikel. Sistem ini dirancang untuk memonitoring penggunaan kendaraan, konsumsi BBM, dan jadwal servis dengan sistem persetujuan berjenjang (multi-level approval).

## 🚀 Fitur Utama
- **Dashboard Analytics**: Grafik frekuensi pemakaian kendaraan menggunakan Chart.js.
- **Multi-level Approval**: Persetujuan berjenjang minimal 2 level (Atasan & Pool Manager).
- **Role-Based Access**: 
  - **Admin**: Input pemesanan, menentukan driver, dan memilih pihak penyetujui.
  - **Approver**: Semua approver dapat melihat daftar pemesanan, namun **hanya** approver yang ditugaskan pada booking tersebut yang dapat menyetujui atau menolak.
- **Monitoring & Logs**: Riwayat lengkap aktivitas (Activity Logs) untuk setiap pemesanan.
- **Export Laporan**: Laporan periodik dalam format Excel/CSV.

## 🛠️ Tech Stack & Spesifikasi
- **Framework**: Laravel 13.5.0
- **Frontend**: Inertia.js (Vue 3)
- **Language**: PHP 8.5.5
- **Database**: SQLite (Version 3.x)
- **Styling**: Tailwind CSS

## 🔑 Akun Uji Coba (Credentials)
Gunakan akun berikut untuk menguji fitur persetujuan berjenjang:

| Role | Nama / Username | Password | Deskripsi |
| :--- | :--- | :--- | :--- |
| **Admin** | `surya_admin` | `password` | Membuat input pemesanan & monitoring log. |
| **Approver 1** | `surya_approver` | `password` | Approver Level 1 (Atasan). |
| **Approver 2** | `surya1_approver` | `password` | Approver Level 2 (Pool Manager). |
| **Approver 3** | `surya2_approver` | `password` | Approver lain (Hanya bisa melihat list). |

## ⚙️ Panduan Instalasi (Arch Linux)

1. **Clone the Repository**
   ```bash
   git clone [https://github.com/username/mining-vehicle-booking.git](https://github.com/username/mining-vehicle-booking.git)
   cd mining-vehicle-booking

2. **Instal Dependensi**
    composer install
    npm install && npm run build

3. **Configure environment**
    cp .env.example .env
    # Pastikan DB_CONNECTION diatur ke sqlite

4. **Setup Database**
    touch database/database.sqlite
    php artisan migrate:fresh --seed

5. **Run the application**
    php artisan serve