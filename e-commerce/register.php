<?php

session_start();

require 'db.php';
require 'functions.php';

$message = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $fullname = sanitize($_POST['fullname']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if(empty($fullname) ||
       empty($email) ||
       empty($password))
    {
        $message = "All fields are required.";
    }
    elseif($password !== $confirm)
    {
        $message = "Passwords do not match.";
    }
    else
    {
        $check = $pdo->prepare(
            "SELECT id FROM users WHERE email=?"
        );

        $check->execute([$email]);

        if($check->rowCount() > 0)
        {
            $message = "Email already exists.";
        }
        else
        {
            $hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insert = $pdo->prepare(
                "INSERT INTO users
                (fullname,email,password)
                VALUES(?,?,?)"
            );

            $insert->execute([
                $fullname,
                $email,
                $hash
            ]);

            $message = "Registration successful.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Create Account</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="auth-container">

<h2>Create Account</h2>

<?php if($message): ?>
<p><?php echo $message; ?></p>
<?php endif; ?>

<form method="POST">

<input
type="text"
name="fullname"
placeholder="Full Name"
required>

<input
type="email"
name="email"
placeholder="Email Address"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<input
type="password"
name="confirm"
placeholder="Confirm Password"
required>

<button type="submit">
Register
</button>

</form>

</div>

</body>
</html>