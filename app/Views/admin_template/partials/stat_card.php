<?php
/**
 * Stat Card Partial
 * 
 * @param string $value - Stat value (or use $number as alias)
 * @param string $number - Stat number (alias for $value)
 * @param string $label - Stat label
 * @param string $icon - Font Awesome icon class
 * @param string $iconGradient - CSS gradient for icon background (e.g., "linear-gradient(135deg, #3b82f6, #2563eb)")
 * @param string $type - Type for gradient (primary, success, warning, info, danger) - used if iconGradient not provided
 * @param string $change - Optional change indicator text
 * @param bool $positive - Whether change is positive (for styling)
 */

// CodeIgniter should extract variables automatically, but let's be explicit
// Get all variables from the current scope - try multiple ways
$passedValue = null;
$passedNumber = null;
$passedLabel = null;
$passedIcon = null;
$passedType = null;

// Try to get variables - CodeIgniter should extract them automatically
if (isset($value)) $passedValue = $value;
if (isset($number)) $passedNumber = $number;
if (isset($label)) $passedLabel = $label;
if (isset($icon)) $passedIcon = $icon;
if (isset($type)) $passedType = $type;

$debugStatCard = isset($debugStatCard) ? $debugStatCard : false;

// If still no value, try getting from $_data array (CodeIgniter internal)
if ($passedValue === null && $passedNumber === null && isset($data) && is_array($data)) {
    $passedValue = $data['value'] ?? $data['number'] ?? null;
    $passedLabel = $data['label'] ?? null;
    $passedIcon = $data['icon'] ?? null;
    $passedType = $data['type'] ?? null;
}

// Debug: Check what variables are available
if ($debugStatCard) {
    echo "<!-- Stat Card Debug: value=" . var_export($passedValue, true);
    echo ", number=" . var_export($passedNumber, true);
    echo ", label=" . var_export($passedLabel, true);
    echo ", icon=" . var_export($passedIcon, true);
    echo ", type=" . var_export($passedType, true) . " -->";
}

// Get stat value (support both value and number parameters)
$statValue = $passedValue !== null ? $passedValue : ($passedNumber !== null ? $passedNumber : '0');

// Get icon class
$iconClass = $passedIcon !== null ? $passedIcon : 'fas fa-chart-line';

// Get label
$labelText = $passedLabel !== null ? $passedLabel : 'Label';

// Determine gradient
$finalGradient = 'linear-gradient(135deg, #3b82f6, #2563eb)'; // Default

$passedIconGradient = isset($iconGradient) ? $iconGradient : null;
if ($passedIconGradient !== null && !empty($passedIconGradient)) {
    $finalGradient = $passedIconGradient;
} elseif ($passedType !== null && !empty($passedType)) {
    $gradients = [
        'primary' => 'linear-gradient(135deg, #64017f, #8b4a9c)',
        'success' => 'linear-gradient(135deg, #28a745, #20c997)',
        'warning' => 'linear-gradient(135deg, #ffc107, #fd7e14)',
        'info' => 'linear-gradient(135deg, #17a2b8, #138496)',
        'danger' => 'linear-gradient(135deg, #dc3545, #c82333)',
        'primary-mini' => 'linear-gradient(135deg, #64017f, #8b4a9c)',
        'success-mini' => 'linear-gradient(135deg, #28a745, #20c997)',
        'warning-mini' => 'linear-gradient(135deg, #ffc107, #fd7e14)',
        'info-mini' => 'linear-gradient(135deg, #17a2b8, #138496)',
    ];
    if (isset($gradients[$passedType])) {
        $finalGradient = $gradients[$passedType];
    }
}
?>
<div class="stat-card">
    <div class="stat-header">
        <div class="stat-icon" style="background: <?= esc($finalGradient) ?>;">
            <i class="<?= esc($iconClass) ?>"></i>
        </div>
    </div>
    <h3 class="stat-value">
        <?php 
        // Final check - if still no value, show a warning
        if (empty($statValue) || $statValue === '0') {
            // Try one more time to get the value
            $directValue = isset($value) ? $value : (isset($number) ? $number : null);
            if ($directValue !== null) {
                echo esc($directValue);
            } else {
                // Show 0 but with a visual indicator if debugging
                echo esc($statValue);
                if ($debugStatCard) {
                    echo ' <small style="color: red;">(NO VALUE PASSED)</small>';
                }
            }
        } else {
            echo esc($statValue);
        }
        ?>
    </h3>
    <?php if ($debugStatCard): ?>
        <!-- Debug: statValue = <?= var_export($statValue, true) ?>, value param = <?= var_export(isset($value) ? $value : 'NOT SET', true) ?>, number param = <?= var_export(isset($number) ? $number : 'NOT SET', true) ?> -->
    <?php endif; ?>
    <p class="stat-label"><?= esc($labelText) ?></p>
    <?php if (isset($change)): ?>
        <span class="stat-change <?= (isset($positive) && $positive) ? 'positive' : '' ?>">
            <?php if (isset($positive) && $positive): ?>
                <i class="fas fa-arrow-up"></i>
            <?php endif; ?>
            <?= esc($change) ?>
        </span>
    <?php endif; ?>
</div>
