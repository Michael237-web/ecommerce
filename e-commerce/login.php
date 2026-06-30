<?php

session_start();

require 'db.php';
require 'functions.php';

$message = "";

/* Redirect support (important) */
$redirect = $_GET['redirect'] ?? 'account.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = sanitize($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role'] = $user['role'];

        /* Redirect back to intended page */
        header("Location: " . $redirect);
        exit();

    } else {
        $message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="auth-container">

<h2>Login</h2>

<?php if ($message): ?>
<p class="error-message"><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<input type="password" name="password" placeholder="Password" required>

<button type="submit">Login</button>

</form>

</div>

</body>
</html>