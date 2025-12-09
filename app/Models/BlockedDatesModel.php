<?php

namespace App\Models;

use CodeIgniter\Model;

class BlockedDatesModel extends Model
{
    protected $table = 'blocked_dates';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'campus_id',
        'blocked_date',
        'reason',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'campus_id' => 'required|integer|is_not_unique[campuses.id]',
        'blocked_date' => 'required|valid_date[Y-m-d]',
        'reason' => 'required|min_length[5]|max_length[500]'
    ];

    protected $validationMessages = [
        'campus_id' => [
            'required' => 'Please select a campus.',
            'integer' => 'Invalid campus selection.',
            'is_not_unique' => 'Selected campus does not exist.'
        ],
        'blocked_date' => [
            'required' => 'Please select a date to block.',
            'valid_date' => 'Please enter a valid date.'
        ],
        'reason' => [
            'required' => 'Please provide a reason for blocking this date.',
            'min_length' => 'Reason must be at least 5 characters long.',
            'max_length' => 'Reason cannot exceed 500 characters.'
        ]
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

    /**
     * Get all blocked dates with campus information
     */
    public function getBlockedDatesWithCampus($campusId = null)
    {
        $builder = $this->builder();
        $builder->select('blocked_dates.*, campuses.name as campus_name');
        $builder->join('campuses', 'campuses.id = blocked_dates.campus_id');
        
        if ($campusId) {
            $builder->where('blocked_dates.campus_id', $campusId);
        }
        
        $builder->orderBy('blocked_dates.blocked_date', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Check if a specific date is blocked for a campus
     */
    public function isDateBlocked($campusId, $date)
    {
        return $this->where('campus_id', $campusId)
                   ->where('blocked_date', $date)
                   ->first() !== null;
    }

    /**
     * Get future blocked dates for a campus
     */
    public function getFutureBlockedDates($campusId = null)
    {
        $builder = $this->builder();
        $builder->select('blocked_dates.*, campuses.name as campus_name');
        $builder->join('campuses', 'campuses.id = blocked_dates.campus_id');
        $builder->where('blocked_dates.blocked_date >=', date('Y-m-d'));
        
        if ($campusId) {
            $builder->where('blocked_dates.campus_id', $campusId);
        }
        
        $builder->orderBy('blocked_dates.blocked_date', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get blocked dates for a specific date range
     */
    public function getBlockedDatesInRange($startDate, $endDate, $campusId = null)
    {
        $builder = $this->builder();
        $builder->select('blocked_dates.*, campuses.name as campus_name');
        $builder->join('campuses', 'campuses.id = blocked_dates.campus_id');
        $builder->where('blocked_dates.blocked_date >=', $startDate);
        $builder->where('blocked_dates.blocked_date <=', $endDate);
        
        if ($campusId) {
            $builder->where('blocked_dates.campus_id', $campusId);
        }
        
        $builder->orderBy('blocked_dates.blocked_date', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Delete past blocked dates (cleanup method)
     */
    public function deletePastBlockedDates()
    {
        return $this->where('blocked_date <', date('Y-m-d'))->delete();
    }

    /**
     * Get blocked dates count by campus
     */
    public function getBlockedDatesCountByCampus()
    {
        $builder = $this->builder();
        $builder->select('campuses.name as campus_name, COUNT(blocked_dates.id) as blocked_count');
        $builder->join('campuses', 'campuses.id = blocked_dates.campus_id', 'right');
        $builder->where('blocked_dates.blocked_date >=', date('Y-m-d'));
        $builder->groupBy('campuses.id, campuses.name');
        $builder->orderBy('campuses.name', 'ASC');
        
        return $builder->get()->getResultArray();
    }
}
