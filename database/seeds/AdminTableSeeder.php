<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            [
                'id' => 1,
                'username' => 'admin',
                'password' => '21232f297a57a5a743894a0e4a801fc3',
                'headimg' => '../uploads/img/1572422252943825.jpg',
                'time' => '1572422255',
                'role' => '1',
                'status' => '1',
            ]
        ];

        \Illuminate\Support\Facades\DB::table('admin')->insert($data);
    }
}
