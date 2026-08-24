<?php
$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
if ($host === '' || ! str_contains($host, 'watotochurch.net')) {
    return;
}

$requestUri = (string) ($_SERVER['REQUEST_URI'] ?? '/weddings/public/');
$liveUrl = 'https://watotochurch.com' . $requestUri;
?>
<style>
.test-site-toast {
    position: fixed;
    top: 16px;
    right: 16px;
    z-index: 99999;
    max-width: min(420px, calc(100vw - 32px));
    background: #9b1c1c;
    color: #fff;
    border-radius: 12px;
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.28);
    padding: 16px 18px;
    font-family: 'Outfit', sans-serif;
    animation: test-site-toast-in 0.35s ease-out;
}
.test-site-toast__header {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 10px;
}
.test-site-toast__header i {
    margin-top: 2px;
    font-size: 1.15rem;
}
.test-site-toast__title {
    margin: 0;
    font-size: 1.05rem;
    font-weight: 700;
    line-height: 1.3;
}
.test-site-toast__body {
    margin: 0 0 14px;
    font-size: 0.92rem;
    line-height: 1.45;
    opacity: 0.95;
}
.test-site-toast__actions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.test-site-toast__btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: #9b1c1c;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
    border-radius: 8px;
    padding: 10px 14px;
    border: none;
    cursor: pointer;
}
.test-site-toast__btn:hover {
    background: #f3f4f6;
    color: #7f1d1d;
    text-decoration: none;
}
.test-site-toast__dismiss {
    background: transparent;
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.45);
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 0.85rem;
    cursor: pointer;
}
@keyframes test-site-toast-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
@media (max-width: 576px) {
    .test-site-toast {
        top: 12px;
        left: 12px;
        right: 12px;
        max-width: none;
    }
}
</style>
<div class="test-site-toast" id="testSiteToast" role="alert" aria-live="assertive">
    <div class="test-site-toast__header">
        <i class="fas fa-exclamation-triangle" aria-hidden="true"></i>
        <p class="test-site-toast__title">This is a test site</p>
    </div>
    <p class="test-site-toast__body">
        You are on the temporary <strong>.net</strong> environment.
        Please use the live wedding booking site on <strong>.com</strong>.
    </p>
    <div class="test-site-toast__actions">
        <a class="test-site-toast__btn" href="<?= esc($liveUrl) ?>">
            Go to live site
            <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </a>
        <button type="button" class="test-site-toast__dismiss" id="testSiteToastDismiss">Dismiss</button>
    </div>
</div>
<script>
(function () {
    var toast = document.getElementById('testSiteToast');
    var dismiss = document.getElementById('testSiteToastDismiss');
    if (!toast || !dismiss) return;
    dismiss.addEventListener('click', function () {
        toast.remove();
    });
})();
</script>
