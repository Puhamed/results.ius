<?php
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

// Function to fetch student data
function fetchStudentData($student_no) {
    $conn = getDatabaseConnection();
    $sql = "SELECT `Year`, `Session`, `student_name`, `Roll_ID`, `Student_NO`, `Note`, 
            `Subject1`, `Mark1`, `Subject2`, `Mark2`, `Subject3`, `Mark3`, 
            `Subject4`, `Mark4`, `Subject5`, `Mark5`, `Subject6`, `Mark6`, 
            `Subject7`, `Mark7`, `Subject8`, `Mark8` 
            FROM `readings` WHERE Student_NO = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $student_data = [];
    if ($result->num_rows > 0) {
        $student_data = $result->fetch_assoc();
    } else {
        $student_data = null; // No records found
    }
    
    $stmt->close();
    $conn->close();
    return $student_data;
}

// Initialize variables
$student_no = '';
$student_data = null;

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_no = trim($_POST['student_no']);
    $student_data = fetchStudentData($student_no);
}
?>

<!DOCTYPE html>
<html lang="ar" dir="RTL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>درجات طلبة علوم القرآن</title>
</head>
<body>
    <h1>نتيجة قسم القرآءات القرآنية</h1>
    <form method="post" action="">
        <label for="student_no">رقم الطالب:</label>
        <input type="text" id="student_no" name="student_no" required>
        <input type="submit" value="تأكيد">
    </form>
    
    <?php if ($student_data): ?>
        <h2>درجات الطالب</h2>
        <p><strong>السنة:</strong> <?php echo htmlspecialchars($student_data['Year']); ?></p>
        <p><strong>الفصل:</strong> <?php echo htmlspecialchars($student_data['Session']); ?></p>
        <p><strong>اسم الطالب:</strong> <?php echo htmlspecialchars($student_data['student_name']); ?></p>
        <p><strong>الرقم الوطني:</strong> <?php echo htmlspecialchars($student_data['Roll_ID']); ?></p>
        <p><strong>ملاحظات:</strong> <?php echo htmlspecialchars($student_data['Note']); ?></p>

        <h3>المواد والدرجات</h3>
<ul>
    <?php 
    for ($i = 1; $i <= 8; $i++): 
        if (!empty($student_data["Subject$i"]) && !empty($student_data["Mark$i"])): ?>
            <li>
                <strong><?php echo htmlspecialchars($student_data["Subject$i"]); ?></strong>: <?php echo htmlspecialchars($student_data["Mark$i"]); ?>
            </li>
        <?php endif; 
    endfor; ?>
</ul>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>لا توجد سجلات للطالب برقم: <?php echo htmlspecialchars($student_no); ?></p>
    <?php endif; ?>
</body>
</html>
