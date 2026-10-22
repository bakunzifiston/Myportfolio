<?php

namespace Database\Seeders;

use App\Models\Certification;
use Illuminate\Database\Seeder;

/**
 * Certificates held, as shown on the certificate documents provided.
 */
class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        $certifications = [
            [
                'name' => 'Monitoring, Evaluation, and Learning for Development Professionals (MEAL DPro)',
                'issuer' => 'Humentum',
                'issued_on' => '2026-10-22',
            ],
            [
                'name' => 'What is Project Management?',
                'issuer' => 'Humentum',
                'issued_on' => '2026-10-21',
            ],
            [
                'name' => 'Teaching English as a Foreign Language (TEFL), 120 Hours, with Distinction',
                'issuer' => 'TEFL Professional Institute – Teacher Record',
                'issued_on' => '2023-02-01',
                'credential_id' => 'TR2397942172',
            ],
        ];

        foreach ($certifications as $i => $certification) {
            Certification::firstOrCreate(
                ['name' => $certification['name']],
                $certification + ['is_published' => true, 'sort_order' => $i + 1],
            );
        }
    }
}
