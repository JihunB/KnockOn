<?php 
session_start();
if (!isset($_SESSION["user_id"])) {
    echo "You must be logged in to edit a post.";
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

$id = $_GET['id']; 
$sql = "SELECT * FROM posts WHERE id=$id";
$result = $conn->query($sql); 
$post = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title']; 
    $content = $_POST['content'];
 
    $sql = "UPDATE posts SET title='$title', content='$content' WHERE id=$id"; 
    if ($conn->query($sql) === TRUE) {
        echo "Post updated successfully";
        header("Location: list_posts.php");
	exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

?>

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Edit Post</title> 
    <style>
        body { 
	    font-family: Arial, sans-serif; 
	    background-color: #4a5568; 
            color: white; 
	    display: flex; 
	    flex-direction: column; 
            align-items: center; 
	    justify-content: center; 
	    height: 100vh; 
            margin: 0;
        }

        .container { 
	    background-color: #2d3748; 
	    padding: 20px; 
            border-radius: 10px; 
	    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); 
            width: 400px; 
	    text-align: center;
        }

        h1 { 
	    margin-bottom: 20px;
        }

        input, textarea { 
	    width: 100%; 
	    padding: 10px; 
	    margin: 10px 0; 
            border-radius: 5px; 
	    border: none; 
	    font-size: 16px;
	    box-sizing: border-box;
        }
	
	textarea {
	    height: 150px;
	    resize: none;
	}

        button { 
	    background-color: #007bff; 
	    color: white; 
	    border: none; 
            padding: 10px 15px; 
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

<div class="container"> 
    <h1>Edit Post</h1> 
    <form method="POST">
        <input type="text" name="title" value="<?php echo $post["title"]; ?>" placeholder="Title"><br> 
	<textarea name="content" placeholder="Content"><?php echo $post["content"]; ?></textarea><br> 
        <button type="submit">Update Post</button>
    </form> 
</div> 

</body>
</html>
