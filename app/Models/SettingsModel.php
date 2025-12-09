<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'setting_key', 'setting_value', 'setting_type', 'description', 
        'category', 'is_active'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'is_active' => 'boolean',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'setting_key' => 'required|alpha_dash|max_length[100]',
        'setting_value' => 'required',
        'setting_type' => 'required|in_list[string,number,boolean,json]',
        'category' => 'required|alpha_dash|max_length[50]',
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

    /**
     * Get a setting value by key
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->where('setting_key', $key)
                       ->where('is_active', true)
                       ->first();

        if (!$setting) {
            return $default;
        }

        return $this->castSettingValue($setting['setting_value'], $setting['setting_type']);
    }

    /**
     * Set a setting value
     */
    public function setSetting($key, $value, $type = 'string', $description = '', $category = 'general')
    {
        $existing = $this->where('setting_key', $key)->first();

        $data = [
            'setting_key' => $key,
            'setting_value' => $this->prepareSettingValue($value, $type),
            'setting_type' => $type,
            'description' => $description,
            'category' => $category,
            'is_active' => true
        ];

        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Get all settings by category
     */
    public function getSettingsByCategory($category)
    {
        $settings = $this->where('category', $category)
                        ->where('is_active', true)
                        ->findAll();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->castSettingValue(
                $setting['setting_value'], 
                $setting['setting_type']
            );
        }

        return $result;
    }

    /**
     * Get all wedding fee settings
     */
    public function getWeddingFees()
    {
        return $this->getSettingsByCategory('wedding_fees');
    }

    /**
     * Update wedding fees
     */
    public function updateWeddingFees($fees)
    {
        $success = true;

        foreach ($fees as $key => $value) {
            $result = $this->setSetting(
                $key, 
                $value, 
                'number', 
                $this->getWeddingFeeDescription($key),
                'wedding_fees'
            );
            
            if (!$result) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Get all categories with their settings
     */
    public function getAllSettingsByCategory()
    {
        $settings = $this->where('is_active', true)
                        ->orderBy('category')
                        ->orderBy('setting_key')
                        ->findAll();

        $result = [];
        foreach ($settings as $setting) {
            $category = $setting['category'];
            if (!isset($result[$category])) {
                $result[$category] = [];
            }
            
            $result[$category][] = [
                'key' => $setting['setting_key'],
                'value' => $this->castSettingValue($setting['setting_value'], $setting['setting_type']),
                'type' => $setting['setting_type'],
                'description' => $setting['description'],
                'raw_value' => $setting['setting_value']
            ];
        }

        return $result;
    }

    /**
     * Cast setting value to appropriate type
     */
    private function castSettingValue($value, $type)
    {
        switch ($type) {
            case 'number':
                return is_numeric($value) ? (float)$value : 0;
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'json':
                return json_decode($value, true) ?: [];
            default:
                return $value;
        }
    }

    /**
     * Prepare setting value for storage
     */
    private function prepareSettingValue($value, $type)
    {
        switch ($type) {
            case 'json':
                return json_encode($value);
            case 'boolean':
                return $value ? '1' : '0';
            default:
                return (string)$value;
        }
    }

    /**
     * Get description for wedding fee settings
     */
    private function getWeddingFeeDescription($key)
    {
        $descriptions = [
            'base_wedding_fee' => 'Base fee for all weddings',
            'pastor_fee' => 'Fee for pastor services',
            'decoration_fee' => 'Fee for decoration services',
            'photography_fee' => 'Fee for photography services',
            'catering_fee_per_guest' => 'Fee per guest for catering',
            'sound_system_fee' => 'Fee for sound system',
            'security_fee' => 'Fee for security services',
            'cleaning_fee' => 'Fee for cleaning services',
            'overtime_fee_per_hour' => 'Fee per hour for overtime',
        ];

        return $descriptions[$key] ?? '';
    }

    /**
     * Initialize default wedding fee settings
     */
    public function initializeWeddingFees()
    {
        $defaultFees = [
            'base_wedding_fee' => 500000,
            'pastor_fee' => 200000,
            'decoration_fee' => 300000,
            'photography_fee' => 400000,
            'catering_fee_per_guest' => 25000,
            'sound_system_fee' => 150000,
            'security_fee' => 100000,
            'cleaning_fee' => 50000,
            'overtime_fee_per_hour' => 50000,
        ];

        return $this->updateWeddingFees($defaultFees);
    }
}
