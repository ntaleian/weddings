<?php

namespace App\Models;

use CodeIgniter\Model;

class PastorModel extends Model
{
    protected $table = 'pastors';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name', 'email', 'phone', 'campus_id',
        'specialization', 'image_path', 'is_available'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'campus_id' => 'integer',
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
        'name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[pastors.email]',
        'phone' => 'required|min_length[10]|max_length[20]',
        'campus_id' => 'permit_empty|integer',
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'This email is already registered.',
        ],
    ];

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

    public function getActivePastors()
    {
        return $this->where('is_available', true)->findAll();
    }

    public function getPastorsByCampus($campusId)
    {
        return $this->where('campus_id', $campusId)
                    ->where('is_available', true)
                    ->findAll();
    }

    public function getPastorWithCampus($pastorId)
    {
        return $this->db->table('pastors')
                      ->select('pastors.*, campuses.name as campus_name, campuses.location as campus_location')
                      ->join('campuses', 'campuses.id = pastors.campus_id', 'left')
                      ->where('pastors.id', $pastorId)
                      ->get()
                      ->getRowArray();
    }

    public function getPastorsWithCampus()
    {
        return $this->db->table('pastors')
                      ->select('pastors.*, campuses.name as campus_name, campuses.location as campus_location')
                      ->join('campuses', 'campuses.id = pastors.campus_id', 'left')
                      ->orderBy('pastors.name', 'ASC')
                      ->get()
                      ->getResultArray();
    }

    public function getPastorBookings($pastorId, $limit = null)
    {
        $query = $this->db->table('bookings')
                         ->select('bookings.*, campuses.name as campus_name, users.first_name as user_first_name, users.last_name as user_last_name')
                         ->join('campuses', 'campuses.id = bookings.campus_id')
                         ->join('users', 'users.id = bookings.user_id')
                         ->where('bookings.pastor_id', $pastorId)
                         ->orderBy('bookings.wedding_date', 'ASC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get()->getResultArray();
    }

    public function getPastorStats($pastorId)
    {
        $stats = [];
        
        // Total bookings
        $stats['total_bookings'] = $this->db->table('bookings')
                                          ->where('pastor_id', $pastorId)
                                          ->countAllResults();
        
        // Upcoming bookings
        $stats['upcoming_bookings'] = $this->db->table('bookings')
                                             ->where('pastor_id', $pastorId)
                                             ->where('wedding_date >=', date('Y-m-d'))
                                             ->where('status', 'approved')
                                             ->countAllResults();
        
        // This month's bookings
        $stats['this_month'] = $this->db->table('bookings')
                                      ->where('pastor_id', $pastorId)
                                      ->where('MONTH(wedding_date)', date('m'))
                                      ->where('YEAR(wedding_date)', date('Y'))
                                      ->countAllResults();
        
        return $stats;
    }
    
    /**
     * Update pastor with proper email validation
     */
    public function updatePastor($id, $data)
    {
        // Temporarily set validation rule for email to exclude current pastor
        $originalRules = $this->validationRules;
        $this->validationRules['email'] = 'required|valid_email|is_unique[pastors.email,id,' . $id . ']';
        
        $result = $this->update($id, $data);
        
        // Restore original validation rules
        $this->validationRules = $originalRules;
        
        return $result;
    }
}
