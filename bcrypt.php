<?php
// web_verify.php
$hash = '$2y$10$.mWP1BzBTR7pjjYBqMtriu5HWPSUVvPKP/lf7kSHJN6f/sfHBkG3K';
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate = $_POST['password'] ?? '';
    $result = password_verify($candidate, $hash) ? 'MATCH' : 'NO MATCH';
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Verify Password</title></head>
<body>
  <h3>Verify password against bcrypt hash</h3>
  <form method="post">
    <label>Enter password to test:
      <input name="password" type="password" required>
    </label>
    <button type="submit">Verify</button>
  </form>

  <?php if ($result !== null): ?>
    <p>Result: <strong><?= htmlspecialchars($result) ?></strong></p>
  <?php endif; ?>
</body>
</html>
<?php
$password = '123';

// PASSWORD_BCRYPT produces a $2y$... bcrypt hash
$hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

echo "Hash: " . $hash . PHP_EOL;
