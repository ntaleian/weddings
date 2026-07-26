<?php
$weddingFee = (float) ($weddingFee ?? 600000);
$paymentAcknowledged = old(
    'payment_acknowledged',
    $application['payment_acknowledged'] ?? ''
);
?>
<style>
.form-section[data-step="5"] .payment-step-card {
    background: var(--white);
    border: 1px solid var(--light-gray);
    border-radius: 12px;
    margin-bottom: 20px;
    padding: 22px;
}

.form-section[data-step="5"] .payment-step-amount {
    color: var(--primary-color);
    font-size: 2rem;
    font-weight: 800;
    margin: 10px 0 4px;
}

.form-section[data-step="5"] .payment-step-card p {
    color: var(--gray);
    line-height: 1.5;
    margin: 0;
}

.form-section[data-step="5"] .payment-details-grid {
    display: grid;
    gap: 12px;
    grid-template-columns: repeat(auto-fit, minmax(min(210px, 100%), 1fr));
    margin-top: 18px;
}

.form-section[data-step="5"] .payment-detail {
    background: #f8f9fa;
    border: 1px solid #edf0f2;
    border-radius: 8px;
    padding: 14px;
}

.form-section[data-step="5"] .payment-detail span {
    color: #6c757d;
    display: block;
    font-size: 0.78rem;
    margin-bottom: 4px;
}

.form-section[data-step="5"] .payment-detail strong {
    color: var(--text-color);
}

.form-section[data-step="5"] .payment-note {
    background: #f8f9fa;
    border-left: 4px solid var(--primary-color);
    border-radius: 8px;
    color: var(--text-color);
    line-height: 1.5;
    padding: 16px;
}

.form-section[data-step="5"] .payment-acknowledgement {
    background: var(--white);
    border: 2px solid var(--light-gray);
    border-radius: 10px;
    margin-top: 18px;
    padding: 16px;
}

.form-section[data-step="5"] .payment-acknowledgement label {
    align-items: flex-start;
    color: var(--text-color);
    cursor: pointer;
    display: flex;
    gap: 10px;
    line-height: 1.45;
}

.form-section[data-step="5"] .payment-acknowledgement input {
    margin-top: 4px;
}
</style>

<!-- Step 5: Payment -->
<div class="form-section" data-step="5" style="display: none;">
    <div class="form-section-header">
        <h2>Payment information</h2>
        <p>Understand the wedding fee and how payment will be recorded after submission.</p>
    </div>

    <div class="payment-step-card">
        <h3 class="section-title">
            <i class="fas fa-credit-card"></i>
            Wedding fee
        </h3>
        <div class="payment-step-amount">UGX <?= number_format($weddingFee, 0) ?></div>
        <p>This fee is recorded against your submitted application. Pay the non-refundable deposit (UGX <?= number_format((float) ($depositAmount ?? 300000), 0) ?>) after submission so admin can verify it and hold your preferred date. Your preferred date is <strong>not confirmed</strong> until that deposit is verified.</p>

        <div class="payment-details-grid">
            <div class="payment-detail">
                <span>Bank</span>
                <strong>ABSA Bank</strong>
            </div>
            <div class="payment-detail">
                <span>Account name</span>
                <strong>Watoto Church Ministries</strong>
            </div>
            <div class="payment-detail">
                <span>Account number</span>
                <strong>0341192455</strong>
            </div>
            <div class="payment-detail">
                <span>Branch</span>
                <strong>Kampala Road</strong>
            </div>
        </div>
    </div>

    <div class="payment-note">
        Please pay at least the non-refundable deposit soon after submitting, then complete the remaining balance at least 2 months before your wedding date.
        Record each payment reference in your dashboard so the admin team can verify it. If you are a Watoto member, also upload your cell leader letter under Documents.
    </div>

    <div class="payment-acknowledgement">
        <label for="paymentAcknowledged">
            <input
                type="checkbox"
                id="paymentAcknowledged"
                name="payment_acknowledged"
                value="1"
                <?= (string) $paymentAcknowledged === '1' ? 'checked' : '' ?>
            >
            <span>I understand the fee, that my preferred date is held only after the non-refundable deposit is verified, and that payment details are recorded after this application is submitted.</span>
        </label>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentStep = document.querySelector('[data-step="5"]');
    if (!paymentStep) return;

    paymentStep.querySelectorAll('input, select, textarea').forEach(function(input) {
        input.addEventListener('change', function() {
            if (window.scheduleAutoSave) {
                window.scheduleAutoSave(5);
            }
        });
    });
});
</script>
