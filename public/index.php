<?php
declare(strict_types=1);

require_once __DIR__ . '/../src/validate.php';

function h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$values = [
    'name' => '',
    'email' => '',
    'message' => '',
];

$errors = [
    'name' => '',
    'email' => '',
    'message' => '',
];

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    $values['name'] = isset($_POST['name']) && is_string($_POST['name']) ? $_POST['name'] : '';
    $values['email'] = isset($_POST['email']) && is_string($_POST['email']) ? $_POST['email'] : '';
    $values['message'] = isset($_POST['message']) && is_string($_POST['message']) ? $_POST['message'] : '';

    $nameTrimmed = trim($values['name']);
    $emailTrimmed = trim($values['email']);
    $messageTrimmed = trim($values['message']);

    if (!validate_required($nameTrimmed)) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($nameTrimmed, 2, 60)) {
        $errors['name'] = 'Invalid name length (too short or too long).';
    }

    if (!validate_required($emailTrimmed) || !validate_email($emailTrimmed)) {
        $errors['email'] = 'Invalid email address.';
    }

    if (!validate_required($messageTrimmed)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($messageTrimmed, 10, 1000)) {
        $errors['message'] = 'Invalid message length (too short or too long).';
    }

    $hasErrors = $errors['name'] !== '' || $errors['email'] !== '' || $errors['message'] !== '';

    if (!$hasErrors) {
        header('Location: success.php', true, 302);
        exit;
    }

    http_response_code(200);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
</head>
<body>
  <h1>Contact Us</h1>
  <form method="post" action="index.php">
    <div>
      <label>Name <input type="text" name="name" value="<?= h($values['name']) ?>"></label>
      <?php if ($errors['name'] !== ''): ?>
        <span class="error"><?= h($errors['name']) ?></span>
      <?php endif; ?>
    </div><br>

    <div>
      <label>Email <input type="text" name="email" value="<?= h($values['email']) ?>"></label>
      <?php if ($errors['email'] !== ''): ?>
        <span class="error"><?= h($errors['email']) ?></span>
      <?php endif; ?>
    </div><br>

    <div>
      <label>Message <textarea name="message"><?= h($values['message']) ?></textarea></label>
      <?php if ($errors['message'] !== ''): ?>
        <span class="error"><?= h($errors['message']) ?></span>
      <?php endif; ?>
    </div><br>

    <button type="submit">Send</button>
  </form>
</body>
</html>