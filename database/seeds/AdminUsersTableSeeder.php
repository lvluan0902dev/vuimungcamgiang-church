<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
          [
              'name' => 'Admin',
              'phone_number' => '',
              'address' => '',
              'image_name' => '',
              'image_path' => '',
              'type' => 'admin',
              'status' => 1,
              'email' => 'admin@admin.com',
              'password' => \Illuminate\Support\Facades\Hash::make('123456')
          ]
        ];

        foreach ($records as $record)
        {
            \App\Model\AdminUser::create($record);
        }
    }
}
