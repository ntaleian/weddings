<div class="payment-container">
    <!-- Payment Overview -->
    <div class="payment-card">
        <div class="payment-header">
            <h2><i class="fas fa-money-bill-wave"></i> Wedding Fees</h2>
            <p>Complete your payment to confirm your wedding booking</p>
        </div>

        <!-- Payment Amount -->
        <div class="payment-amount">
            UGX <?= number_format($weddingFee) ?>
        </div>

        <!-- Payment Status -->
        <div class="text-center">
            <?php 
            $totalWithPending = $totalPaid + ($pendingAmount ?? 0);
            if ($totalPaid >= $weddingFee): ?>
                <span class="payment-status status-paid">
                    <i class="fas fa-check-circle"></i>
                    Payment Complete
                </span>
            <?php elseif ($totalPaid > 0 || ($pendingAmount ?? 0) > 0): ?>
                <span class="payment-status status-pending">
                    <i class="fas fa-clock"></i>
                    Partial Payment (UGX <?= number_format($totalPaid) ?> paid<?= ($pendingAmount ?? 0) > 0 ? ', UGX ' . number_format($pendingAmount) . ' pending' : '' ?>)
                </span>
            <?php else: ?>
                <span class="payment-status status-unpaid">
                    <i class="fas fa-exclamation-triangle"></i>
                    Payment Required
                </span>
            <?php endif; ?>
        </div>

        <?php if ($totalWithPending < $weddingFee): ?>
            <!-- Two Column Layout for Payment Instructions and Form -->
            <div class="row g-4">
                <!-- Left Column: Payment Instructions -->
                <div class="col-xl-6 col-lg-12">
                    <div class="payment-instructions">
                        <h3><i class="fas fa-university"></i> How to Pay</h3>
                        <p>Please make your payment at least 2 months before your wedding date using the bank details below:</p>
                        
                        <div class="bank-details">
                            <div class="bank-detail-item">
                                <span class="bank-detail-label">Bank</span>
                                <span class="bank-detail-value">ABSA Bank</span>
                            </div>
                            <div class="bank-detail-item">
                                <span class="bank-detail-label">Account Name</span>
                                <span class="bank-detail-value">Watoto Church Ministries</span>
                            </div>
                            <div class="bank-detail-item">
                                <span class="bank-detail-label">Account Number</span>
                                <span class="bank-detail-value">0341192455</span>
                            </div>
                            <div class="bank-detail-item">
                                <span class="bank-detail-label">Branch</span>
                                <span class="bank-detail-value">Kampala Road</span>
                            </div>
                            <div class="bank-detail-item">
                                <span class="bank-detail-label">Amount Due</span>
                                <span class="bank-detail-value">UGX <?= number_format($remainingBalance ?? ($weddingFee - $totalPaid - ($pendingAmount ?? 0))) ?></span>
                            </div>
                        </div>
                        
                        <div class="alert alert-info flash-alert">
                            <div class="alert-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="alert-content">
                                After paying, use the form to record your payment details for our records.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Payment Form -->
                <div class="col-xl-6 col-lg-12">
                    <div class="payment-form">
                        <h3><i class="fas fa-receipt"></i> Record Payment</h3>
                        <form action="<?= site_url('dashboard/payment/add') ?>" method="POST">
                            <?= csrf_field() ?>
                            
                            <div class="form-group">
                                <label for="amount">Amount Paid (UGX)</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="amount" 
                                       name="amount" 
                                       min="1000" 
                                       max="<?= $remainingBalance ?? ($weddingFee - $totalPaid - ($pendingAmount ?? 0)) ?>"
                                       value="<?= $remainingBalance ?? ($weddingFee - $totalPaid - ($pendingAmount ?? 0)) ?>"
                                       readonly
                                       required
                                       title="This amount is automatically calculated based on your remaining balance">
                                <small class="text-muted" style="font-size: 0.8rem; display: block; margin-top: 4px;">
                                    <i class="fas fa-info-circle"></i> This is the exact amount you need to pay (remaining balance)
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_reference">Transaction Reference</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="payment_reference" 
                                       name="payment_reference" 
                                       placeholder="e.g., TXN123456789 or receipt number"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_date">Payment Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       id="payment_date" 
                                       name="payment_date" 
                                       max="<?= date('Y-m-d') ?>"
                                       required>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes (Optional)</label>
                                <textarea class="form-control" 
                                          id="notes" 
                                          name="notes" 
                                          rows="2" 
                                          placeholder="Any additional details about this payment"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-save"></i>
                                Submit Payment Record
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-success flash-alert">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <strong>Payment Complete!</strong> Thank you for completing your wedding payment.
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Payment History -->
    <?php if (!empty($payments)): ?>
        <div class="payment-card">
            <div class="payment-history">
                <h3><i class="fas fa-history"></i> Payment History</h3>
                
                <?php foreach ($payments as $payment): ?>
                    <div class="payment-item">
                        <div class="payment-info">
                            <h4>Payment #<?= $payment['id'] ?></h4>
                            <div class="payment-meta">
                                <span><i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($payment['payment_date'])) ?></span>
                                <span style="margin-left: 15px;"><i class="fas fa-receipt"></i> <?= esc($payment['transaction_reference']) ?></span>
                                <?php if (!empty($payment['notes'])): ?>
                                    <br><small><?= esc($payment['notes']) ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="payment-item-amount">
                            UGX <?= number_format($payment['amount']) ?>
                            <br>
                            <small class="payment-status status-<?= $payment['status'] ?>" style="font-size: 0.8rem; padding: 4px 8px;">
                                <?= ucfirst($payment['status']) ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
