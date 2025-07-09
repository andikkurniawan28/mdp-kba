# 📊 Monitoring Data Platform (MDP) - PG Kebon Agung

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-^8.1-blue.svg)](https://www.php.net/)
[![Status](https://img.shields.io/badge/status-production-brightgreen.svg)]()
[![Maintained](https://img.shields.io/badge/maintained-yes-green.svg)]()
[![Issues](https://img.shields.io/github/issues/username/mdp-pgkebonagung)](https://github.com/username/mdp-pgkebonagung/issues)

> Aplikasi monitoring operasional dan pencatatan data terintegrasi untuk PG Kebon Agung.

---

## 🎯 Tujuan

Monitoring Data Platform (MDP) adalah aplikasi berbasis web untuk membantu pencatatan, pemantauan, dan pengelolaan parameter penting proses industri secara digital dan efisien.

---

## 🏗️ Fitur Utama

- 🔍 **Monitoring Parameter**
  - Per kategori, per zona, atau semua titik
  - Tabel + visualisasi + agregasi data
- 📝 **Input Data**
  - Dukungan parameter kuantitatif & kualitatif
  - Input bertahap per titik/jam
  - Validasi dan log perubahan
- ⚙️ **Manajemen Data**
  - Parameter, zona, titik, satuan, pilihan nilai
  - Role-based access (RBAC)
- 📈 **Perhitungan Otomatis**
  - HK dan Rendemen dari Brix & Pol
  - Perhitungan flow berdasarkan totalizer
- 📤 **Ekspor Excel**
  - Monitoring dan Rekap Agregasi
- 🔒 **Audit Trail**
  - Catatan siapa input, kapan, dan apa yang diubah

---

## 🧰 Teknologi

| Komponen     | Teknologi                |
|--------------|---------------------------|
| Backend      | Laravel 10.x              |
| Frontend     | Blade + Bootstrap 5       |
| Database     | MySQL / MariaDB           |
| UI Library   | jQuery, Select2, Chart.js |
| Export Tool  | SheetJS (XLSX)            |

---

## ⚙️ Instalasi Developer

```bash
git clone https://github.com/username/mdp-pgkebonagung.git
cd mdp-pgkebonagung
composer install
cp .env.example .env
php artisan key:generate
# Atur koneksi database di .env
php artisan migrate --seed
php artisan serve
