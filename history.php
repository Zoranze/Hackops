<?php
// Database configuration - REPLACE with your actual credentials
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password
$dbname = "diabetic_recognition";

$results = [];
$search_query = "";
$message = "";

// Check if a search query has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_query'])) {
    $search_query = trim($_POST['search_query']);

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        $message = "Connection failed: " . $conn->connect_error;
    } else {
        // SQL query to search for patient information and images.
        $sql = "SELECT p.name, p.diabetic, i.file_path, i.uploaded_at
                FROM patient p
                LEFT JOIN images i ON p.patientId = i.patientId
                WHERE p.name LIKE ? OR p.patientId LIKE ?";

        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            $message = 'Prepare failed: ' . $conn->error;
        } else {
            // Sanitize the search query to prevent SQL injection
            $search_param = "%" . $search_query . "%";
            $stmt->bind_param("ss", $search_param, $search_param);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $results[] = [
                        'patient_name' => $row['name'],
                        'picture_url' => !empty($row['file_path']) ? $row['file_path'] : 'https://placehold.co/100x100/AAAAAA/FFF?text=No+Img',
                        'diabetic' => $row['diabetic'] ? 'Yes' : 'No',
                        'history_uploaded' => !empty($row['uploaded_at']) ? date('Y-m-d', strtotime($row['uploaded_at'])) : 'N/A',
                    ];
                }
            } else {
                $message = "No history found for '" . htmlspecialchars($search_query) . "'.";
            }
            $stmt->close();
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 900px;
            text-align: center;
        }
        h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }
        .search-form label {
            font-size: 1.2em;
            margin-right: 15px;
            color: #555;
        }
        .search-form input[type="text"] {
            padding: 10px;
            font-size: 1em;
            border: 2px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }
        .search-form button {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .search-form button:hover {
            background-color: #0056b3;
        }
        .results-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .patient-card {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            text-align: center;
        }
        .patient-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .patient-card h3 {
            margin: 0 0 5px;
            color: #333;
        }
        .patient-card p {
            margin: 0;
            color: #666;
            font-size: 0.9em;
        }
        .message {
            margin-top: 20px;
            font-size: 1.2em;
            color: #d9534f;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Patient History</h1>

    <form class="search-form" id="search-form" method="POST" action="history.php">
        <label for="search_query">View a patient history</label>
        <input type="text" id="search_query" name="search_query" placeholder="Enter patient name or ID" required>
        <button type="submit">Search</button>
    </form>

    <div id="results-container">
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php elseif (!empty($results)): ?>
            <h2>Search Results</h2>
            <div class="results-grid">
                <?php foreach ($results as $item): ?>
                    <div class="patient-card">
                        <img src="<?php echo htmlspecialchars($item['picture_url']); ?>" alt="Patient Picture">
                        <h3><?php echo htmlspecialchars($item['patient_name']); ?></h3>
                        <p><strong>Diabetic:</strong> <?php echo htmlspecialchars($item['diabetic']); ?></p>
                        <p><strong>History Uploaded:</strong> <?php echo htmlspecialchars($item['history_uploaded']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
