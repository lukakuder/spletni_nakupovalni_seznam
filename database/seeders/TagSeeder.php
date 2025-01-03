<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = ['Živila', 'Elektronika', 'Oblačila', 'Gospodinjstvo', 'Zdravje', 'Lepota', 'Pijače', 'Prigrizki', 'Sadje', 'Zelenjava', 'Mlečni Izdelki', 'Zamrznjeno', 'Meso', 'Pekarna', 'Čiščenje', 'Otroci', 'Hišni Ljubljenčki', 'Darila', 'Pisarna', 'Prosti Čas'];

        foreach ($tags as $tag) {
            Tag::findOrCreate($tag);
        }
    }
}
