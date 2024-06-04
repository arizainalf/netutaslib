<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $usersData = [
            ['nama' => 'Ari Zainal Fauziah', 'email' => 'arizainalf@gmail.com', 'password' => bcrypt('123123123'), 'role' => 'Admin'],
            ['nama' => 'Ihsan Maulana', 'email' => 'ihsanm@gmail.com', 'password' => bcrypt('11221122'), 'role' => 'Admin'],
        ];
        DB::table('users')->insert($usersData);

        $categoriesData = [
            ['nama' => 'Fiksi'],
            ['nama' => 'Non-Fiksi'],
            ['nama' => 'Agama'],
        ];
        DB::table('categories')->insert($categoriesData);

        $booksData = [
            ['category_id' => '1', 'judul' => 'lorem ipsum dolor sit amet', 'penulis' => 'ariz', 'penerbit' => 'aldi', 'tahun' => '2020', 'image' => 'jFlqt1JYQYC5CmGa0qvNBCty4LbdtTCmgQ4pHeOe.jpg', 'stok' => '1'],
        ];
        DB::table('books')->insert($booksData);

        $membersData = [
            ['nisn' => '12221212', 'nipd' => '123123123', 'nama' => 'Ihsan Maulana', 'jenis_kelamin' => 'Laki - Laki'],
            ['nisn' => '12221359', 'nipd' => '1234123456', 'nama' => 'Ari Zainal Fauziah', 'jenis_kelamin' => 'Laki - Laki'],
            ['nisn' => '12220037', 'nipd' => '1234512345', 'nama' => 'Vidi Azzahra Mujahidillah', 'jenis_kelamin' => 'Perempuan'],
            ['nisn' => '12221360', 'nipd' => '12312373', 'nama' => 'Toto Fauzi', 'jenis_kelamin' => 'Laki - Laki'],
        ];

        DB::table('members')->insert($membersData);
    }
}