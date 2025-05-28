<?php
// Include database configuration
include 'includes/config.php';

// SQL to add profile_image column to users table if it doesn't exist
$sql = "
    SELECT COLUMN_NAME 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = '" . DB_NAME . "' 
    AND TABLE_NAME = 'users' 
    AND COLUMN_NAME = 'profile_image'
";

try {
    // Check if the column already exists
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    if ($stmt->rowCount() == 0) {
        // Column doesn't exist, so add it
        $alter_sql = "ALTER TABLE `users` ADD COLUMN `profile_image` VARCHAR(255) NULL AFTER `user_type`";
        $conn->exec($alter_sql);
        echo "<div style='background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 20px;'>
                <h3>Success!</h3>
                <p>The 'profile_image' column has been added to the 'users' table.</p>
                <p><a href='patient/profile.php'>Go to Profile Page</a></p>
              </div>";
    } else {
        echo "<div style='background-color: #cce5ff; color: #004085; padding: 15px; border-radius: 5px; margin: 20px;'>
                <h3>Information</h3>
                <p>The 'profile_image' column already exists in the 'users' table.</p>
                <p><a href='patient/profile.php'>Go to Profile Page</a></p>
              </div>";
    }
} catch (PDOException $e) {
    echo "<div style='background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 20px;'>
            <h3>Error</h3>
            <p>Database error: " . $e->getMessage() . "</p>
          </div>";
}
?>
