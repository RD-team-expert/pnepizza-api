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
            'Google_Maps_API_Key' => 'PNE Pizza - Quality Pizza & Community Service',
            'Google_Analytics_ID' => 'https://example.com/og-image.jpg',
        ]);
    }
}
