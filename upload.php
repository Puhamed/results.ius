<?php
require 'vendor/autoload.php'; // Include PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

// Database connection constants
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Change as per your database credentials
define('DB_PASSWORD', ''); // Change as per your database credentials
define('DB_NAME', 'studentsmarks'); // Change to your database name

// Function to establish a database connection
function getDatabaseConnection() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check if a file was uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file']['tmp_name'];

    // Load the spreadsheet
    $spreadsheet = IOFactory::load($file);
    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

    // Establish database connection
    $conn = getDatabaseConnection();

    // Remove old data
    $conn->query("DELETE FROM studentmark");

    // Prepare the insert statement
    $stmt = $conn->prepare("INSERT INTO studentmark (Year, Session, student_name, Roll_ID, Student_NO, Note, Subject1, Mark1, Subject2, Mark2, Subject3, Mark3, Subject4, Mark4, Subject5, Mark5, Subject6, Mark6, Subject7, Mark7, Subject8, Mark8) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Loop through the rows of the spreadsheet
    foreach ($sheetData as $row) {
        // Skip the header row
        if ($row['A'] == 'Year') {
            continue;
        }

        // Bind parameters
        $stmt->bind_param("ssssssssssssssssssssss",
            $row['A'], $row['B'], $row['C'], $row['D'], $row['E'], $row['F'],
            $row['G'], $row['H'], $row['I'], $row['J'], $row['K'], $row['L'],
            $row['M'], $row['N'], $row['O'], $row['P'], $row['Q'], $row['R'],
            $row['S'], $row['T'], $row['U']
        );

        // Execute the statement
        $stmt->execute();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

    echo "تم رفع البيانات بنجاح!";
} else {
    echo "فشل في رفع الملف.";
}
?>
