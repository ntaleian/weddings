<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'booking_id',
        'sender_type',
        'sender_id',
        'content',
        'is_read'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'booking_id' => 'required|integer',
        'sender_type' => 'required|in_list[user,coordinator]',
        'sender_id' => 'required|integer',
        'content' => 'required|min_length[1]|max_length[1000]'
    ];

    protected $validationMessages = [
        'content' => [
            'required' => 'Message content is required',
            'min_length' => 'Message must be at least 1 character long',
            'max_length' => 'Message cannot exceed 1000 characters'
        ]
    ];

    /**
     * Get messages for a specific booking
     */
    public function getMessagesForBooking($bookingId)
    {
        return $this->where('booking_id', $bookingId)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get unread messages count for a booking
     */
    public function getUnreadCount($bookingId, $senderType = null)
    {
        $builder = $this->where('booking_id', $bookingId)
                        ->where('is_read', 0);
        
        if ($senderType) {
            $builder->where('sender_type !=', $senderType);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($bookingId, $excludeSenderType = null)
    {
        $builder = $this->where('booking_id', $bookingId);
        
        if ($excludeSenderType) {
            $builder->where('sender_type !=', $excludeSenderType);
        }
        
        return $builder->set('is_read', 1)->update();
    }

    /**
     * Send a new message
     */
    public function sendMessage($bookingId, $senderType, $senderId, $content)
    {
        $data = [
            'booking_id' => $bookingId,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'content' => trim($content),
            'is_read' => 0
        ];

        return $this->insert($data);
    }

    /**
     * Get latest messages for dashboard preview
     */
    public function getLatestMessagesForUser($userId, $limit = 5)
    {
        return $this->select('messages.*, bookings.bride_name, bookings.groom_name, campuses.name as campus_name')
                    ->join('bookings', 'bookings.id = messages.booking_id')
                    ->join('campuses', 'campuses.id = bookings.campus_id')
                    ->where('bookings.user_id', $userId)
                    ->orderBy('messages.created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get conversation summary for admin dashboard
     */
    public function getConversationSummary($bookingId)
    {
        $totalMessages = $this->where('booking_id', $bookingId)->countAllResults();
        $unreadFromUser = $this->where('booking_id', $bookingId)
                              ->where('sender_type', 'user')
                              ->where('is_read', 0)
                              ->countAllResults();
        
        $lastMessage = $this->where('booking_id', $bookingId)
                           ->orderBy('created_at', 'DESC')
                           ->first();

        return [
            'total_messages' => $totalMessages,
            'unread_from_user' => $unreadFromUser,
            'last_message' => $lastMessage
        ];
    }
}
