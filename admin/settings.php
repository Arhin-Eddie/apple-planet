<?php
// admin/settings.php
require_once __DIR__ . '/admin_header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currency_symbol = sanitize_input($_POST['currency_symbol']);
    
    if (empty($currency_symbol)) {
        $error = "Currency symbol cannot be empty.";
    } else {
        // Upsert setting
        $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES ('currency_symbol', ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("ss", $currency_symbol, $currency_symbol);
        
        if ($stmt->execute()) {
            $success = "Settings updated successfully.";
            $global_settings['currency_symbol'] = $currency_symbol;
        } else {
            $error = "Failed to update settings.";
        }
    }
}

$current_currency = $global_settings['currency_symbol'] ?? '$';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Store Settings</h3>
</div>

<?php if($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
<?php if($error): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card">
            <h5 class="fw-bold mb-4">Localization</h5>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Currency Symbol</label>
                    <input type="text" name="currency_symbol" class="form-control" value="<?= htmlspecialchars($current_currency) ?>" required placeholder="e.g. $, €, £, GHS">
                    <div class="form-text small">This symbol will be displayed across the entire store.</div>
                </div>
                
                <button type="submit" class="btn btn-primary py-2 px-4">Save Settings</button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/admin_footer.php'; ?>
