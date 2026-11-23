<?php
session_start(); 
if (!isset($_SESSION["user_id"])) {
   header("Location: login.php");
   exit();
}

$servername = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "knockon_user";
$password = getenv("DB_PASS");
$dbname = getenv("DB_NAME") ?: "knockon_db";
 
$conn = new mysqli($servername, $username, $password, $dbname); 

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $title = $_POST["title"]; 
    $content = $_POST["content"]; 
    $user_id = $_SESSION["user_id"];

    $uploadOk = 1;
    $file_path = "";
    $targetFile = "";
    $fileName = $fileTmpName = $fileType = "";

    if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
        $fileTmpName = $_FILES['fileToUpload']['tmp_name'];
        $fileName = basename($_FILES['fileToUpload']['name']); 
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION)); 

	if ($_FILES['fileToUpload']['size'] > 500000) { 
	    echo "The file is too large.";
	    $uploadOk = 0;
	}
	if (!in_array($fileType, ['jpg', 'png', 'jpeg', 'gif'])) {
	    echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
	    $uploadOk = 0;
	}

	if ($uploadOk == 1) { 
	    $uploadDir = "uploads/";
	    if (!file_exists($uploadDir)) {
		mkdir($uploadDir, 0777, true);
	    }
	    $uniqueFileName = uniqid() . "_" . $fileName;
	    $targetFile = $uploadDir . $uniqueFileName;

	    if (move_uploaded_file($fileTmpName, $targetFile)) {
		$file_path = $targetFile;
	    } else {
		echo "Error uploading file.";
		$file_path = "";
	    }
	} else {
	    $file_path = "";
    }
}

    $sql = "INSERT INTO posts (title, content, user_id, file_path) VALUES ('$title', '$content', '$user_id', '$file_path')";

    if ($conn->query($sql) === TRUE) {
	header("Location: list_posts.php?success=1");
	exit();
    } else {
	echo "Error: " . $conn->error;
    }
}

$conn->close();
?> 

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Create Post</title> 
    <style>
        body { 
	    font-family: Arial, sans-serif; 
	    background-color: #4a5568; 
	    color: white; 
	    display: flex; 
	    justify-content: center; 
	    align-items: center; 
	    height: 100vh; 
	    margin: 0;
        }
	nav {
	    position: absolute;
	    top: 20px;
	    width: 100%;
	    text-align: center;
	}

	nav a {
	    color: white;
	    text-decoration: none;
	    margin: 0 15px;
	    font-weight: bold;
	    font-size: 16px;
	}
        
	nav a:hover {
	    text-decoration: underline;
	}

	.container { 
	    background-color: #2d3748; 
            padding: 20px; 
	    border-radius: 10px; 
	    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
	    width: 400px; 
            text-align: center;
	    margin-top: 50px;
        }
        
	h2 { 
	    margin-bottom: 20px;
        }
        
	.form-group { 
	    display: flex; 
	    flex-direction: column; 
	    text-align: left; 
	    margin-bottom: 15px;
        }

        label { 
	    font-size: 14px; 
	    margin-bottom: 5px;
        }

        input, textarea { 
	    padding: 10px; 
	    border-radius: 5px; 
	    border: none; 
	    font-size: 16px;
        }
  
	input[type="file"] { 
	    background-color: white; 
	    padding: 5px;
        }
        
	button { 
	    background-color: #007bff; 
	    color: white; 
	    border: none; 
	    padding: 10px; 
            border-radius: 5px; 
	    cursor: pointer; 
	    font-size: 16px;
        }
        
	button:hover { 
	    background-color: #0056b3;
        }
    </style> 
</head> 
<body> 
    <nav>
	<a href="list_posts.php">Post List</a> |
	<a href="logout.php">Logout</a>
    </nav>

    <div class="container"> 
    	<h2>Create Post</h2> 
	<form method="POST" enctype="multipart/form-data">
            <div class="form-group"> 
		<label for="title">Title</label> 
		<input type="text" id="title" name="title" required>
            </div>
            
            <div class="form-group"> 
		<label for="content">Content</label> 
		<textarea id="content" name="content" rows="4" required></textarea>
            </div>
            
            <div class="form-group"> 
		<label for="file">Attach Image</label> 
		<input type="file" id="file" name="fileToUpload">
            </div>
            
            <button type="submit">Submit Post</button> 
	</form> 
    </div> 
</body>
</html>
