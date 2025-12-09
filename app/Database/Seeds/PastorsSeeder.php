<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PastorsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'first_name' => 'Pastor',
                'last_name' => 'Julius',
                'email' => 'julius@watotochurch.com',
                'phone' => '+256700111111',
                'campus_id' => 1, // Downtown Campus
                'specialization' => 'Wedding Ceremonies, Counseling',
                'bio' => 'Senior Pastor with over 15 years of experience in wedding ceremonies and pre-marital counseling.',
                'image_path' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Pastor',
                'last_name' => 'Sarah',
                'email' => 'sarah.pastor@watotochurch.com',
                'phone' => '+256700222222',
                'campus_id' => 2, // Ntinda Campus
                'specialization' => 'Wedding Ceremonies, Youth Ministry',
                'bio' => 'Passionate about supporting young couples in their journey to marriage.',
                'image_path' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Pastor',
                'last_name' => 'David',
                'email' => 'david@watotochurch.com',
                'phone' => '+256700333333',
                'campus_id' => 3, // Bweyogerere Campus
                'specialization' => 'Wedding Ceremonies, Marriage Counseling',
                'bio' => 'Experienced in both traditional and contemporary wedding ceremonies.',
                'image_path' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Pastor',
                'last_name' => 'Grace',
                'email' => 'grace.pastor@watotochurch.com',
                'phone' => '+256700444444',
                'campus_id' => 4, // Gulu Campus
                'specialization' => 'Wedding Ceremonies, Community Outreach',
                'bio' => 'Serving the northern region with dedication and love.',
                'image_path' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'first_name' => 'Pastor',
                'last_name' => 'Mark',
                'email' => 'mark@watotochurch.com',
                'phone' => '+256700555555',
                'campus_id' => 5, // Jinja Campus
                'specialization' => 'Wedding Ceremonies, Family Ministry',
                'bio' => 'Committed to strengthening families through faith and love.',
                'image_path' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('pastors')->insertBatch($data);
    }
}
