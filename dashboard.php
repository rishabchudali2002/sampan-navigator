<?php
session_start();
include('config.php');



// Delete Tourist Guide
if (isset($_GET['delete_guide'])) {
    $guide_id = intval($_GET['delete_guide']);
    $delete_guide_query = "DELETE FROM users WHERE user_id = $guide_id";
    if (!mysqli_query($conn, $delete_guide_query)) {
        die("Guide Delete Error: " . mysqli_error($conn));
    }
    header('Location: dashboard.php');
    exit();
}

// Load Heritage Site Data for Editing
$edit_heritage_data = null;
if (isset($_GET['edit_heritage'])) {
    $heritage_id_to_edit = intval($_GET['edit_heritage']);
    $edit_query = "SELECT * FROM heritage WHERE heritage_id = $heritage_id_to_edit";
    $edit_result = mysqli_query($conn, $edit_query);
    if ($edit_result && mysqli_num_rows($edit_result) > 0) {
        $edit_heritage_data = mysqli_fetch_assoc($edit_result);
    } else {
        $error_message = "Heritage site not found for editing.";
    }
}


// Fetch heritage sites
$heritage_query = "SELECT * FROM heritage";
$heritage_result = mysqli_query($conn, $heritage_query);

// Fetch tourist guides
$guides_query = "SELECT * FROM users WHERE is_guide = 1";
$guides_result = mysqli_query($conn, $guides_query);



// Add New Heritage Site
if (isset($_POST['add_heritage'])) {
    // Sanitize text fields
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload
    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; 
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . uniqid() . '_' . $imageName;
        
        if (move_uploaded_file($imageTmpName, $targetFile)) {
            $image_path = mysqli_real_escape_string($conn, $targetFile);
        } else {
            die("Error uploading image.");
        }
    }
    
    $insert_query = "INSERT INTO heritage (name, location, description, image) 
                     VALUES ('$name', '$location', '$description', '$image_path')";
    
    if (!mysqli_query($conn, $insert_query)) {
        die("Heritage Insert Error: " . mysqli_error($conn));
    }
    
    header('Location: dashboard.php');
    exit();
}

// Edit Heritage Site
if (isset($_POST['edit_heritage'])) {
    $heritage_id = intval($_POST['heritage_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    // Handle image upload for update (optional)
    $updateImageSQL = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $targetFile = $uploadDir . uniqid() . '_' . $imageName;
        
        if (move_uploaded_file($imageTmpName, $targetFile)) {
            $image_path = mysqli_real_escape_string($conn, $targetFile);
            $updateImageSQL = ", image = '$image_path'";
        } else {
            die("Error uploading image.");
        }
    }
    
    $update_query = "UPDATE heritage SET name = '$name', location = '$location', description = '$description' $updateImageSQL
                     WHERE heritage_id = $heritage_id";
    
    if (!mysqli_query($conn, $update_query)) {
        die("Heritage Update Error: " . mysqli_error($conn));
    }
    
    header('Location: dashboard.php');
    exit();
}

// Delete Heritage Site
if (isset($_GET['delete_heritage'])) {
    $heritage_id = intval($_GET['delete_heritage']);
    $delete_query = "DELETE FROM heritage WHERE heritage_id = $heritage_id";
    if (!mysqli_query($conn, $delete_query)) {
        die("Heritage Delete Error: " . mysqli_error($conn));
    }
    header('Location: dashboard.php');
    exit();
}

// Add New Tourist Guide
if (isset($_POST['add_guide'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Not hashed for simplicity
    $is_guide = 1;
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $insert_guide_query = "INSERT INTO users (email, name, password, is_guide) 
                           VALUES ('$email', '$name', '$hashed_password', '$is_guide')";
    
    if (!mysqli_query($conn, $insert_guide_query)) {
        die("Guide Insert Error: " . mysqli_error($conn));
    }
    
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Dashboard styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 { 
            text-align: center; 
            color: #333; 
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn:hover { 
            background-color: #45a049; 
        }
        .section { 
            margin-bottom: 30px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        table, th, td { 
            border: 1px solid #ddd; 
        }
        th, td { 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        .form-container {
            background-color: #f2f2f2;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container input[type="file"] {
            padding: 5px;
        }
        .form-container button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover { 
            background-color: #45a049; 
        }
        .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #34495e;
      color: #fff;
      padding: 15px 30px;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }

    .admin-header h1 {
      margin: 0;
      font-size: 1.8rem;
      font-weight: 600;
    }

    /* Logout button styling */
    .logout-btn {
      background-color: #e74c3c;
      color: #fff;
      padding: 8px 16px;
      border: none;
      border-radius: 4px;
      text-decoration: none;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }

    /* Add some top margin to the content below the fixed header */
    .dashboard-content {
      padding-top: 80px;
      margin: 0 30px;
    }
  </style>

</head>
<body>

<div class="container">
    <div class="admin-header">
    <h1>Admin Dashboard</h1>
    <a href="logout.php" class="logout-btn">Logout</a>
  </div>
    <!-- Heritage Sites Section -->
    <div class="section">
        <h3>Manage Heritage Sites</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($heritage = mysqli_fetch_assoc($heritage_result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($heritage['name']) ?></td>
                        <td><?= htmlspecialchars($heritage['location']) ?></td>
                        <td><?= htmlspecialchars($heritage['description']) ?></td>
                        <td>
                            <?php if (!empty($heritage['image'])): ?>
                                <img src="<?= htmlspecialchars($heritage['image']) ?>" alt="<?= htmlspecialchars($heritage['name']) ?>" style="width: 100px;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="dashboard.php?edit_heritage=<?= $heritage['heritage_id'] ?>" class="btn">Edit</a> <br>
                            <a href="dashboard.php?delete_heritage=<?= $heritage['heritage_id'] ?>" class="btn" style="background-color: red;">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <?php if ($edit_heritage_data): ?>
            <h3>Edit Heritage Site</h3>
            <div class="form-container">
                <form method="POST" action="" enctype="multipart/form-data">
                    <!-- Hidden field for heritage ID -->
                    <input type="hidden" name="heritage_id" value="<?= htmlspecialchars($edit_heritage_data['heritage_id']) ?>">
                    <input type="text" name="name" placeholder="Heritage Name" required value="<?= htmlspecialchars($edit_heritage_data['name']) ?>">
                    <input type="text" name="location" placeholder="Heritage Location" required value="<?= htmlspecialchars($edit_heritage_data['location']) ?>">
                    <textarea name="description" placeholder="Description" required><?= htmlspecialchars($edit_heritage_data['description']) ?></textarea>
                    <label for="image">Select Image (optional):</label>
                    <input type="file" name="image" id="image">
                    <button type="submit" name="edit_heritage">Update Heritage Site</button>
                </form>
            </div>
        <?php else: ?>
            <h3>Add New Heritage Site</h3>
            <div class="form-container">
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="text" name="name" placeholder="Heritage Name" required>
                    <input type="text" name="location" placeholder="Heritage Location" required>
                    <textarea name="description" placeholder="Description" required></textarea>
                    <label for="image">Select Image:</label>
                    <input type="file" name="image" id="image" required>
                    <button type="submit" name="add_heritage">Add Heritage Site</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Tourist Guides Section -->
    <div class="section">
        <h3>Manage Tourist Guides</h3>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($guide = mysqli_fetch_assoc($guides_result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($guide['name']) ?></td>
                        <td><?= htmlspecialchars($guide['email']) ?></td>
                        <td>
                            <a href="dashboard.php?delete_guide=<?= $guide['user_id'] ?>" class="btn" style="background-color: red;">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Add New Tourist Guide</h3>
        <div class="form-container">
            <form method="POST" action="">
                <input type="text" name="name" placeholder="Guide Name" required>
                <input type="email" name="email" placeholder="Guide Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="add_guide">Add Tourist Guide</button>
            </form>
        </div>
    </div>

</div>

</body>
</html>

<?php
mysqli_close($conn);
?>
