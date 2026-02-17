<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => '座敷A', 'type' => 'tatami', 'capacity' => 4, 'is_combinable' => true, 'combine_group' => 1, 'sort_order' => 1],
            ['name' => '座敷B', 'type' => 'tatami', 'capacity' => 4, 'is_combinable' => true, 'combine_group' => 1, 'sort_order' => 2],
            ['name' => '個室1', 'type' => 'private', 'capacity' => 4, 'is_combinable' => false, 'combine_group' => null, 'sort_order' => 3],
            ['name' => '個室2', 'type' => 'private', 'capacity' => 4, 'is_combinable' => false, 'combine_group' => null, 'sort_order' => 4],
            ['name' => 'テーブル1', 'type' => 'regular', 'capacity' => 4, 'is_combinable' => false, 'combine_group' => null, 'sort_order' => 5],
            ['name' => 'テーブル2', 'type' => 'regular', 'capacity' => 4, 'is_combinable' => false, 'combine_group' => null, 'sort_order' => 6],
            ['name' => 'テーブル3', 'type' => 'regular', 'capacity' => 4, 'is_combinable' => false, 'combine_group' => null, 'sort_order' => 7],
        ];

        foreach ($rows as $row) {
            Table::updateOrCreate(['name' => $row['name']], array_merge($row, ['is_active' => true]));
        }
    }
}
