<?php
declare(strict_types=1);
error_reporting(E_ALL);

/**
 * ITCS333 A4 — contact form.
 *   - GET: render the form
 *   - POST: validate name (required, 2-60 chars), email (valid), message (required, 10-1000 chars)
 *   - invalid POST: re-render with inline errors + repopulated fields (200)
 *   - valid POST: redirect (303) to a success page (PRG)
 *   - escape all output with htmlspecialchars
 */

require_once __DIR__ . '/../src/validate.php';

// helper to escape any value before printing it
function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// default values (so nothing is undefined on GET)
$name    = '';
$email   = '';
$message = '';
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // read the submitted values safely
    $name    = isset($_POST['name'])    ? trim((string) $_POST['name'])    : '';
    $email   = isset($_POST['email'])   ? trim((string) $_POST['email'])   : '';
    $message = isset($_POST['message']) ? trim((string) $_POST['message']) : '';

    // name: required, 2-60 characters
    if (!validate_required($name)) {
        $errors['name'] = 'Name is required.';
    } elseif (!validate_length($name, 2, 60)) {
        $errors['name'] = 'Name must be between 2 and 60 characters (too short or too long).';
    }

    // email: valid format
    if (!validate_required($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!validate_email($email)) {
        $errors['email'] = 'Please enter a valid email address (invalid format).';
    }

    // message: required, 10-1000 characters
    if (!validate_required($message)) {
        $errors['message'] = 'Message is required.';
    } elseif (!validate_length($message, 10, 1000)) {
        $errors['message'] = 'Message must be between 10 and 1000 characters (too short or too long).';
    }

    // no errors -> Post/Redirect/Get
    if (count($errors) === 0) {
        header('Location: success.php', true, 303);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Contact Us</title>
  <style>
    .error { color: #c00; font-size: 0.9em; }
  </style>
</head>
<body>
  <h1>Contact Us</h1>

  <?php if (count($errors) > 0): ?>
    <p class="error">Please fix the errors below.</p>
  <?php endif; ?>

  <form method="post" action="index.php">
    <label>Name
      <input type="text" name="name" value="<?php echo e($name); ?>">
    </label>
    <?php if (isset($errors['name'])): ?>
      <span class="error"><?php echo e($errors['name']); ?></span>
    <?php endif; ?>
    <br>

    <label>Email
      <input type="email" name="email" value="<?php echo e($email); ?>">
    </label>
    <?php if (isset($errors['email'])): ?>
      <span class="error"><?php echo e($errors['email']); ?></span>
    <?php endif; ?>
    <br>

    <label>Message
      <textarea name="message"><?php echo e($message); ?></textarea>
    </label>
    <?php if (isset($errors['message'])): ?>
      <span class="error"><?php echo e($errors['message']); ?></span>
    <?php endif; ?>
    <br>

    <button type="submit">Send</button>
  </form>
</body>
</html>
