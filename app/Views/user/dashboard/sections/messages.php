<style>
.messages-layout {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
    height: 600px;
}

.messages-main {
    background: var(--white);
    border-radius: 16px;
    padding: 0;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
}

.messages-header {
    padding: 20px;
    border-bottom: 2px solid var(--light-gray);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.messages-body {
    flex: 1;
    padding: 20px;
    overflow-y: auto;
}

.message {
    margin-bottom: 20px;
    display: flex;
    gap: 15px;
}

.message.sent {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-weight: 600;
    flex-shrink: 0;
}

.message-content {
    max-width: 70%;
}

.message-bubble {
    background: #f1f3f4;
    padding: 12px 16px;
    border-radius: 16px;
    margin-bottom: 5px;
}

.message.sent .message-bubble {
    background: var(--primary-color);
    color: var(--white);
}

.message-time {
    font-size: 0.8rem;
    color: var(--gray);
    text-align: right;
}

.message.sent .message-time {
    text-align: left;
}

.message-input {
    padding: 20px;
    border-top: 2px solid var(--light-gray);
}

.input-group {
    display: flex;
    gap: 10px;
}

.input-group input {
    flex: 1;
    padding: 12px 16px;
    border: 2px solid var(--light-gray);
    border-radius: 25px;
    outline: none;
}

.input-group input:focus {
    border-color: var(--primary-color);
}

.send-btn {
    width: 50px;
    height: 50px;
    border: none;
    background: var(--primary-color);
    color: var(--white);
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s ease;
}

.send-btn:hover {
    background: var(--secondary-color);
}

.messages-sidebar {
    background: var(--white);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.coordinator-info {
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--light-gray);
    margin-bottom: 20px;
}

.coordinator-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 2rem;
    margin: 0 auto 15px;
}

.quick-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.quick-action-btn {
    padding: 10px;
    border: 2px solid var(--light-gray);
    background: var(--white);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
}

.quick-action-btn:hover {
    border-color: var(--primary-color);
    background: rgba(100, 1, 127, 0.05);
}

.empty-messages {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
    color: var(--gray);
}

.empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(100, 1, 127, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.empty-icon i {
    font-size: 2rem;
    color: var(--primary-color);
}

.empty-messages h4 {
    margin-bottom: 10px;
    color: var(--dark-gray);
}

.empty-messages p {
    max-width: 300px;
    line-height: 1.5;
    margin: 0;
}

@media (max-width: 768px) {
    .messages-layout {
        grid-template-columns: 1fr;
        grid-template-rows: 1fr auto;
    }
    
    .messages-sidebar {
        order: -1;
        height: auto;
    }
}
</style>

<div class="section-header">
    <h1>Messages</h1>
    <p>Communication with your wedding coordinator</p>
</div>

<div class="messages-layout">
    <div class="messages-main">
        <div class="messages-header">
            <h3>Chat with <?= esc($coordinator['name'] ?? 'Wedding Coordinator') ?></h3>
            <span class="status-indicator">
                <i class="fas fa-circle" style="color: #2ecc71; font-size: 0.8rem;"></i>
                Online
            </span>
        </div>
        
        <div class="messages-body" id="messagesBody">
            <?php if (isset($messages) && !empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                <div class="message <?= $message['is_from_user'] ? 'sent' : '' ?>">
                    <div class="message-avatar">
                        <?= $message['is_from_user'] ? 'YOU' : 'SN' ?>
                    </div>
                    <div class="message-content">
                        <div class="message-bubble">
                            <?= esc($message['content']) ?>
                        </div>
                        <div class="message-time">
                            <?= date('M j, g:i A', strtotime($message['created_at'])) ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
            <!-- Empty state -->
            <div class="empty-messages">
                <div class="empty-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h4>No messages yet</h4>
                <p>Start a conversation with your wedding coordinator by sending a message or using one of the quick actions.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="message-input">
            <form id="messageForm" action="<?= site_url('dashboard/send-message') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="input-group">
                    <input type="text" name="message" placeholder="Type your message..." id="messageInput" required maxlength="1000">
                    <button type="submit" class="send-btn" id="sendBtn">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="messages-sidebar">
        <div class="coordinator-info">
            <div class="coordinator-avatar">
                <i class="fas fa-user"></i>
            </div>
            <h4><?= esc($coordinator['name'] ?? 'Wedding Coordinator') ?></h4>
            <p>Wedding Coordinator</p>
            <small>Usually responds within 2 hours</small>
        </div>
        
        <div class="quick-actions">
            <h5>Quick Actions</h5>
            <button class="quick-action-btn" onclick="sendQuickMessage('schedule')">
                <i class="fas fa-calendar"></i>
                Schedule Counseling
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('documents')">
                <i class="fas fa-file-alt"></i>
                Ask about Documents
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('venue')">
                <i class="fas fa-church"></i>
                Venue Questions
            </button>
            <button class="quick-action-btn" onclick="sendQuickMessage('payment')">
                <i class="fas fa-credit-card"></i>
                Payment Information
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const sendBtn = document.getElementById('sendBtn');
    const messagesBody = document.getElementById('messagesBody');

    // Handle form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Disable form while sending
        sendBtn.disabled = true;
        messageInput.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        // Send message via AJAX
        fetch('<?= site_url('dashboard/send-message') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                message: message,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to chat
                addMessageToChat(data.data);
                // Clear input
                messageInput.value = '';
                // Show success message
                showToast('Message sent successfully', 'success');
            } else {
                showToast(data.message || 'Failed to send message', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while sending the message', 'error');
        })
        .finally(() => {
            // Re-enable form
            sendBtn.disabled = false;
            messageInput.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
        });
    });

    // Handle quick messages
    window.sendQuickMessage = function(type) {
        // Send quick message via AJAX
        fetch('<?= site_url('dashboard/send-quick-message') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                type: type,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add message to chat
                addMessageToChat(data.data);
                // Show success message
                showToast('Message sent successfully', 'success');
            } else {
                showToast(data.message || 'Failed to send message', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while sending the message', 'error');
        });
    };

    // Add message to chat UI
    function addMessageToChat(messageData) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message sent';
        
        const now = new Date();
        const timeString = now.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });

        messageDiv.innerHTML = `
            <div class="message-avatar">YOU</div>
            <div class="message-content">
                <div class="message-bubble">
                    ${escapeHtml(messageData.content)}
                </div>
                <div class="message-time">
                    ${timeString}
                </div>
            </div>
        `;

        messagesBody.appendChild(messageDiv);
        messagesBody.scrollTop = messagesBody.scrollHeight;
    }

    // Escape HTML to prevent XSS
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Show toast notification
    function showToast(message, type = 'info') {
        // Simple toast implementation
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#2ecc71' : type === 'error' ? '#e74c3c' : '#3498db'};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            z-index: 10000;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: slideIn 0.3s ease;
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Auto-scroll to bottom on page load
    messagesBody.scrollTop = messagesBody.scrollHeight;

    // Allow Enter key to send message
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            messageForm.dispatchEvent(new Event('submit'));
        }
    });
});
</script>

<style>
@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}
</style>
