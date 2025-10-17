<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComplaintType;

class ComplaintTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['event', 'group', 'product', 'donation', 'autre'];
        foreach ($types as $t) {
            ComplaintType::firstOrCreate(['name' => $t]);
        }
    }
}
