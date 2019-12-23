<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
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
                'role' => '后台管理员',
                'jurisdictionid' => '1,7,2,8,3,9,4,12,37,5,10,36,6,11,34,35,38,30,31,32,33,39,40',
                'time' => '1570793347',
                'status' => '1',
            ]
        ];

        \Illuminate\Support\Facades\DB::table('role')->insert($data);
    }
}
