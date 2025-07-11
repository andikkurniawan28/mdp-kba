<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\Parameter;
use App\Models\KategoriParameter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function semua_akses()
    {
        $data = [
            'izin_akses_monitoring' => 'Akses Menu Monitoring',
            'izin_akses_input' => 'Akses Menu Input',
            'akses_cetak_barcode' => 'Cetak Barcode',
            'izin_akses_laporan' => 'Akses Menu Laporan',
            'izin_akses_master' => 'Akses Menu Master',
            'akses_master_daftar_kategori_parameter' => 'Daftar Role',
            'akses_master_tambah_kategori_parameter' => 'Tambah Role',
            'akses_master_edit_kategori_parameter' => 'Edit Role',
            'akses_master_hapus_kategori_parameter' => 'Hapus Role',
            'akses_master_daftar_satuan' => 'Daftar Satuan',
            'akses_master_tambah_satuan' => 'Tambah Satuan',
            'akses_master_edit_satuan' => 'Edit Satuan',
            'akses_master_hapus_satuan' => 'Hapus Satuan',
            'akses_master_daftar_jenis_pilihan_kualitatif' => 'Daftar Jenis Pilihan Kualitatif',
            'akses_master_tambah_jenis_pilihan_kualitatif' => 'Tambah Jenis Pilihan Kualitatif',
            'akses_master_edit_jenis_pilihan_kualitatif' => 'Edit Jenis Pilihan Kualitatif',
            'akses_master_hapus_jenis_pilihan_kualitatif' => 'Hapus Jenis Pilihan Kualitatif',
            'akses_master_daftar_parameter' => 'Daftar Parameter',
            'akses_master_tambah_parameter' => 'Tambah Parameter',
            'akses_master_edit_parameter' => 'Edit Parameter',
            'akses_master_hapus_parameter' => 'Hapus Parameter',
            'akses_master_daftar_zona' => 'Daftar Zona',
            'akses_master_tambah_zona' => 'Tambah Zona',
            'akses_master_edit_zona' => 'Edit Zona',
            'akses_master_hapus_zona' => 'Hapus Zona',
            'akses_master_daftar_titik_pengamatan' => 'Daftar Titik Pengamatan',
            'akses_master_tambah_titik_pengamatan' => 'Tambah Titik Pengamatan',
            'akses_master_edit_titik_pengamatan' => 'Edit Titik Pengamatan',
            'akses_master_hapus_titik_pengamatan' => 'Hapus Titik Pengamatan',
            'akses_master_daftar_role' => 'Daftar Role',
            'akses_master_tambah_role' => 'Tambah Role',
            'akses_master_edit_role' => 'Edit Role',
            'akses_master_hapus_role' => 'Hapus Role',
            'akses_master_daftar_user' => 'Daftar User',
            'akses_master_tambah_user' => 'Tambah User',
            'akses_master_edit_user' => 'Edit User',
            'akses_master_hapus_user' => 'Hapus User',
            'akses_daftar_input_monitoring' => 'Daftar Input Monitoring',
            'akses_tambah_input_monitoring' => 'Tambah Input Monitoring',
            'akses_edit_input_monitoring' => 'Edit Input Monitoring',
            'akses_hapus_input_monitoring' => 'Hapus Input Monitoring',
            'akses_laporan_shift' => 'Laporan Shift',
            'akses_verifikasi_mandor' => 'Verifikasi Mandor',
        ];

        $parameters = Parameter::select(['id', 'simbol', 'nama'])->get();
        $kategori_parameters = KategoriParameter::select(['id', 'nama'])->get();
        $zonas = Zona::select(['id', 'nama'])->get();

        // Dinamis: Parameter
        foreach ($parameters as $parameter) {
            $key = 'akses_input_param' . $parameter->id;
            $label = "Input ({$parameter->simbol} | {$parameter->nama})";
            $data[$key] = $label;
        }

        // Dinamis: Kategori Parameter
        foreach ($kategori_parameters as $kategori) {
            $key = 'akses_monitoring_kategori' . Str::slug($kategori->id, '_');
            $label = "Monitoring Kategori ({$kategori->nama})";
            $data[$key] = $label;
        }

        // Dinamis: Zona
        foreach ($zonas as $zona) {
            $key = 'akses_monitoring_zona' . Str::slug($zona->id, '_');
            $label = "Monitoring Zona ({$zona->nama})";
            $data[$key] = $label;
        }

        return $data;
    }

    public static function semua_akses2()
    {
        $izinList = [
            // Izin statis
            'izin_akses_monitoring',
            'izin_akses_input',
            'akses_cetak_barcode',
            'izin_akses_laporan',
            'izin_akses_master',
            'akses_master_daftar_kategori_parameter',
            'akses_master_tambah_kategori_parameter',
            'akses_master_edit_kategori_parameter',
            'akses_master_hapus_kategori_parameter',
            'akses_master_daftar_satuan',
            'akses_master_tambah_satuan',
            'akses_master_edit_satuan',
            'akses_master_hapus_satuan',
            'akses_master_daftar_jenis_pilihan_kualitatif',
            'akses_master_tambah_jenis_pilihan_kualitatif',
            'akses_master_edit_jenis_pilihan_kualitatif',
            'akses_master_hapus_jenis_pilihan_kualitatif',
            'akses_master_daftar_parameter',
            'akses_master_tambah_parameter',
            'akses_master_edit_parameter',
            'akses_master_hapus_parameter',
            'akses_master_daftar_zona',
            'akses_master_tambah_zona',
            'akses_master_edit_zona',
            'akses_master_hapus_zona',
            'akses_master_daftar_titik_pengamatan',
            'akses_master_tambah_titik_pengamatan',
            'akses_master_edit_titik_pengamatan',
            'akses_master_hapus_titik_pengamatan',
            'akses_master_daftar_role',
            'akses_master_tambah_role',
            'akses_master_edit_role',
            'akses_master_hapus_role',
            'akses_master_daftar_user',
            'akses_master_tambah_user',
            'akses_master_edit_user',
            'akses_master_hapus_user',
            'akses_daftar_input_monitoring',
            'akses_tambah_input_monitoring',
            'akses_edit_input_monitoring',
            'akses_hapus_input_monitoring',
            'akses_laporan_shift',
            'akses_verifikasi_mandor',
        ];

        // Izin dinamis: Parameter
        $parameters = Parameter::select(['id'])->get();
        foreach ($parameters as $parameter) {
            $izinList[] = 'akses_input_param' . $parameter->id;
        }

        // Izin dinamis: Kategori Parameter
        $kategori_parameters = KategoriParameter::select(['id'])->get();
        foreach ($kategori_parameters as $kategori) {
            $slug = Str::slug($kategori->id, '_');
            $izinList[] = 'akses_monitoring_kategori' . $slug;
        }

        // Izin dinamis: Zona
        $zonas = Zona::select(['id'])->get();
        foreach ($zonas as $zona) {
            $slug = Str::slug($zona->id, '_');
            $izinList[] = 'akses_monitoring_zona' . $slug;
        }

        return $izinList;
    }

}
