<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'website_title' => 'PNE Pizza - Quality Pizza & Community Service',
            'keywords' => 'pizza, community service, love kitchen, restaurant, local business',
            'description' => 'Your local pizza restaurant committed to serving the community with quality food and exceptional service.',
            'og_title' => 'PNE Pizza - Quality Pizza & Community Service',
            'og_image_url' => 'https://example.com/og-image.jpg',
            'og_description' => 'Join us in making a difference while enjoying the best pizza in town.',
        ]);
    }
}
