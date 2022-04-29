<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MotivationBagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('motivation_bages')->insert([
            'title' => 'Свой человек',
            'count' => 10
        ]);

        DB::table('motivation_bages')->insert([
            'title' => 'Цифровой доктор',
            'count' => 10
        ]);

        DB::table('motivation_bages')->insert([
            'title' => 'Амбасcадор',
            'count' => 40
        ]);

        DB::table('motivation_bages')->insert([
            'title' => 'Эксперт',
            'count' => 30
        ]);

        DB::table('motivation_bages')->insert([
            'title' => 'Командный игрок',
            'count' => 25
        ]);

        DB::table('motivation_bages')->insert([
            'title' => 'Генератор идей',
            'count' => 40
        ]);

        DB::table('motivation_bages')->insert([
            'title' => 'Сердцеед',
            'count' => 60
        ]);

        DB::table('motivation_bages')->insert([
            'title' => 'SuperStar',
            'count' => 50
        ]);
    }
}
