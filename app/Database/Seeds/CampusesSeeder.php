<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CampusesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Downtown Campus',
                'location' => 'Kampala',
                'address' => 'Plot 87, Kampala Road, Kampala',
                'capacity' => 500,
                'description' => 'Our flagship campus located in the heart of Kampala city. Features modern facilities and excellent accessibility.',
                'facilities' => json_encode(['Parking', 'AC', 'Sound System', 'Photography Stage', 'Bridal Room']),
                'cost_per_wedding' => 2500000.00,
                'image_path' => 'assets/images/location_downtown.jpg',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Ntinda Campus',
                'location' => 'Ntinda',
                'address' => 'Ntinda Road, Kampala',
                'capacity' => 300,
                'description' => 'Beautiful campus with natural lighting and garden views. Perfect for intimate ceremonies.',
                'facilities' => json_encode(['Garden', 'Natural Light', 'Modern', 'Parking', 'Catering Kitchen']),
                'cost_per_wedding' => 1800000.00,
                'image_path' => 'assets/images/location_lubowa.jpg',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Bweyogerere Campus',
                'location' => 'Bweyogerere',
                'address' => 'Bweyogerere Road, Wakiso',
                'capacity' => 400,
                'description' => 'Spacious campus with ample parking and modern facilities. Great for medium to large celebrations.',
                'facilities' => json_encode(['Spacious', 'Parking', 'Accessible', 'Sound System', 'Video Projection']),
                'cost_per_wedding' => 2000000.00,
                'image_path' => 'assets/images/location_bweyogerere.jpg',
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Gulu Campus',
                'location' => 'Gulu',
                'address' => 'Gulu Town, Northern Uganda',
                'capacity' => 250,
                'description' => 'Serving the northern region with beautiful facilities and warm hospitality.',
                'facilities' => json_encode(['Community Hall', 'Parking', 'Generator', 'Sound System']),
                'cost_per_wedding' => 1500000.00,
                'image_path' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Jinja Campus',
                'location' => 'Jinja',
                'address' => 'Jinja Road, Eastern Uganda',
                'capacity' => 350,
                'description' => 'Located near the source of the Nile with scenic views and modern amenities.',
                'facilities' => json_encode(['Scenic Views', 'Modern', 'Parking', 'AC', 'Catering']),
                'cost_per_wedding' => 1800000.00,
                'image_path' => null,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('campuses')->insertBatch($data);
    }
}
