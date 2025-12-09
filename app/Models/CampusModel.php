<?php

namespace App\Models;

use CodeIgniter\Model;

class CampusModel extends Model
{
    protected $table = 'campuses';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'location', 'address', 'capacity', 'description', 
        'facilities', 'image_path', 'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'location' => 'required|min_length[3]|max_length[255]',
        'address' => 'required',
        'capacity' => 'required|integer|greater_than[0]',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public function getActiveCampuses()
    {
        return $this->where('is_active', true)->findAll();
    }

    public function getCampusWithBookings($campusId)
    {
        return $this->db->table('campuses')
                      ->select('campuses.*, COUNT(bookings.id) as total_bookings')
                      ->join('bookings', 'bookings.campus_id = campuses.id', 'left')
                      ->where('campuses.id', $campusId)
                      ->groupBy('campuses.id')
                      ->get()
                      ->getRowArray();
    }

    public function getCampusAvailability($campusId, $date)
    {
        // Check if date is blocked
        $blockedDate = $this->db->table('blocked_dates')
                              ->where('date', $date)
                              ->where('(campus_id IS NULL OR campus_id = ' . $campusId . ')')
                              ->where('is_active', true)
                              ->get()
                              ->getRowArray();

        if ($blockedDate) {
            return false;
        }

        // Check if date is already booked
        $booking = $this->db->table('bookings')
                          ->where('campus_id', $campusId)
                          ->where('wedding_date', $date)
                          ->whereIn('status', ['pending', 'approved'])
                          ->get()
                          ->getRowArray();

        return !$booking;
    }

    public function getCampusBookings($campusId, $limit = null)
    {
        $query = $this->db->table('bookings')
                         ->select('bookings.*, users.first_name, users.last_name, users.email')
                         ->join('users', 'users.id = bookings.user_id')
                         ->where('bookings.campus_id', $campusId)
                         ->orderBy('bookings.wedding_date', 'ASC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->getResultArray();
    }
}
