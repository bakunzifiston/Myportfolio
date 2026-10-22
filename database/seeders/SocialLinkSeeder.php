<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    /** URLs are left empty on purpose; links stay hidden until real profile URLs are added. */
    public function run(): void
    {
        $links = [
            ['platform' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'linkedin', 'sort_order' => 1],
            ['platform' => 'github', 'label' => 'GitHub', 'icon' => 'github', 'sort_order' => 2],
        ];

        foreach ($links as $link) {
            SocialLink::firstOrCreate(['platform' => $link['platform']], $link + ['url' => null, 'is_visible' => true]);
        }
    }
}
