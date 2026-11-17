<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Registration;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin / Panitia User
        $admin = User::firstOrCreate(
            ['email' => 'admin@workshoptix.test'],
            [
                'name' => 'Panitia Workshop IT',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '081234567890',
                'institution' => 'SMK Pesat IT XPRO',
            ]
        );

        // 2. Demo Participant User
        $participant = User::firstOrCreate(
            ['email' => 'peserta@workshoptix.test'],
            [
                'name' => 'Ahmad Pratama',
                'password' => Hash::make('password'),
                'role' => 'participant',
                'phone' => '089876543210',
                'institution' => 'SMK Pesat Rekayasa Perangkat Lunak',
            ]
        );

        // 3. Other Participants
        $participant2 = User::firstOrCreate(
            ['email' => 'budi.santoso@example.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'participant',
                'phone' => '081345678912',
                'institution' => 'Universitas Indonesia',
            ]
        );

        $participant3 = User::firstOrCreate(
            ['email' => 'siti.nurhaliza@example.com'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password'),
                'role' => 'participant',
                'phone' => '085678901234',
                'institution' => 'Politeknik Negeri Jakarta',
            ]
        );

        // 4. Workshops
        $workshopsData = [
            [
                'title' => 'Mastering Laravel 12 & Modern Full-Stack Development',
                'slug' => 'mastering-laravel-12-modern-full-stack',
                'category' => 'Web Development',
                'instructor_name' => 'Rizky Fauzi, S.Kom.',
                'instructor_title' => 'Senior Backend Architect at TechVentures',
                'description' => 'Pelajari arsitektur Laravel enterprise, modular development, RESTful API terstandarisasi, repository pattern, dan integrasi modern Tailwind CSS v4 dengan performa tinggi untuk skalabilitas aplikasi tingkat lanjut.',
                'target_audience' => 'Siswa RPL, Mahasiswa Informatika, dan Web Developer pemula hingga menengah yang ingin naik level.',
                'learning_points' => "Arsitektur Clean Laravel & Service-Repository Pattern\nOptimasi Database & Teknik Mencegah N+1 Queries\nPembuatan REST API Aman dengan Token Bearer & Sanctum\nIntegrasi Tailwind CSS v4 & Komponen Blade Dinamis\nDeployment Praktis ke Cloud Infrastructure",
                'prerequisites' => 'Memahami dasar pemrograman PHP, sintaksis OOP, dan query dasar database relasional.',
                'date' => '2026-12-05',
                'start_time' => '08:30',
                'end_time' => '15:30',
                'location_type' => 'offline',
                'location' => 'Lab Software Engineering Lt. 3, Kampus Pesat IT XPRO',
                'price' => 150000,
                'quota' => 35,
                'status' => 'available',
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'DevOps Essentials: CI/CD Pipeline, Docker & Kubernetes',
                'slug' => 'devops-essentials-cicd-docker-kubernetes',
                'category' => 'Cloud & DevOps',
                'instructor_name' => 'Fajar Nugraha, M.T.',
                'instructor_title' => 'Lead Cloud Engineer & Certified Kubernetes Administrator',
                'description' => 'Hands-on workshop praktis kontainerisasi aplikasi web, otomatisasi build dan test deployment menggunakan GitHub Actions, serta konfigurasi orkestrasi cluster Kubernetes.',
                'target_audience' => 'Software Engineer, DevOps Enthusiast, dan Sysadmin yang ingin menguasai cloud infrastructure modern.',
                'learning_points' => "Dasar Containerization dengan Docker & Multi-stage Build\nMenulis Pipeline CI/CD Efisien di GitHub Actions\nManajemen Environment, Secrets, dan ConfigMap\nDeploy Aplikasi ke Cluster Kubernetes Production-Ready\nMonitoring Real-time & Health Checks",
                'prerequisites' => 'Mengenal perintah dasar Linux Command Line dan konsep web hosting.',
                'date' => '2026-12-12',
                'start_time' => '09:00',
                'end_time' => '16:00',
                'location_type' => 'hybrid',
                'location' => 'Auditorium Pesat IT & Live Streaming Hybrid Interactive',
                'price' => 175000,
                'quota' => 40,
                'status' => 'available',
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Cyber Security Defensive & Penetration Testing Fundamentals',
                'slug' => 'cyber-security-defensive-penetration-testing',
                'category' => 'Cyber Security',
                'instructor_name' => 'Dimas Wicaksono, CEH',
                'instructor_title' => 'Head of Cyber Security & Threat Intelligence',
                'description' => 'Pelatihan intensif mengamankan aplikasi web dari ancaman serangan siber modern berdasarkan standar OWASP Top 10, audit keamanan sistem, dan teknik ethical hacking terarah.',
                'target_audience' => 'Siswa IT, Network Administrator, Web Developer, dan Security Enthusiast.',
                'learning_points' => "Identifikasi & Eksploitasi OWASP Top 10 Vulnerabilities\nAnalisis Lalu Lintas Jaringan dengan Wireshark & Burp Suite\nImplementasi Defense-in-Depth pada Aplikasi Web\nHardening Server Linux, SSH, dan Database\nTeknik Mitigasi DDoS dan Brute Force Attack",
                'prerequisites' => 'Pengetahuan dasar networking (TCP/IP) dan protokol HTTP/HTTPS.',
                'date' => '2026-12-19',
                'start_time' => '08:30',
                'end_time' => '15:00',
                'location_type' => 'offline',
                'location' => 'Lab Cyber Security & Networking, Kampus Pesat IT XPRO',
                'price' => 200000,
                'quota' => 30,
                'status' => 'available',
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Practical Generative AI & Machine Learning with Python',
                'slug' => 'practical-generative-ai-machine-learning-python',
                'category' => 'AI & Data Science',
                'instructor_name' => 'Dr. Nadia Safitri',
                'instructor_title' => 'AI Research Scientist & Data Strategy Consultant',
                'description' => 'Membangun aplikasi cerdas terapan dengan integrasi LLM API, implementasi Retrieval-Augmented Generation (RAG), vector database, dan otomatisasi task berbasis kecerdasan buatan.',
                'target_audience' => 'Programmer, Data Enthusiast, Mahasiswa, dan Siswa RPL yang tertarik dunia AI.',
                'learning_points' => "Konsep Fundamental Large Language Models (LLM)\nTeknik Advanced Prompt Engineering & Function Calling\nMembangun Solusi RAG dengan Vector Database\nFine-tuning Kontekstual & Embedding Semantic Search\nDeploy Microservice AI Siap Pakai",
                'prerequisites' => 'Familiar dengan sintaksis dasar bahasa pemrograman Python.',
                'date' => '2027-01-09',
                'start_time' => '09:00',
                'end_time' => '15:30',
                'location_type' => 'online',
                'location' => 'Online Interactive Live Training via Google Meet',
                'price' => 125000,
                'quota' => 50,
                'status' => 'available',
                'is_featured' => true,
                'image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Cross-Platform Mobile App Development with Flutter',
                'slug' => 'cross-platform-mobile-app-development-flutter',
                'category' => 'Mobile Development',
                'instructor_name' => 'Eko Prasetyo, S.Kom.',
                'instructor_title' => 'Lead Mobile Engineer at FinTech Global',
                'description' => 'Panduan lengkap membangun aplikasi mobile berperforma native untuk Android dan iOS menggunakan satu basis kode Flutter dengan arsitektur Bloc yang rapi dan skalabel.',
                'target_audience' => 'Developer yang ingin merambah pengembangan aplikasi mobile modern.',
                'learning_points' => "Penguasaan Widget Tree, Layouting, dan Material Design 3\nState Management Skalaber dengan Bloc Pattern\nIntegrasi REST API, Interceptor, dan JSON Deserialization\nLocal Storage Caching dengan Hive & SQLite\nBuild APK release dan persiapan publikasi Play Store",
                'prerequisites' => 'Dasar pemrograman berorientasi objek (OOP) seperti Dart, Java, atau TypeScript.',
                'date' => '2027-01-16',
                'start_time' => '08:30',
                'end_time' => '15:00',
                'location_type' => 'offline',
                'location' => 'Lab Mobile Development, Kampus Pesat IT XPRO',
                'price' => 150000,
                'quota' => 30,
                'status' => 'available',
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'UI/UX Design Systems: From Wireframe to High-Fidelity Prototype',
                'slug' => 'ui-ux-design-systems-wireframe-to-prototype',
                'category' => 'UI/UX Design',
                'instructor_name' => 'Anisa Rahmawati, S.Ds.',
                'instructor_title' => 'Principal Product Designer at DesignHub Studio',
                'description' => 'Mendesain antarmuka produk digital modern yang konsisten dan fungsional. Pelajari pembuatan token visual, typography hierarchy, auto-layout, dan reusable components di Figma.',
                'target_audience' => 'UI/UX Designer, Frontend Developer, dan Siswa Desain / IT.',
                'learning_points' => "User Journey Mapping & Information Architecture\nPembuatan Reusable Components & Variants di Figma\nPenggunaan Auto-layout, Variables, dan Dynamic Modes\nMicro-interactions & Interactive Prototyping\nDesign Handoff yang Mulus ke Tim Frontend Developer",
                'prerequisites' => 'Memiliki akun Figma gratis dan pemahaman dasar elemen visual website.',
                'date' => '2027-01-23',
                'start_time' => '09:00',
                'end_time' => '14:30',
                'location_type' => 'offline',
                'location' => 'Creative Studio & Lab Komputer Grafis, Kampus Pesat IT',
                'price' => 120000,
                'quota' => 25,
                'status' => 'available',
                'is_featured' => false,
                'image' => 'https://images.unsplash.com/photo-1581291518857-4e27b48ff24e?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        $createdWorkshops = [];
        foreach ($workshopsData as $data) {
            $createdWorkshops[] = Workshop::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        // 5. Seed Registration & Verified Payment & Ticket for Demo Participant
        // Workshop 1: Verified -> has Ticket
        $w1 = $createdWorkshops[0];
        $reg1 = Registration::firstOrCreate(
            [
                'user_id' => $participant->id,
                'workshop_id' => $w1->id,
            ],
            [
                'registration_code' => 'REG-'.strtoupper(Str::random(8)),
                'full_name' => $participant->name,
                'email' => $participant->email,
                'phone' => $participant->phone,
                'institution' => $participant->institution,
                'notes' => 'Sangat tertarik mempelajari implementasi N+1 optimization dan Laravel enterprise.',
                'status' => 'paid',
            ]
        );

        $pay1 = Payment::firstOrCreate(
            ['registration_id' => $reg1->id],
            [
                'payment_code' => 'PAY-'.strtoupper(Str::random(8)),
                'user_id' => $participant->id,
                'amount' => $w1->price,
                'payment_method' => 'Bank Transfer BCA',
                'payment_proof' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
                'status' => 'verified',
                'admin_notes' => 'Pembayaran lunas terverifikasi rekening koran BCA.',
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]
        );

        $ticket1 = Ticket::firstOrCreate(
            ['registration_id' => $reg1->id],
            [
                'ticket_code' => 'TIX-WTX-'.date('Y').'-'.strtoupper(Str::random(6)),
                'user_id' => $participant->id,
                'workshop_id' => $w1->id,
                'qr_token' => Str::uuid()->toString(),
                'status' => 'valid',
            ]
        );

        // Workshop 2 for Participant 2: Pending Payment (waiting for verification)
        $w2 = $createdWorkshops[1];
        $reg2 = Registration::firstOrCreate(
            [
                'user_id' => $participant2->id,
                'workshop_id' => $w2->id,
            ],
            [
                'registration_code' => 'REG-'.strtoupper(Str::random(8)),
                'full_name' => $participant2->name,
                'email' => $participant2->email,
                'phone' => $participant2->phone,
                'institution' => $participant2->institution,
                'notes' => 'Mohon verifikasi secepatnya.',
                'status' => 'pending_payment',
            ]
        );

        Payment::firstOrCreate(
            ['registration_id' => $reg2->id],
            [
                'payment_code' => 'PAY-'.strtoupper(Str::random(8)),
                'user_id' => $participant2->id,
                'amount' => $w2->price,
                'payment_method' => 'Bank Transfer Mandiri',
                'payment_proof' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
                'status' => 'pending',
                'admin_notes' => null,
            ]
        );

        // Workshop 3 for Participant 3: Verified -> Ticket
        $w3 = $createdWorkshops[2];
        $reg3 = Registration::firstOrCreate(
            [
                'user_id' => $participant3->id,
                'workshop_id' => $w3->id,
            ],
            [
                'registration_code' => 'REG-'.strtoupper(Str::random(8)),
                'full_name' => $participant3->name,
                'email' => $participant3->email,
                'phone' => $participant3->phone,
                'institution' => $participant3->institution,
                'status' => 'paid',
            ]
        );

        Payment::firstOrCreate(
            ['registration_id' => $reg3->id],
            [
                'payment_code' => 'PAY-'.strtoupper(Str::random(8)),
                'user_id' => $participant3->id,
                'amount' => $w3->price,
                'payment_method' => 'QRIS Dinamis',
                'payment_proof' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
                'status' => 'verified',
                'admin_notes' => 'Verifikasi otomatis QRIS.',
                'verified_at' => now(),
                'verified_by' => $admin->id,
            ]
        );

        Ticket::firstOrCreate(
            ['registration_id' => $reg3->id],
            [
                'ticket_code' => 'TIX-WTX-'.date('Y').'-'.strtoupper(Str::random(6)),
                'user_id' => $participant3->id,
                'workshop_id' => $w3->id,
                'qr_token' => Str::uuid()->toString(),
                'status' => 'valid',
            ]
        );
    }
}
