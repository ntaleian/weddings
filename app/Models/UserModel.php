<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'first_name', 'last_name', 'email', 'phone', 'password', 
        'role', 'is_active', 'is_email_verified', 'email_verified_at',
        'email_notifications', 'sms_notifications', 'marketing_emails', 
        'profile_visibility', 'password_changed_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    // protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'first_name' => 'required|min_length[2]|max_length[100]',
        'last_name' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        // 'phone' => 'required|min_length[10]|max_length[20]',
        'password' => 'required|min_length[8]',
        'role' => 'required|in_list[user,admin]',
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
    protected $beforeInsert = ['hashPassword'];
    protected $afterInsert = [];
    protected $beforeUpdate = ['hashPassword'];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserBookings($userId)
    {
        return $this->db->table('bookings')
                      ->select('bookings.*, campuses.name as campus_name, pastors.name as pastor_name')
                      ->join('campuses', 'campuses.id = bookings.campus_id')
                      ->join('pastors', 'pastors.id = bookings.pastor_id', 'left')
                      ->where('bookings.user_id', $userId)
                      ->orderBy('bookings.created_at', 'DESC')
                      ->get()
                      ->getResultArray();
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }
}
