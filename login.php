<?php 

session_start(); 
$servername = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "knockon_user";
$password = getenv("DB_PASS");
$dbname = getenv("DB_NAME") ?: "knockon_db";

$conn = new mysqli($servername, $username, $password, $dbname); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $email = $_POST["email"]; 
    $password = $_POST["password"]; 
    $sql = "SELECT * FROM users WHERE email = '$email'"; 
    $result = $conn->query($sql); 
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); 
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"]; 
            $_SESSION["username"] = $user["username"]; 
            echo "Login successful";
            header("Location: list_posts.php");
	    exit();
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found";
    }
}
?> 

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Register</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head> 
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card p-4 shadow-lg" style="width: 350px;"> 
	<h2 class="text-center">Login</h2> 
	<?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div> 
	<?php endif; ?> 
	<form method="POST">
            <div class="mb-3"> 
		<label class="form-label">Email</label> 
                <input type="email" name="email" class="form-control" required>
            </div> 
	    <div class="mb-3"> 
		<label class="form-label">Password</label> 
		<input type="password" name="password" class="form-control" required>            
	    </div> 
	    <button type="submit" class="btn btn-primary w-100">Login</button>
        </form> 
	<p class="text-center mt-3"><a href="register.php">Don't have 
        an account? Register</a></p>
    </div> 
</body>
</html>
