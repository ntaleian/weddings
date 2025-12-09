<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Phase1_UpdateSettingsAndCampuses extends Migration
{
    public function up()
    {
        // Update advance_booking_days from 90 to 180 (6 months)
        $this->db->table('settings')
            ->where('setting_key', 'advance_booking_days')
            ->update(['setting_value' => '180']);

        // Update base_wedding_fee from 500000 to 600000
        $this->db->table('settings')
            ->where('setting_key', 'base_wedding_fee')
            ->update(['setting_value' => '600000']);

        // Add new fee settings
        $settings = [
            [
                'setting_key' => 'church_admin_fee',
                'setting_value' => '450000',
                'setting_type' => 'number',
                'description' => 'Church administration costs',
                'category' => 'wedding_fees',
                'is_active' => 1
            ],
            [
                'setting_key' => 'worship_tech_fee',
                'setting_value' => '150000',
                'setting_type' => 'number',
                'description' => 'Worship/Tech Team costs',
                'category' => 'wedding_fees',
                'is_active' => 1
            ],
            [
                'setting_key' => 'gazetted_venue_fee_20km',
                'setting_value' => '200000',
                'setting_type' => 'number',
                'description' => 'Additional fee for gazetted venues within 20km',
                'category' => 'wedding_fees',
                'is_active' => 1
            ],
            [
                'setting_key' => 'gazetted_venue_fee_20_50km',
                'setting_value' => '300000',
                'setting_type' => 'number',
                'description' => 'Additional fee for gazetted venues between 20-50km',
                'category' => 'wedding_fees',
                'is_active' => 1
            ],
            [
                'setting_key' => 'certificate_reissue_fee',
                'setting_value' => '50000',
                'setting_type' => 'number',
                'description' => 'Fee for certificate re-issue',
                'category' => 'wedding_fees',
                'is_active' => 1
            ],
            [
                'setting_key' => 'payment_deadline_days',
                'setting_value' => '60',
                'setting_type' => 'number',
                'description' => 'Days before wedding that payment must be completed (2 months)',
                'category' => 'wedding',
                'is_active' => 1
            ],
            [
                'setting_key' => 'application_deadline_days',
                'setting_value' => '60',
                'setting_type' => 'number',
                'description' => 'Days before wedding that application must be submitted (2 months)',
                'category' => 'wedding',
                'is_active' => 1
            ],
            [
                'setting_key' => 'wedding_days_allowed',
                'setting_value' => 'saturday',
                'setting_type' => 'string',
                'description' => 'Days of week when weddings are allowed (saturday only)',
                'category' => 'wedding',
                'is_active' => 1
            ],
            [
                'setting_key' => 'wedding_time_slots',
                'setting_value' => '["09:00","11:00","13:00"]',
                'setting_type' => 'json',
                'description' => 'Available wedding time slots (9 AM, 11 AM, 1 PM)',
                'category' => 'wedding',
                'is_active' => 1
            ]
        ];

        foreach ($settings as $setting) {
            // Check if setting already exists
            $exists = $this->db->table('settings')
                ->where('setting_key', $setting['setting_key'])
                ->countAllResults();

            if ($exists == 0) {
                $this->db->table('settings')->insert($setting);
            }
        }

        // Deactivate Nansana campus (not in guidelines)
        $this->db->table('campuses')
            ->where('name', 'Nansana Campus')
            ->update(['is_active' => 0]);

        // Add missing campuses
        $campuses = [
            [
                'name' => 'Kyengera Campus',
                'location' => 'Kyengera, Wakiso',
                'capacity' => 500,
                'cost' => 1500000.00,
                'description' => 'Modern facility in Kyengera with excellent accessibility.',
                'facilities' => 'Sound system, Parking for 120 cars, Bridal suite, Garden area',
                'is_active' => 1
            ],
            [
                'name' => 'Entebbe Campus',
                'location' => 'Entebbe, Wakiso',
                'capacity' => 400,
                'cost' => 1400000.00,
                'description' => 'Beautiful venue in Entebbe with scenic surroundings.',
                'facilities' => 'Sound system, Parking for 100 cars, Bridal suite, Outdoor space',
                'is_active' => 1
            ],
            [
                'name' => 'Gulu Campus',
                'location' => 'Gulu, Northern Uganda',
                'capacity' => 600,
                'cost' => 1600000.00,
                'description' => 'Spacious venue in Gulu serving the northern region.',
                'facilities' => 'Sound system, Parking for 150 cars, Bridal suite, Reception hall',
                'is_active' => 1
            ],
            [
                'name' => 'Mbarara Campus',
                'location' => 'Mbarara, Western Uganda',
                'capacity' => 550,
                'cost' => 1550000.00,
                'description' => 'Modern facility in Mbarara serving the western region.',
                'facilities' => 'Sound system, Parking for 130 cars, Bridal suite, Garden area',
                'is_active' => 1
            ],
            [
                'name' => 'Ssuubi Campus',
                'location' => 'Ssuubi Watoto Village',
                'capacity' => 300,
                'cost' => 1000000.00,
                'description' => 'Intimate venue at Ssuubi Watoto Village.',
                'facilities' => 'Basic sound system, Parking for 60 cars, Garden ceremony area',
                'is_active' => 1
            ],
            [
                'name' => 'Biira Campus',
                'location' => 'Biira Watoto Village',
                'capacity' => 300,
                'cost' => 1000000.00,
                'description' => 'Intimate venue at Biira Watoto Village.',
                'facilities' => 'Basic sound system, Parking for 60 cars, Garden ceremony area',
                'is_active' => 1
            ]
        ];

        foreach ($campuses as $campus) {
            // Check if campus already exists
            $exists = $this->db->table('campuses')
                ->where('name', $campus['name'])
                ->countAllResults();

            if ($exists == 0) {
                $this->db->table('campuses')->insert($campus);
            }
        }

        // Update venue_time_slots to match guidelines (Saturdays only: 9 AM, 11 AM, 1 PM)
        // First, clear existing time slots
        $this->db->table('venue_time_slots')->truncate();

        // Get all active campuses
        $activeCampuses = $this->db->table('campuses')
            ->where('is_active', 1)
            ->get()
            ->getResultArray();

        // Insert time slots for each campus (Saturdays only)
        $timeSlots = [
            ['start_time' => '09:00:00', 'end_time' => '12:00:00'],
            ['start_time' => '11:00:00', 'end_time' => '14:00:00'],
            ['start_time' => '13:00:00', 'end_time' => '16:00:00']
        ];

        foreach ($activeCampuses as $campus) {
            foreach ($timeSlots as $slot) {
                $this->db->table('venue_time_slots')->insert([
                    'campus_id' => $campus['id'],
                    'day_of_week' => 'saturday',
                    'start_time' => $slot['start_time'],
                    'end_time' => $slot['end_time'],
                    'is_available' => 1,
                    'max_bookings' => 1
                ]);
            }
        }
    }

    public function down()
    {
        // Revert settings
        $this->db->table('settings')
            ->where('setting_key', 'advance_booking_days')
            ->update(['setting_value' => '90']);

        $this->db->table('settings')
            ->where('setting_key', 'base_wedding_fee')
            ->update(['setting_value' => '500000']);

        // Remove new settings
        $newSettings = [
            'church_admin_fee',
            'worship_tech_fee',
            'gazetted_venue_fee_20km',
            'gazetted_venue_fee_20_50km',
            'certificate_reissue_fee',
            'payment_deadline_days',
            'application_deadline_days',
            'wedding_days_allowed',
            'wedding_time_slots'
        ];

        $this->db->table('settings')
            ->whereIn('setting_key', $newSettings)
            ->delete();

        // Reactivate Nansana
        $this->db->table('campuses')
            ->where('name', 'Nansana Campus')
            ->update(['is_active' => 1]);

        // Remove new campuses
        $newCampuses = [
            'Kyengera Campus',
            'Entebbe Campus',
            'Gulu Campus',
            'Mbarara Campus',
            'Ssuubi Campus',
            'Biira Campus'
        ];

        $this->db->table('campuses')
            ->whereIn('name', $newCampuses)
            ->delete();
    }
}

