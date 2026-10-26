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
                'name' => 'Logic Models',
                'issuer' => 'Humentum',
                'issued_on' => '2026-10-25',
                'description' => 'Online course on building logic models that connect project activities to outputs, outcomes and long-term impact.',
            ],
            [
                'name' => 'Monitoring, Evaluation, and Learning for Development Professionals (MEAL DPro)',
                'issuer' => 'Humentum',
                'issued_on' => '2026-10-22',
                'description' => 'Online course on monitoring, evaluation, accountability and learning practices for development projects, based on the MEAL DPro guide.',
            ],
            [
                'name' => 'What is Project Management?',
                'issuer' => 'Humentum',
                'issued_on' => '2026-10-21',
                'description' => 'Online course introducing the fundamentals of project management and the project life cycle in development work.',
            ],
            [
                'name' => 'Teaching English as a Foreign Language (TEFL), 120 Hours, with Distinction',
                'issuer' => 'TEFL Professional Institute – Teacher Record',
                'issued_on' => '2023-02-01',
                'credential_id' => 'TR2397942172',
                'description' => '120-hour course in methods for teaching English as a foreign language, completed with Distinction.',
            ],
        ];

        foreach ($certifications as $i => $certification) {
            $record = Certification::firstOrCreate(
                ['name' => $certification['name']],
                $certification + ['is_published' => true, 'sort_order' => $i + 1],
            );

            // Fill in a description added after the record was first seeded, without overwriting one edited in the admin area.
            if (blank($record->description) && filled($certification['description'] ?? null)) {
                $record->update(['description' => $certification['description']]);
            }
        }
    }
}
