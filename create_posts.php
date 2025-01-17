<?php
session_start(); 
if (!isset($_SESSION["user_id"])) {
   header("Location: login.php");
   exit();
}

$servername = "localhost"; 
$username = "root"; 
$password = "v!=eQ=SSWst6"; 
$dbname = "bulletin_board";
$port = 3307;
 
$conn = new mysqli($servername, $username, $password, $dbname, $port); 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    $title = $_POST["title"]; 
    $content = $_POST["content"]; 
    $user_id = $_SESSION["user_id"];

    $uploadOk = 1;
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
	    $targetFile = $uploadDir . $fileName;

	    if (move_uploaded_file($fileTmpName, $targetFile)) {
		echo "The file has been uploaded successfully.";
	    } else {
		echo "There was an error uploading your file.";
	    }
	}
    }

    $sql = "INSERT INTO posts (title, content, user_id, file_path) VALUES ('$title', '$content', '$user_id', '$targetFile')";
    if ($conn->query($sql) === TRUE) {
	echo "Post has been created successfully.";
    } else {
	echo "Error: " . $conn->error;
    }
}

$conn->close();
?> 

<form method="POST"enctype="multipart/form-data" >
    Title: <input type="text" name="title"required><br>
    Content: <textarea name="content"required></textarea><br>
    File Upload: <input type="file" name="fileToUpload"><br>
    <button type="submit">Submit Post</button> 
</form>
