<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Project;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@ptsmart.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $manager = User::create([
            'name' => 'Manager Sales',
            'email' => 'manager@ptsmart.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $sales1 = User::create([
            'name' => 'Sales 1',
            'email' => 'sales@ptsmart.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        $sales2 = User::create([
            'name' => 'Sales 2',
            'email' => 'sales2@ptsmart.com',
            'password' => Hash::make('password'),
            'role' => 'sales',
        ]);

        $products = [
            [
                'name' => 'Smart Fiber 50',
                'code' => 'FIB-50',
                'description' => 'Paket internet fiber optic dengan kecepatan 50 Mbps, cocok untuk rumah tangga dan UMKM.',
                'price' => 350000,
                'type' => 'fiber',
                'speed' => '50 Mbps',
                'is_active' => true,
            ],
            [
                'name' => 'Smart Fiber 100',
                'code' => 'FIB-100',
                'description' => 'Paket internet fiber optic dengan kecepatan 100 Mbps, cocok untuk kantor kecil-menengah.',
                'price' => 550000,
                'type' => 'fiber',
                'speed' => '100 Mbps',
                'is_active' => true,
            ],
            [
                'name' => 'Smart Fiber 200',
                'code' => 'FIB-200',
                'description' => 'Paket internet fiber optic dengan kecepatan 200 Mbps, cocok untuk perusahaan.',
                'price' => 850000,
                'type' => 'fiber',
                'speed' => '200 Mbps',
                'is_active' => true,
            ],
            [
                'name' => 'Smart Wireless 20',
                'code' => 'WIR-20',
                'description' => 'Paket internet wireless dengan kecepatan 20 Mbps, solusi untuk area tanpa fiber.',
                'price' => 250000,
                'type' => 'wireless',
                'speed' => '20 Mbps',
                'is_active' => true,
            ],
            [
                'name' => 'Smart Wireless 50',
                'code' => 'WIR-50',
                'description' => 'Paket internet wireless dengan kecepatan 50 Mbps.',
                'price' => 400000,
                'type' => 'wireless',
                'speed' => '50 Mbps',
                'is_active' => true,
            ],
            [
                'name' => 'Smart Dedicated 1G',
                'code' => 'DED-1G',
                'description' => 'Dedicated line 1 Gbps, untuk enterprise.',
                'price' => 15000000,
                'type' => 'dedicated',
                'speed' => '1 Gbps',
                'is_active' => true,
            ],
            [
                'name' => 'Smart Dedicated 10G',
                'code' => 'DED-10G',
                'description' => 'Dedicated line 10 Gbps, untuk data center.',
                'price' => 50000000,
                'type' => 'dedicated',
                'speed' => '10 Gbps',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }

        $leads = [
            [
                'company_name' => 'PT. Maju Jaya Surabaya',
                'contact_person' => 'Budi Santoso',
                'email' => 'budi@majujaya.com',
                'phone' => '031-5551234',
                'address' => 'Jl. Pemuda No. 45, Surabaya Pusat 60271',
                'status' => 'new',
                'created_by' => $sales1->id,
            ],
            [
                'company_name' => 'CV. Teknologi Nusantara',
                'contact_person' => 'Siti Rahayu',
                'email' => 'siti@teknusa.com',
                'phone' => '031-5679876',
                'address' => 'Jl. Basuki Rahmat No. 78, Surabaya Pusat 60261',
                'status' => 'contacted',
                'created_by' => $sales1->id,
            ],
            [
                'company_name' => 'PT. Digital Solusi',
                'contact_person' => 'Andi Wijaya',
                'email' => 'andi@digitalsolusi.id',
                'phone' => '031-8437890',
                'address' => 'Jl. Raya Darmo No. 123, Surabaya Selatan 60241',
                'status' => 'qualified',
                'created_by' => $sales2->id,
            ],
            [
                'company_name' => 'Koperasi Sejahtera',
                'contact_person' => 'Dewi Lestari',
                'email' => 'dewi@kopsejahtera.co.id',
                'phone' => '031-7314567',
                'address' => 'Jl. Raya Gubeng No. 56, Surabaya Timur 60281',
                'status' => 'qualified',
                'created_by' => $sales2->id,
            ],
            [
                'company_name' => 'PT. Global Trading',
                'contact_person' => 'Hendra Kusuma',
                'email' => 'hendra@globaltrading.com',
                'phone' => '031-7493456',
                'address' => 'Jl. Mayjen Sungkono No. 88, Surabaya Barat 60225',
                'status' => 'unqualified',
                'created_by' => $sales1->id,
            ],
        ];

        foreach ($leads as $leadData) {
            Lead::create($leadData);
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Accounts:');
        $this->command->info('- Admin: admin@ptsmart.com / password');
        $this->command->info('- Manager: manager@ptsmart.com / password');
        $this->command->info('- Sales: sales@ptsmart.com / password');
    }
}
