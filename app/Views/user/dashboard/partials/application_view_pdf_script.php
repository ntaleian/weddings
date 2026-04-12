<?php
/**
 * jsPDF generator for user submitted-application view (aligned with admin booking PDF content).
 *
 * @var array $booking
 * @var list<array> $payments
 * @var list<array> $uploadedDocuments
 * @var array<string, mixed> $paymentSummary
 */
helper(['residential_address', 'marital_status']);

$payments = $payments ?? [];
$uploadedDocuments = $uploadedDocuments ?? [];
$paymentSummary = $paymentSummary ?? [
    'total_required' => (float) ($booking['total_cost'] ?? 600000),
    'total_paid' => 0.0,
    'pending_amount' => 0.0,
    'remaining_balance' => (float) ($booking['total_cost'] ?? 600000),
];

$totalRequired = (float) ($paymentSummary['total_required'] ?? $booking['total_cost'] ?? 600000);
$totalPaid = (float) ($paymentSummary['total_paid'] ?? 0);
$pendingAmount = (float) ($paymentSummary['pending_amount'] ?? 0);
$remainingBalance = (float) ($paymentSummary['remaining_balance'] ?? max(0, $totalRequired - $totalPaid - $pendingAmount));

$weddingDateFormatted = ! empty($booking['wedding_date'])
    ? date('l, F j, Y', strtotime($booking['wedding_date']))
    : 'Not scheduled';
$weddingTimeFormatted = ! empty($booking['wedding_time'])
    ? date('g:i A', strtotime($booking['wedding_time']))
    : 'Not scheduled';

$brideStatus = $booking['bride_marital_status'] ?? '';
$brideStatusLabels = [
    'spinster' => 'Spinster',
    'divorced-separated' => 'Divorced/Separated',
    'married-traditionally' => 'Married Traditionally',
    'widowed' => 'Widowed',
    'civil-marriage' => 'Civil Marriage',
    'cohabiting' => 'Cohabiting',
];
$brideMaritalPdf = $brideStatusLabels[$brideStatus] ?? (trim((string) $brideStatus) !== '' ? ucfirst(str_replace('-', ' ', $brideStatus)) : 'Not provided');

$groomStatus = $booking['groom_marital_status'] ?? '';
$groomStatusLabels = [
    'bachelor' => 'Bachelor',
    'divorced-separated' => 'Divorced/Separated',
    'married-traditionally' => 'Married Traditionally',
    'widowed' => 'Widowed',
    'civil-marriage' => 'Civil Marriage',
    'cohabiting' => 'Cohabiting',
];
$groomMaritalPdf = $groomStatusLabels[$groomStatus] ?? (trim((string) $groomStatus) !== '' ? ucfirst(str_replace('-', ' ', $groomStatus)) : 'Not provided');

$bc = $booking['bride_church_member'] ?? '';
$pdfBrideChurch = 'Not specified';
if ($bc === 'yes') {
    $pdfBrideChurch = 'Yes - Watoto Church';
} elseif ($bc === 'other') {
    $pdfBrideChurch = 'Yes - Other Church';
} elseif ($bc === 'no') {
    $pdfBrideChurch = 'No';
}

$gc = $booking['groom_church_member'] ?? '';
$pdfGroomChurch = 'Not specified';
if ($gc === 'yes') {
    $pdfGroomChurch = 'Yes - Watoto Church';
} elseif ($gc === 'other') {
    $pdfGroomChurch = 'Yes - Other Church';
} elseif ($gc === 'no') {
    $pdfGroomChurch = 'No';
}

$w1marital = $booking['witness1_marital_status'] ?? $booking['witness1_relationship'] ?? null;
$w2marital = $booking['witness2_marital_status'] ?? $booking['witness2_relationship'] ?? null;
?>
<script>
function downloadApplicationPdf() {
    if (window.jspdf && window.jspdf.jsPDF) {
        generateApplicationPdf();
        return;
    }
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js';
    script.onload = function() {
        generateApplicationPdf();
    };
    document.head.appendChild(script);
}

function generateApplicationPdf() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    const brand = [0, 140, 21];

    function checkNewPage(requiredSpace) {
        if (yPos + requiredSpace > 270) {
            doc.addPage();
            yPos = 20;
            return true;
        }
        return false;
    }

    function addSectionHeader(title, y) {
        doc.setTextColor(brand[0], brand[1], brand[2]);
        doc.setFontSize(13);
        doc.setFont(undefined, 'bold');
        doc.text(title, 20, y);
        doc.setLineWidth(0.8);
        doc.setDrawColor(brand[0], brand[1], brand[2]);
        doc.line(20, y + 2, 190, y + 2);
        doc.setFont(undefined, 'normal');
        return y + 8;
    }

    function addLabelValue(label, value, x, y, maxWidth) {
        maxWidth = maxWidth || 80;
        doc.setTextColor(brand[0], brand[1], brand[2]);
        doc.setFontSize(9);
        doc.setFont(undefined, 'bold');
        doc.text(label + ':', x, y);
        doc.setTextColor(52, 73, 94);
        doc.setFont(undefined, 'normal');
        const lines = doc.splitTextToSize(String(value || 'Not provided'), maxWidth);
        doc.text(lines, x + 35, y);
        return y + (lines.length * 5);
    }

    doc.setFillColor(brand[0], brand[1], brand[2]);
    doc.rect(0, 0, 210, 50, 'F');
    doc.setFillColor(255, 255, 255);
    doc.circle(30, 25, 10, 'F');
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('WC', 30, 27, { align: 'center' });
    doc.setTextColor(255, 255, 255);
    doc.setFontSize(22);
    doc.text('WATOTO CHURCH', 105, 22, { align: 'center' });
    doc.setFontSize(14);
    doc.text('WEDDING APPLICATION (YOUR COPY)', 105, 30, { align: 'center' });
    doc.setFontSize(10);
    doc.setFont(undefined, 'normal');
    doc.text('"Celebrating Christ, Caring for Community"', 105, 38, { align: 'center' });
    doc.text('Submitted application summary', 105, 44, { align: 'center' });

    const bookingId = '<?= str_pad((string) ($booking['id'] ?? '0'), 4, '0', STR_PAD_LEFT) ?>';
    doc.setFillColor(248, 249, 250);
    doc.roundedRect(15, 55, 180, 18, 3, 3, 'F');
    doc.setDrawColor(brand[0], brand[1], brand[2]);
    doc.setLineWidth(0.5);
    doc.roundedRect(15, 55, 180, 18, 3, 3, 'D');
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(11);
    doc.setFont(undefined, 'bold');
    doc.text('APPLICATION #' + bookingId, 20, 62);
    doc.text(<?= json_encode('STATUS: ' . strtoupper((string) ($booking['status'] ?? ''))) ?>, 120, 62);
    doc.setFont(undefined, 'normal');
    doc.setFontSize(9);
    doc.text('Generated: ' + new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }), 20, 68);
    doc.text(<?= json_encode('Submitted: ' . (! empty($booking['created_at']) ? date('F j, Y', strtotime($booking['created_at'])) : 'Unknown')) ?>, 120, 68);

    let yPos = 80;

    yPos = addSectionHeader('VENUE & DATE INFORMATION', yPos);
    checkNewPage(25);
    doc.setFillColor(255, 248, 220);
    doc.roundedRect(15, yPos - 3, 180, 25, 2, 2, 'F');
    doc.setDrawColor(255, 193, 7);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 25, 2, 2, 'D');
    yPos = addLabelValue('Campus/Venue', <?= json_encode($booking['campus_name'] ?? 'Not assigned', JSON_UNESCAPED_UNICODE) ?>, 20, yPos, 150);
    yPos = addLabelValue('Wedding Date', <?= json_encode($weddingDateFormatted, JSON_UNESCAPED_UNICODE) ?>, 20, yPos, 150);
    yPos = addLabelValue('Ceremony Time', <?= json_encode($weddingTimeFormatted, JSON_UNESCAPED_UNICODE) ?>, 20, yPos, 150);
    yPos += 5;

    yPos = addSectionHeader('BRIDE INFORMATION', yPos);
    checkNewPage(70);
    doc.setFillColor(255, 240, 245);
    doc.roundedRect(15, yPos - 3, 180, 70, 2, 2, 'F');
    doc.setDrawColor(255, 182, 193);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 70, 2, 2, 'D');
    let brideY = yPos;
    brideY = addLabelValue('Full Name', <?= json_encode($booking['bride_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Phone', <?= json_encode($booking['bride_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Email', <?= json_encode($booking['bride_email'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Date of Birth', <?= json_encode(! empty($booking['bride_date_of_birth']) ? date('F j, Y', strtotime($booking['bride_date_of_birth'])) : 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Age', <?= json_encode($booking['bride_age'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Nationality', <?= json_encode($booking['bride_nationality'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Marital Status', <?= json_encode($brideMaritalPdf, JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Occupation', <?= json_encode($booking['bride_occupation'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('ID Type', <?= json_encode($booking['bride_id_type'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('ID Number', <?= json_encode($booking['bride_id_number'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    brideY = addLabelValue('Residential address', <?= json_encode(format_residential_address_plain($booking['bride_address'] ?? null), JSON_UNESCAPED_UNICODE) ?>, 20, brideY, 150);
    yPos = brideY + 5;

    yPos = addSectionHeader('GROOM INFORMATION', yPos);
    checkNewPage(70);
    doc.setFillColor(240, 248, 255);
    doc.roundedRect(15, yPos - 3, 180, 70, 2, 2, 'F');
    doc.setDrawColor(173, 216, 230);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 70, 2, 2, 'D');
    let groomY = yPos;
    groomY = addLabelValue('Full Name', <?= json_encode($booking['groom_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Phone', <?= json_encode($booking['groom_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Email', <?= json_encode($booking['groom_email'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Date of Birth', <?= json_encode(! empty($booking['groom_date_of_birth']) ? date('F j, Y', strtotime($booking['groom_date_of_birth'])) : 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Age', <?= json_encode($booking['groom_age'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Nationality', <?= json_encode($booking['groom_nationality'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Marital Status', <?= json_encode($groomMaritalPdf, JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Occupation', <?= json_encode($booking['groom_occupation'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('ID Type', <?= json_encode($booking['groom_id_type'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('ID Number', <?= json_encode($booking['groom_id_number'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    groomY = addLabelValue('Residential address', <?= json_encode(format_residential_address_plain($booking['groom_address'] ?? null), JSON_UNESCAPED_UNICODE) ?>, 20, groomY, 150);
    yPos = groomY + 5;

    yPos = addSectionHeader('FAMILY INFORMATION', yPos);
    checkNewPage(95);
    doc.setFillColor(248, 255, 248);
    doc.roundedRect(15, yPos - 3, 180, 95, 2, 2, 'F');
    doc.setDrawColor(200, 230, 200);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 95, 2, 2, 'D');
    let familyY = yPos;
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Bride\'s parents:', 20, familyY);
    familyY += 6;
    familyY = addLabelValue('Father\'s Name', <?= json_encode($booking['bride_father'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Father\'s Occupation', <?= json_encode($booking['bride_father_occupation'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Father\'s Status', <?= json_encode(parent_living_label($booking['bride_father_status'] ?? null), JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Father\'s Contact', <?= json_encode(
        ($booking['bride_father_status'] ?? '') === 'deceased'
            ? 'N/A'
            : (trim((string) ($booking['bride_father_phone'] ?? '')) !== ''
                ? $booking['bride_father_phone']
                : ($booking['bride_family_phone'] ?? 'Not provided'))
    , JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Name', <?= json_encode($booking['bride_mother'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Occupation', <?= json_encode($booking['bride_mother_occupation'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Status', <?= json_encode(parent_living_label($booking['bride_mother_status'] ?? null), JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Contact', <?= json_encode(
        ($booking['bride_mother_status'] ?? '') === 'deceased'
            ? 'N/A'
            : (trim((string) ($booking['bride_mother_phone'] ?? '')) !== ''
                ? $booking['bride_mother_phone']
                : ($booking['bride_family_phone'] ?? 'Not provided'))
    , JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY += 3;
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Groom\'s parents:', 20, familyY);
    familyY += 6;
    familyY = addLabelValue('Father\'s Name', <?= json_encode($booking['groom_father'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Father\'s Occupation', <?= json_encode($booking['groom_father_occupation'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Father\'s Status', <?= json_encode(parent_living_label($booking['groom_father_status'] ?? null), JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Father\'s Contact', <?= json_encode(
        ($booking['groom_father_status'] ?? '') === 'deceased'
            ? 'N/A'
            : (trim((string) ($booking['groom_father_phone'] ?? '')) !== ''
                ? $booking['groom_father_phone']
                : ($booking['groom_family_phone'] ?? 'Not provided'))
    , JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Name', <?= json_encode($booking['groom_mother'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Occupation', <?= json_encode($booking['groom_mother_occupation'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Status', <?= json_encode(parent_living_label($booking['groom_mother_status'] ?? null), JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    familyY = addLabelValue('Mother\'s Contact', <?= json_encode(
        ($booking['groom_mother_status'] ?? '') === 'deceased'
            ? 'N/A'
            : (trim((string) ($booking['groom_mother_phone'] ?? '')) !== ''
                ? $booking['groom_mother_phone']
                : ($booking['groom_family_phone'] ?? 'Not provided'))
    , JSON_UNESCAPED_UNICODE) ?>, 20, familyY, 150);
    yPos = familyY + 5;

    yPos = addSectionHeader('CHURCH MEMBERSHIP', yPos);
    checkNewPage(55);
    doc.setFillColor(255, 250, 240);
    doc.roundedRect(15, yPos - 3, 180, 55, 2, 2, 'F');
    doc.setDrawColor(255, 218, 185);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 55, 2, 2, 'D');
    let churchY = yPos;
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Bride\'s membership:', 20, churchY);
    churchY += 6;
    churchY = addLabelValue('Church member', <?= json_encode($pdfBrideChurch, JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    <?php if ($bc === 'yes'): ?>
    churchY = addLabelValue('Cell Family Number', <?= json_encode($booking['bride_cell_group_number'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Cell Family Leader', <?= json_encode($booking['bride_cell_leader_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Leader Phone', <?= json_encode($booking['bride_cell_leader_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    <?php elseif ($bc === 'other'): ?>
    churchY = addLabelValue('Church Name', <?= json_encode($booking['bride_church_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Senior Pastor', <?= json_encode($booking['bride_senior_pastor'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Pastor Phone', <?= json_encode($booking['bride_pastor_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    <?php endif; ?>
    churchY += 3;
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Groom\'s membership:', 20, churchY);
    churchY += 6;
    churchY = addLabelValue('Church member', <?= json_encode($pdfGroomChurch, JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    <?php if ($gc === 'yes'): ?>
    churchY = addLabelValue('Cell Family Number', <?= json_encode($booking['groom_cell_group_number'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Cell Family Leader', <?= json_encode($booking['groom_cell_leader_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Leader Phone', <?= json_encode($booking['groom_cell_leader_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    <?php elseif ($gc === 'other'): ?>
    churchY = addLabelValue('Church Name', <?= json_encode($booking['groom_church_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Senior Pastor', <?= json_encode($booking['groom_senior_pastor'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    churchY = addLabelValue('Pastor Phone', <?= json_encode($booking['groom_pastor_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, churchY, 150);
    <?php endif; ?>
    yPos = churchY + 5;

    yPos = addSectionHeader('BEST MAN & MATRON', yPos);
    checkNewPage(45);
    doc.setFillColor(245, 245, 250);
    doc.roundedRect(15, yPos - 3, 180, 45, 2, 2, 'F');
    doc.setDrawColor(200, 200, 220);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 45, 2, 2, 'D');
    let witnessY = yPos;
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Best man:', 20, witnessY);
    witnessY += 6;
    witnessY = addLabelValue('Full Name', <?= json_encode($booking['witness1_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    witnessY = addLabelValue('Phone', <?= json_encode($booking['witness1_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    witnessY = addLabelValue('ID Number', <?= json_encode($booking['witness1_id_number'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    witnessY = addLabelValue('Marital status', <?= json_encode(witness_marital_status_label($w1marital), JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    witnessY += 3;
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFont(undefined, 'bold');
    doc.text('Matron:', 20, witnessY);
    witnessY += 6;
    witnessY = addLabelValue('Full Name', <?= json_encode($booking['witness2_name'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    witnessY = addLabelValue('Phone', <?= json_encode($booking['witness2_phone'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    witnessY = addLabelValue('ID Number', <?= json_encode($booking['witness2_id_number'] ?? 'Not provided', JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    witnessY = addLabelValue('Marital status', <?= json_encode(witness_marital_status_label($w2marital), JSON_UNESCAPED_UNICODE) ?>, 20, witnessY, 150);
    yPos = witnessY + 5;

    yPos = addSectionHeader('PAYMENT INFORMATION', yPos);
    checkNewPage(40);
    const totalRequired = <?= json_encode($totalRequired) ?>;
    const totalPaidJs = <?= json_encode($totalPaid) ?>;
    const pendingAmt = <?= json_encode($pendingAmount) ?>;
    const remainingBal = <?= json_encode($remainingBalance) ?>;
    doc.setFillColor(remainingBal <= 0 ? 220 : 255, remainingBal <= 0 ? 255 : 248, 220);
    doc.roundedRect(15, yPos - 3, 180, 35, 2, 2, 'F');
    doc.setDrawColor(remainingBal <= 0 ? 40 : 255, remainingBal <= 0 ? 167 : 193, remainingBal <= 0 ? 69 : 7);
    doc.setLineWidth(0.5);
    doc.roundedRect(15, yPos - 3, 180, 35, 2, 2, 'D');
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(10);
    doc.setFont(undefined, 'bold');
    doc.text('Total Required:', 20, yPos);
    doc.text('Amount Paid:', 20, yPos + 7);
    if (pendingAmt > 0) {
        doc.text('Pending Verification:', 20, yPos + 14);
    }
    doc.text('Remaining Balance:', 20, yPos + (pendingAmt > 0 ? 21 : 14));
    doc.setFont(undefined, 'normal');
    doc.text('UGX ' + totalRequired.toLocaleString(), 80, yPos);
    doc.text('UGX ' + totalPaidJs.toLocaleString(), 80, yPos + 7);
    if (pendingAmt > 0) {
        doc.setTextColor(255, 152, 0);
        doc.text('UGX ' + pendingAmt.toLocaleString(), 80, yPos + 14);
    }
    if (remainingBal > 0) {
        doc.setTextColor(231, 76, 60);
    } else {
        doc.setTextColor(39, 174, 96);
    }
    doc.text('UGX ' + remainingBal.toLocaleString(), 80, yPos + (pendingAmt > 0 ? 21 : 14));
    doc.setTextColor(52, 73, 94);
    doc.setFont(undefined, 'bold');
    doc.text('Status:', 120, yPos + 7);
    doc.setFont(undefined, 'normal');
    if (remainingBal <= 0) {
        doc.setTextColor(39, 174, 96);
    } else {
        doc.setTextColor(230, 126, 34);
    }
    doc.text(remainingBal <= 0 ? 'PAID IN FULL' : (totalPaidJs === 0 ? 'PENDING' : 'PARTIAL PAYMENT'), 140, yPos + 7);
    yPos += 40;

    <?php if (! empty($payments)): ?>
    checkNewPage(30);
    doc.setTextColor(brand[0], brand[1], brand[2]);
    doc.setFontSize(11);
    doc.setFont(undefined, 'bold');
    doc.text('Payment history:', 20, yPos);
    yPos += 8;
    doc.setFillColor(250, 250, 250);
    doc.roundedRect(15, yPos - 3, 180, 8, 1, 1, 'F');
    doc.setDrawColor(200, 200, 200);
    doc.setLineWidth(0.2);
    doc.roundedRect(15, yPos - 3, 180, 8, 1, 1, 'D');
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(8);
    doc.setFont(undefined, 'bold');
    doc.text('Date', 18, yPos);
    doc.text('Amount', 60, yPos);
    doc.text('Reference', 100, yPos);
    doc.text('Status', 160, yPos);
    yPos += 5;
    <?php foreach ($payments as $payment): ?>
    checkNewPage(8);
    doc.setFont(undefined, 'normal');
    doc.text(<?= json_encode(! empty($payment['payment_date']) ? date('M j, Y', strtotime($payment['payment_date'])) : (! empty($payment['created_at']) ? date('M j, Y', strtotime($payment['created_at'])) : 'N/A')) ?>, 18, yPos);
    doc.text(<?= json_encode('UGX ' . number_format((float) ($payment['amount'] ?? 0))) ?>, 60, yPos);
    doc.text(<?= json_encode($payment['transaction_reference'] ?? 'N/A', JSON_UNESCAPED_UNICODE) ?>, 100, yPos);
    <?php if (($payment['status'] ?? '') === 'completed'): ?>
    doc.setTextColor(39, 174, 96);
    <?php elseif (($payment['status'] ?? '') === 'pending'): ?>
    doc.setTextColor(255, 152, 0);
    <?php else: ?>
    doc.setTextColor(231, 76, 60);
    <?php endif; ?>
    doc.text(<?= json_encode(strtoupper((string) ($payment['status'] ?? ''))) ?>, 160, yPos);
    doc.setTextColor(52, 73, 94);
    yPos += 5;
    <?php endforeach; ?>
    yPos += 3;
    <?php endif; ?>

    <?php if (! empty($uploadedDocuments)): ?>
    yPos = addSectionHeader('UPLOADED DOCUMENTS', yPos);
    checkNewPage(20);
    doc.setFillColor(250, 250, 255);
    doc.roundedRect(15, yPos - 3, 180, 15, 2, 2, 'F');
    doc.setDrawColor(200, 200, 255);
    doc.setLineWidth(0.3);
    doc.roundedRect(15, yPos - 3, 180, 15, 2, 2, 'D');
    doc.setTextColor(52, 73, 94);
    doc.setFontSize(9);
    <?php foreach ($uploadedDocuments as $index => $doc): ?>
    if (<?= (int) $index ?> > 0) yPos += 5;
    checkNewPage(8);
    doc.text(<?= json_encode('• ' . ($doc['name'] ?? ''), JSON_UNESCAPED_UNICODE) ?>, 20, yPos);
    doc.setFontSize(8);
    doc.setTextColor(128, 128, 128);
    doc.text(<?= json_encode('  Uploaded: ' . (! empty($doc['uploaded_at']) ? date('M j, Y', strtotime($doc['uploaded_at'])) : 'N/A')) ?>, 25, yPos + 4);
    doc.setFontSize(9);
    doc.setTextColor(52, 73, 94);
    yPos += 6;
    <?php endforeach; ?>
    yPos += 3;
    <?php endif; ?>

    const pageCount = doc.internal.getNumberOfPages();
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFillColor(brand[0], brand[1], brand[2]);
        doc.rect(0, 280, 210, 15, 'F');
        doc.setTextColor(255, 255, 255);
        doc.setFontSize(8);
        doc.text('Watoto Church Wedding Department | Your application copy', 105, 286, { align: 'center' });
        doc.text('Page ' + i + ' of ' + pageCount, 105, 292, { align: 'center' });
    }

    const fileName = 'Watoto_Wedding_Application_' + bookingId + '_' + new Date().toISOString().split('T')[0] + '.pdf';
    doc.save(fileName);
}
</script>
