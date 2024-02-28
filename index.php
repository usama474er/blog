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
    <title>Simple Blog Management System</title>
</head>
<body>
    <h1>Simple Blog Management System</h1>

    <!-- Form to create a new blog post -->
    <h2>Create New Blog Post</h2>
    <form method="post">
        <input type="hidden" name="action" value="create">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content" rows="4" cols="50" required></textarea><br>
        <input type="submit" value="Create Post">
    </form>

    <!-- Display existing blog posts -->
    <h2>Existing Blog Posts</h2>
    <?php if (empty($blogPosts)): ?>
        <p>No posts found.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($blogPosts as $post): ?>
                <li>
                    <strong><?php echo $post['title']; ?></strong><br>
                    <?php echo $post['content']; ?><br>
                    <!-- Form to update a blog post -->
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                        <input type="text" name="title" value="<?php echo $post['title']; ?>" required>
                        <textarea name="content" rows="2" cols="40" required><?php echo $post['content']; ?></textarea>
                        <button type="submit">Update</button>
                    </form>
                    <!-- Form to delete a blog post -->
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
