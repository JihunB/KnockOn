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
$searchQuery = "";
if (isset($_GET['search'])) { 
	$searchQuery = $_GET['search'];
	$searchQuery = $conn->real_escape_string($searchQuery);
}

if ($searchQuery != "") {
	$sql = "SELECT * FROM posts WHERE title LIKE '%$searchQuery%' OR content LIKE '%$searchQuery%'";
} else {
	$sql = "SELECT * FROM posts"; 
}


$result = $conn->query($sql); 
?>

<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Post List</title> 
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
	    width: 500px; 
	    text-align: center; 
	    margin-top: 50px;
        }
        h1 { 
	    margin-bottom: 20px;
        }
        
        .search-bar { 
	    display: flex; 
	    justify-content: center; 
	    gap: 10px; 
	    margin-bottom: 20px;
        }
        .search-bar input { 
	    padding: 10px; 
	    border-radius: 5px; 
	    border: none; 
	    font-size: 16px; 
            width: 70%;
        }
        .search-bar button { 
	    background-color: #007bff; 
	    color: white; 
	    border: none; 
	    padding: 10px; 
            border-radius: 5px; 
	    cursor: pointer; 
	    font-size: 16px;
        }
        .search-bar button:hover { 
	    background-color: #0056b3;
        }
        
        .post-list { 
	    text-align: left;
        }
        .post-item { 
	    background-color: #3b4252; 
	    padding: 15px; 
	    border-radius: 5px; 
	    margin-bottom: 10px; 
	    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        .post-item h3 { 
	    margin: 0 0 10px;
        }
        .post-item p { 
	    font-size: 14px; 
	    color: #ccc;
        }
	.post-item img {
	    max-width: 100%;
	    height: auto;
	    border-radius: 5px;
	    margin-top: 10px;
	}
        .post-item .actions { 
	    margin-top: 10px;
        }
        .post-item .actions a { 
	    color: #ff6b6b; 
	    text-decoration: none; 
	    font-size: 14px; 
            margin-right: 10px;
        }
    </style> 
</head> 
<body> 

    <nav> 
	<?php if (isset($_SESSION['user_id'])) { ?> 
	    <a href="create_posts.php">Create Post</a> |
        <?php } ?> 
	<a href="logout.php">Logout</a> 
    </nav> 

    <div class="container"> 
	<h1>Post List</h1> 

	<?php if (isset($_GET['success'])) { ?>
	    <p style="color: lightgreen; font-weight: bold;">Post has been created successfully.</p>
	<?php } ?>

	<form action="list_posts.php" method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search posts..." value="<?php echo $searchQuery; ?>"> 
	    <button type="submit">Search</button>
        </form> 
	
	<div class="post-list"> 
	    <?php 
	    if ($result->num_rows > 0) { 
		while ($row = $result->fetch_assoc()) {
                    echo "<div class='post-item'>"; 
		    echo "<h3>" . htmlspecialchars($row['title']) . "</h3>"; 
		    echo "<p>" . htmlspecialchars($row['content']) . "</p>"; 

		    if (!empty($row['file_path'])) {
			echo "<img src='" . htmlspecialchars($row['file_path']) . "' alt='Post Image'>";
		    }

		    echo "<p><small>" . $row['created_at'] . "</small></p>"; 
		    echo "<div class='actions'>"; 
		    echo "<a href='edit_post.php?id=" . $row["id"] . "'>Edit</a> | "; 
		    echo "<a href='delete_post.php?id=" . $row["id"] . "'>Delete</a>"; 
		    echo "</div>"; 
		    echo "</div>";
                }
            } else {
                echo "<p>No posts found.</p>";
            }
            ?> 
	</div> 
    </div> 

</body> 
</html> 

<?php 
$conn->close();
?>
