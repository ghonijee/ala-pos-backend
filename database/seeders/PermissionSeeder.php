<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->command->info('Initial Permssion Data: Start');
        $initialData = [
            [
                "name" => "Create transaction",
                "key" => "create-transaction",
                "description" => "Membuat transaksi di kasir"
            ],
            [
                "name" => "Show transaction",
                "key" => "show-transaction",
                "description" => "Melihat riwayat transaksi"
            ],
            [
                "name" => "Cancel transaction",
                "key" => "cancel-transaction",
                "description" => "Membatalkan transaksi yang sudah dibuat"
            ],
            [
                "name" => "Delete transaction",
                "key" => "delete-transaction",
                "description" => "Menghapus transaksi yang dibatalkan"
            ],
            [
                "name" => "Create product",
                "key" => "create-product",
                "description" => "Menambahkan produk pada toko"
            ],
            [
                "name" => "Show product",
                "key" => "show-product",
                "description" => "Melihat master produk pada toko"
            ],
            [
                "name" => "Update product",
                "key" => "update-product",
                "description" => "Mengubah detail produk pada toko"
            ],
            [
                "name" => "Hapus product",
                "key" => "delete-product",
                "description" => "Mengahapus produk pada toko"
            ],
            [
                "name" => "Create store",
                "key" => "create-store",
                "description" => "Menambahkan toko atau usaha"
            ],
            [
                "name" => "Show store",
                "key" => "show-store",
                "description" => "Melihat master toko"
            ],
            [
                "name" => "Update store",
                "key" => "update-store",
                "description" => "Mengubah detail toko/usaha"
            ],
            [
                "name" => "Hapus store",
                "key" => "delete-store",
                "description" => "Mengahapus toko"
            ],
            [
                "name" => "Create user",
                "key" => "create-user",
                "description" => "Menambahkan user pegawai/staf"
            ],
            [
                "name" => "Show user",
                "key" => "show-user",
                "description" => "Melihat detail user pegawai/staf"
            ],
            [
                "name" => "Update user",
                "key" => "update-user",
                "description" => "Mengubah data user pegawai/staf"
            ],
            [
                "name" => "Hapus user",
                "key" => "delete-user",
                "description" => "Mengahapus data user pegawai"
            ],
        ];

        Permission::insert($initialData);
        // $this->command->info('Initial Permssion Data: Finish');
    }
}
