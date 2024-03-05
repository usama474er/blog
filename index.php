<?php
session_start();

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "blog";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a new blog post
function createBlogPost($conn, $title, $content) {
    $sql = "INSERT INTO blog_posts (title, content) VALUES ('$title', '$content')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        return false;
    }
}

// Function to update a blog post
function updateBlogPost($conn, $id, $title, $content) {
    $sql = "UPDATE blog_posts SET title='$title', content='$content' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error updating record: " . $conn->error;
        return false;
    }
}

// Function to delete a blog post
function deleteBlogPost($conn, $id) {
    $sql = "DELETE FROM blog_posts WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        echo "Error deleting record: " . $conn->error;
        return false;
    }
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            case 'create':
                if (isset($_POST['title']) && isset($_POST['content'])) {
                    createBlogPost($conn, $_POST['title'], $_POST['content']);
                }
                break;
            case 'update':
                if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['content'])) {
                    updateBlogPost($conn, $_POST['id'], $_POST['title'], $_POST['content']);
                }
                break;
            case 'delete':
                if (isset($_POST['id'])) {
                    deleteBlogPost($conn, $_POST['id']);
                }
                break;
        }
    }
    // Redirect to avoid form resubmission
    header("Location: {$_SERVER['PHP_SELF']}");
    exit;
}

// Function to retrieve all blog posts from the database
function getAllBlogPosts($conn) {
    $sql = "SELECT * FROM blog_posts";
    $result = $conn->query($sql);

    $posts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    return $posts;
}

// Load existing blog posts
$blogPosts = getAllBlogPosts($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>
   
    <form method="post">
        <input type="hidden" name="action" value="create">
        <label for="title">Time : 10:26</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="content">Content: Hello text file</label><br>
        <label for="content">Size: 4B</label><br>
        
    </form>


   

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
