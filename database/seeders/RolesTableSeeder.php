<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder {
    public function run(): void {
        DB::table('roles')->upsert([
            ['id'=>1,'name'=>'superadmin','created_at'=>now(),'updated_at'=>now()],
            ['id'=>2,'name'=>'admin','created_at'=>now(),'updated_at'=>now()],
            ['id'=>3,'name'=>'user','created_at'=>now(),'updated_at'=>now()],
        ],  ['id'], ['name','updated_at']);
    }
}
