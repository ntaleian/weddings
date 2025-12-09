<style>
.timeline-container {
    background: var(--white);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.timeline {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    left: 30px;
    height: 100%;
    width: 4px;
    background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    margin-bottom: 40px;
    padding-left: 80px;
}

.timeline-marker {
    position: absolute;
    left: 18px;
    top: 10px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-size: 0.8rem;
    font-weight: 600;
    z-index: 2;
}

.timeline-marker.completed {
    background: var(--success-color);
}

.timeline-marker.current {
    background: var(--primary-color);
    box-shadow: 0 0 20px rgba(100, 1, 127, 0.4);
}

.timeline-marker.pending {
    background: var(--gray);
}

.timeline-marker.overdue {
    background: var(--danger-color);
}

.timeline-content {
    background: var(--white);
    border: 2px solid var(--light-gray);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.timeline-item.completed .timeline-content {
    border-color: var(--success-color);
    background: rgba(46, 204, 113, 0.05);
}

.timeline-item.current .timeline-content {
    border-color: var(--primary-color);
    background: rgba(100, 1, 127, 0.05);
    box-shadow: 0 4px 15px rgba(100, 1, 127, 0.1);
}

.timeline-item.overdue .timeline-content {
    border-color: var(--danger-color);
    background: rgba(231, 76, 60, 0.05);
}

.timeline-header {
    display: flex;
    justify-content: between;
    align-items: flex-start;
    margin-bottom: 15px;
    flex-wrap: wrap;
    gap: 10px;
}

.timeline-title {
    margin: 0;
    color: var(--dark-gray);
    font-size: 1.2rem;
    flex: 1;
}

.timeline-date {
    background: var(--light-gray);
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    color: var(--gray);
    white-space: nowrap;
}

.timeline-item.completed .timeline-date {
    background: var(--success-color);
    color: var(--white);
}

.timeline-item.current .timeline-date {
    background: var(--primary-color);
    color: var(--white);
}

.timeline-item.overdue .timeline-date {
    background: var(--danger-color);
    color: var(--white);
}

.timeline-description {
    color: var(--gray);
    margin-bottom: 15px;
    line-height: 1.6;
}

.timeline-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.timeline-btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.timeline-btn.primary {
    background: var(--primary-color);
    color: var(--white);
}

.timeline-btn.primary:hover {
    background: var(--secondary-color);
}

.timeline-btn.secondary {
    background: var(--light-gray);
    color: var(--gray);
}

.timeline-btn.secondary:hover {
    background: var(--gray);
    color: var(--white);
}

.timeline-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 10px;
}

.status-icon {
    width: 16px;
    height: 16px;
    border-radius: 50%;
}

.status-completed { color: var(--success-color); }
.status-current { color: var(--primary-color); }
.status-pending { color: var(--gray); }
.status-overdue { color: var(--danger-color); }

.timeline-summary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: var(--white);
    border-radius: 16px;
    padding: 25px;
    margin-bottom: 30px;
    text-align: center;
}

.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.stat-item {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    padding: 15px;
    backdrop-filter: blur(10px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.progress-tips {
    margin-top: 20px;
}

.tip-box {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 15px;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
}

.tip-box i {
    color: #FFD700;
    font-size: 1.1rem;
}

.timeline-details {
    background: rgba(0, 0, 0, 0.02);
    border-radius: 8px;
    padding: 15px;
    margin: 15px 0;
    border-left: 4px solid var(--primary-color);
}

.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.detail-item:last-child {
    margin-bottom: 0;
}

.detail-item strong {
    color: var(--primary-color);
    min-width: 100px;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
}

.detail-item strong i {
    font-size: 0.8rem;
}

.requirement-list {
    margin: 0;
    padding-left: 20px;
    color: var(--gray);
}

.requirement-list li {
    margin-bottom: 5px;
}

.timeline-item.completed .timeline-details {
    border-left-color: var(--success-color);
}

.timeline-item.current .timeline-details {
    border-left-color: var(--primary-color);
    background: rgba(100, 1, 127, 0.03);
}

.timeline-item.overdue .timeline-details {
    border-left-color: var(--danger-color);
}

.timeline-info-section {
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid var(--light-gray);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.info-card {
    background: var(--white);
    border: 2px solid var(--light-gray);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.info-card:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(100, 1, 127, 0.1);
}

.info-header {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--light-gray);
}

.info-header i {
    color: var(--primary-color);
    font-size: 1.2rem;
    margin-top: 2px;
}

.info-header h4 {
    margin: 0;
    color: var(--dark-gray);
    font-size: 1.1rem;
    line-height: 1.2;
}

.contact-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 15px;
}

.contact-link {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.9rem;
    padding: 8px 0;
    transition: color 0.3s ease;
}

.contact-link:hover {
    color: var(--secondary-color);
}

.deadline-list,
.tips-list {
    margin: 15px 0 0 0;
    padding-left: 0;
    list-style: none;
}

.deadline-list li,
.tips-list li {
    padding: 8px 0;
    border-bottom: 1px solid var(--light-gray);
    font-size: 0.9rem;
    color: var(--gray);
}

.deadline-list li:last-child,
.tips-list li:last-child {
    border-bottom: none;
}

.deadline-list li strong {
    color: var(--primary-color);
    min-width: 100px;
    display: inline-block;
}

.tips-list li {
    position: relative;
    padding-left: 20px;
}

.tips-list li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: var(--success-color);
    font-weight: bold;
}

@media (max-width: 768px) {
    .timeline::before {
        left: 15px;
    }
    
    .timeline-item {
        padding-left: 50px;
    }
    
    .timeline-marker {
        left: 3px;
    }
    
    .timeline-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .timeline-actions {
        width: 100%;
    }
    
    .timeline-btn {
        flex: 1;
        justify-content: center;
    }
}
</style>

<div class="section-header">
    <h1>Wedding Timeline</h1>
    <p>Track your progress and upcoming milestones</p>
</div>

<div class="timeline-container">
    <div class="timeline-summary">
        <h3>Your Wedding Journey Progress</h3>
        <p>You're making great progress! Follow this step-by-step guide to ensure your wedding preparation is complete and stress-free. Each milestone brings you closer to your special day.</p>
        
        <div class="summary-stats">
            <div class="stat-item">
                <div class="stat-number"><?= $stats['completed'] ?? '2' ?></div>
                <div class="stat-label">Completed Steps</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['remaining'] ?? '6' ?></div>
                <div class="stat-label">Remaining Steps</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['days_to_wedding'] ?? '120' ?></div>
                <div class="stat-label">Days to Wedding</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?= $stats['progress_percentage'] ?? '25' ?>%</div>
                <div class="stat-label">Journey Complete</div>
            </div>
        </div>
        
        <div class="progress-tips">
            <div class="tip-box">
                <i class="fas fa-lightbulb"></i>
                <strong>Pro Tip:</strong> Start your application early to avoid any last-minute stress. Most couples complete the process 3-4 months before their wedding date.
            </div>
        </div>
    </div>

    <div class="timeline">
        <?php 
        $timelineItems = $timeline ?? [
            [
                'id' => 1,
                'title' => 'Initial Registration',
                'description' => 'You successfully created your account and gained access to our wedding application system. Your profile has been verified and you can now track your wedding preparation progress.',
                'date' => 'Completed - ' . date('M j, Y', strtotime('-2 days')),
                'status' => 'completed',
                'actions' => [],
                'details' => 'Account verified on ' . date('M j, Y g:i A', strtotime('-2 days')) . '. Email confirmation received.',
                'duration' => 'Completed in 5 minutes',
                'progress' => 'Your secure account allows you to save progress and receive updates throughout the process.'
            ],
            [
                'id' => 2,
                'title' => 'Campus & Date Selection',
                'description' => 'You have selected your preferred campus venue and wedding date. Your chosen date and time slot have been reserved in our booking system and are now secured for your ceremony.',
                'date' => 'Completed - ' . date('M j, Y', strtotime('-1 day')),
                'status' => 'completed',
                'actions' => [
                    ['label' => 'View Your Selection', 'type' => 'secondary', 'action' => 'viewSelection']
                ],
                'details' => 'Reserved on ' . date('M j, Y g:i A', strtotime('-1 day')) . '. Booking reference: #WED-' . date('Y') . '-001',
                'duration' => 'Completed in 15 minutes',
                'progress' => 'Your wedding date is locked in our calendar. No other bookings can be made for your selected time slot.'
            ],
            [
                'id' => 3,
                'title' => 'Complete Application Form',
                'description' => 'You are currently filling out the comprehensive wedding application. This includes your personal details, family information, witness details, and ceremony preferences.',
                'date' => 'In Progress - Due ' . date('M j, Y', strtotime('+5 days')),
                'status' => 'current',
                'actions' => [
                    ['label' => 'Continue Application', 'type' => 'primary', 'action' => 'continueApp'],
                    ['label' => 'Save & Resume Later', 'type' => 'secondary', 'action' => 'saveProgress']
                ],
                'details' => 'Started on ' . date('M j, Y', strtotime('-1 day')) . '. Progress automatically saved every 30 seconds.',
                'duration' => 'Estimated 30-45 minutes total',
                'progress' => 'You have completed approximately 40% of the application form. Personal details section is done.'
            ],
            [
                'id' => 4,
                'title' => 'Document Submission',
                'description' => 'Once your application is complete, you will upload all required legal documents. These will be reviewed by our administrative team to verify your eligibility for marriage.',
                'date' => 'Pending - Due ' . date('M j, Y', strtotime('+10 days')),
                'status' => 'pending',
                'actions' => [
                    ['label' => 'View Document Requirements', 'type' => 'secondary', 'action' => 'viewDocs']
                ],
                'details' => 'Will be available after application completion. Upload area will be unlocked automatically.',
                'duration' => 'Estimated 20 minutes for uploads',
                'progress' => 'This step will become active once your application form is submitted and reviewed.'
            ],
            [
                'id' => 5,
                'title' => 'Pastor Interview Session',
                'description' => 'After document approval, you will be contacted to schedule your interview with the assigned pastor. This personal meeting helps establish your relationship with the officiating minister.',
                'date' => 'Pending - ' . date('M j, Y', strtotime('+20 days')),
                'status' => 'pending',
                'actions' => [
                    ['label' => 'Learn About Interview', 'type' => 'secondary', 'action' => 'scheduleInterview']
                ],
                'details' => 'Pastor assignment will be made based on your campus selection and availability.',
                'duration' => 'Scheduled for 60-90 minutes',
                'progress' => 'You will receive an email with available time slots once your documents are approved.'
            ],
            [
                'id' => 6,
                'title' => 'Pre-Marital Counseling Program',
                'description' => 'Following your pastor interview, you will begin the required pre-marital counseling sessions. These sessions are designed to prepare you for a strong marriage foundation.',
                'date' => 'Pending - Starts ' . date('M j, Y', strtotime('+30 days')),
                'status' => 'pending',
                'actions' => [
                    ['label' => 'View Counseling Schedule', 'type' => 'secondary', 'action' => 'learnCounseling']
                ],
                'details' => 'Sessions will be scheduled after your pastor interview is completed.',
                'duration' => '4-6 weeks (minimum 4 sessions)',
                'progress' => 'Flexible scheduling available - sessions can be weekly or bi-weekly based on your preference.'
            ],
            [
                'id' => 7,
                'title' => 'Administrative Review & Final Approval',
                'description' => 'Our team will conduct a comprehensive review of your completed application, approved documents, and finished counseling program before granting final approval.',
                'date' => 'Pending - ' . date('M j, Y', strtotime('+45 days')),
                'status' => 'pending',
                'actions' => [],
                'details' => 'Review begins automatically when all previous requirements are met.',
                'duration' => 'Review takes 3-5 business days',
                'progress' => 'You will receive email notification once this stage begins and when approval is granted.'
            ],
            [
                'id' => 8,
                'title' => 'Your Wedding Day Celebration',
                'description' => 'Your special day has arrived! All preparations are complete, and you are ready to celebrate your marriage ceremony with family and friends.',
                'date' => isset($wedding_date) ? date('l, F j, Y', strtotime($wedding_date)) : date('l, F j, Y', strtotime('+120 days')),
                'status' => 'pending',
                'actions' => [],
                'details' => 'Final confirmation and ceremony details will be sent 7 days before your wedding.',
                'duration' => 'Full day celebration',
                'progress' => 'Ceremony rehearsal will be scheduled 1-2 days before your wedding date.'
            ]
        ];
        ?>

        <?php foreach ($timelineItems as $item): ?>
        <div class="timeline-item <?= $item['status'] ?>">
            <div class="timeline-marker <?= $item['status'] ?>">
                <?php if ($item['status'] == 'completed'): ?>
                    <i class="fas fa-check"></i>
                <?php elseif ($item['status'] == 'current'): ?>
                    <?= $item['id'] ?>
                <?php elseif ($item['status'] == 'overdue'): ?>
                    <i class="fas fa-exclamation"></i>
                <?php else: ?>
                    <?= $item['id'] ?>
                <?php endif; ?>
            </div>
            
            <div class="timeline-content">
                <div class="timeline-header">
                    <h4 class="timeline-title"><?= esc($item['title']) ?></h4>
                    <span class="timeline-date"><?= esc($item['date']) ?></span>
                </div>
                
                <p class="timeline-description"><?= esc($item['description']) ?></p>
                
                <?php if (!empty($item['details'])): ?>
                <div class="timeline-details">
                    <div class="detail-item">
                        <strong><i class="fas fa-info-circle"></i> Status:</strong>
                        <span><?= esc($item['details']) ?></span>
                    </div>
                    <div class="detail-item">
                        <strong><i class="fas fa-clock"></i> Time:</strong>
                        <span><?= esc($item['duration']) ?></span>
                    </div>
                    <?php if (!empty($item['progress'])): ?>
                    <div class="detail-item">
                        <strong><i class="fas fa-chart-line"></i> Progress:</strong>
                        <span><?= esc($item['progress']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($item['actions'])): ?>
                <div class="timeline-actions">
                    <?php foreach ($item['actions'] as $action): ?>
                    <button class="timeline-btn <?= $action['type'] ?>" onclick="handleTimelineAction('<?= $action['action'] ?>', <?= $item['id'] ?>)">
                        <i class="fas fa-<?= $action['type'] == 'primary' ? 'arrow-right' : 'eye' ?>"></i>
                        <?= esc($action['label']) ?>
                    </button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <div class="timeline-status">
                    <span class="status-icon status-<?= $item['status'] ?>">
                        <i class="fas fa-circle"></i>
                    </span>
                    <span class="status-<?= $item['status'] ?>">
                        <?= ucfirst($item['status']) ?>
                        <?php if ($item['status'] == 'current'): ?>
                            - In Progress
                        <?php elseif ($item['status'] == 'completed'): ?>
                            - Done
                        <?php elseif ($item['status'] == 'overdue'): ?>
                            - Overdue
                        <?php else: ?>
                            - Upcoming
                        <?php endif; ?>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
</div>
