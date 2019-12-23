<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
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
             'appid' => 'wxffd90c004c129edf',
             'mch_id' => '1',
             'mch_secret' => '1',
             'secret' => '84ee89cc19a3bf755d32ecf4db8597a1',
             'map_key' => '76JBZ-55A6W-3YURV-RO4FM-D7HRE-JDFW6',
             'map_secret_key' => 'LGS1AUdf7Q7qB9fTBVF7Ofv1DiebARAr',
             'access_token' => '1',
             'time_add' => '1',
             'cert_pem' => '1',
             'key_pem' => '1',
             'aboutUs' => '1',
         ]
     ];

      \Illuminate\Support\Facades\DB::table('config')->insert($data);
    }
}
