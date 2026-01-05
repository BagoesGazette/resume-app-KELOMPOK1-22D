<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class KandidatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * 10 Kandidat dengan background berbeda-beda:
     * - Fresh Graduate (3 orang)
     * - Junior (3 orang)
     * - Mid-Level (2 orang)
     * - Senior (2 orang)
     */
    public function run(): void
    {
        $kandidatData = [
            // ===== FRESH GRADUATE (3) =====
            [
                'name'  => 'Andi Setiawan',
                'email' => 'andi.setiawan@gmail.com',
                'verified' => true,
                'verified_days_ago' => 1,
                'profile' => [
                    'level' => 'Fresh Graduate',
                    'education' => 'S1 Teknik Informatika - Universitas Indonesia (2024)',
                    'gpa' => '3.85',
                    'skills' => ['Laravel', 'PHP', 'MySQL', 'Git', 'HTML', 'CSS', 'JavaScript'],
                    'experience_years' => 0,
                ]
            ],
            [
                'name'  => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@gmail.com',
                'verified' => true,
                'verified_days_ago' => 2,
                'profile' => [
                    'level' => 'Fresh Graduate',
                    'education' => 'S1 Sistem Informasi - Institut Teknologi Bandung (2024)',
                    'gpa' => '3.92',
                    'skills' => ['React', 'Node.js', 'MongoDB', 'Express', 'TypeScript', 'Tailwind CSS'],
                    'experience_years' => 0,
                ]
            ],
            [
                'name'  => 'Budi Prasetyo',
                'email' => 'budi.prasetyo@gmail.com',
                'verified' => true,
                'verified_days_ago' => 3,
                'profile' => [
                    'level' => 'Fresh Graduate',
                    'education' => 'S1 Teknik Komputer - Universitas Gadjah Mada (2024)',
                    'gpa' => '3.75',
                    'skills' => ['Python', 'Django', 'PostgreSQL', 'Docker', 'Git', 'REST API'],
                    'experience_years' => 0,
                ]
            ],

            // ===== JUNIOR (3) =====
            [
                'name'  => 'Dewi Lestari',
                'email' => 'dewi.lestari@gmail.com',
                'verified' => true,
                'verified_days_ago' => 5,
                'profile' => [
                    'level' => 'Junior',
                    'education' => 'S1 Ilmu Komputer - Universitas Brawijaya (2022)',
                    'gpa' => '3.65',
                    'skills' => ['Laravel', 'Vue.js', 'MySQL', 'Redis', 'Git', 'RESTful API', 'Bootstrap'],
                    'experience_years' => 2,
                    'last_position' => 'Junior Web Developer - PT Digital Solusi'
                ]
            ],
            [
                'name'  => 'Rizky Firmansyah',
                'email' => 'rizky.firmansyah@gmail.com',
                'verified' => true,
                'verified_days_ago' => 4,
                'profile' => [
                    'level' => 'Junior',
                    'education' => 'S1 Teknik Informatika - Universitas Diponegoro (2021)',
                    'gpa' => '3.70',
                    'skills' => ['React', 'Next.js', 'Node.js', 'PostgreSQL', 'GraphQL', 'AWS', 'Docker'],
                    'experience_years' => 2,
                    'last_position' => 'Frontend Developer - Startup Tech Indonesia'
                ]
            ],
            [
                'name'  => 'Indah Permatasari',
                'email' => 'indah.permatasari@gmail.com',
                'verified' => true,
                'verified_days_ago' => 6,
                'profile' => [
                    'level' => 'Junior',
                    'education' => 'S1 Sistem Informasi - Universitas Airlangga (2022)',
                    'gpa' => '3.80',
                    'skills' => ['PHP', 'CodeIgniter', 'Laravel', 'MySQL', 'jQuery', 'Bootstrap', 'Git'],
                    'experience_years' => 2,
                    'last_position' => 'Web Developer - CV Kreasi Digital'
                ]
            ],

            // ===== MID-LEVEL (2) =====
            [
                'name'  => 'Ahmad Fauzi',
                'email' => 'ahmad.fauzi@gmail.com',
                'verified' => true,
                'verified_days_ago' => 7,
                'profile' => [
                    'level' => 'Mid-Level',
                    'education' => 'S1 Teknik Informatika - Institut Teknologi Sepuluh Nopember (2019)',
                    'gpa' => '3.60',
                    'skills' => ['Laravel', 'Vue.js', 'React', 'MySQL', 'MongoDB', 'Redis', 'Docker', 'Kubernetes', 'AWS', 'CI/CD'],
                    'experience_years' => 4,
                    'last_position' => 'Full Stack Developer - PT Tech Solutions Indonesia'
                ]
            ],
            [
                'name'  => 'Maya Anggraini',
                'email' => 'maya.anggraini@gmail.com',
                'verified' => true,
                'verified_days_ago' => 8,
                'profile' => [
                    'level' => 'Mid-Level',
                    'education' => 'S1 Ilmu Komputer - Universitas Padjadjaran (2018)',
                    'gpa' => '3.55',
                    'skills' => ['Node.js', 'Express', 'React', 'Angular', 'PostgreSQL', 'MongoDB', 'Microservices', 'RabbitMQ', 'Jenkins'],
                    'experience_years' => 5,
                    'last_position' => 'Backend Developer - PT Digital Nusantara'
                ]
            ],

            // ===== SENIOR (2) =====
            [
                'name'  => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@gmail.com',
                'verified' => true,
                'verified_days_ago' => 10,
                'profile' => [
                    'level' => 'Senior',
                    'education' => 'S1 Teknik Informatika - Universitas Indonesia (2016)',
                    'gpa' => '3.75',
                    'skills' => ['Laravel', 'Vue.js', 'React', 'Node.js', 'Python', 'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Docker', 'Kubernetes', 'AWS', 'GCP', 'Microservices', 'System Design'],
                    'experience_years' => 7,
                    'last_position' => 'Senior Software Engineer - PT Teknologi Maju Indonesia',
                    'certifications' => ['AWS Certified Solutions Architect', 'Laravel Certified Developer']
                ]
            ],
            [
                'name'  => 'Ratna Sari',
                'email' => 'ratna.sari@gmail.com',
                'verified' => true,
                'verified_days_ago' => 12,
                'profile' => [
                    'level' => 'Senior',
                    'education' => 'S2 Ilmu Komputer - Institut Teknologi Bandung (2017), S1 Teknik Informatika - Universitas Brawijaya (2015)',
                    'gpa' => '3.88',
                    'skills' => ['Laravel', 'Symfony', 'React', 'Vue.js', 'MySQL', 'PostgreSQL', 'MongoDB', 'Elasticsearch', 'Docker', 'Kubernetes', 'AWS', 'Azure', 'System Architecture', 'Team Leadership'],
                    'experience_years' => 8,
                    'last_position' => 'Lead Software Engineer - PT Solusi Digital Indonesia',
                    'certifications' => ['PMP', 'AWS Solutions Architect Professional', 'Scrum Master']
                ]
            ],
        ];

        foreach ($kandidatData as $data) {
            $user = User::create([
                'name'              => $data['name'],
                'email'             => $data['email'],
                'password'          => bcrypt('kandidat123'),
                'email_verified_at' => $data['verified'] 
                    ? Carbon::now()->subDays($data['verified_days_ago'] ?? 0)
                    : null,
            ]);
            
            $user->assignRole('kandidat');

            // Store profile data in user meta or separate table (optional)
            // You can add this if you have a user_profiles table
            // $user->profile()->create($data['profile']);
        }

        // Output info
        $this->command->info('');
        $this->command->info('✅ Successfully seeded 10 Kandidat users:');
        $this->command->info('   - 3 Fresh Graduate');
        $this->command->info('   - 3 Junior (2 years exp)');
        $this->command->info('   - 2 Mid-Level (4-5 years exp)');
        $this->command->info('   - 2 Senior (7-8 years exp)');
        $this->command->info('');
        $this->command->info('🔑 Login credentials (all users):');
        $this->command->info('   Password: kandidat123');
        $this->command->info('');
        $this->command->table(
            ['Name', 'Email', 'Level', 'Experience'],
            array_map(function($data) {
                return [
                    $data['name'],
                    $data['email'],
                    $data['profile']['level'],
                    $data['profile']['experience_years'] . ' years'
                ];
            }, $kandidatData)
        );
    }
}