<?php
$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$searchResults = [];
if (isset($_GET['query'])) {
    $query = $conn->real_escape_string($_GET['query']);
    $sql = "SELECT * FROM business_hours WHERE business_name LIKE '%$query%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $searchResults[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meru Doctors Plaza Search Results</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Search Results</h1>
        <form action="" method="GET" class="mb-4">
            <input type="text" name="query" placeholder="Search for a business..." required class="p-2 border rounded">
            <button type="submit" class="bg-blue-500 text-white p-2 rounded">Search</button>
        </form>
        <div class="flex flex-col md:flex-row">
            <div class="w-full md:w-2/3 pr-0 md:pr-4 mb-4 md:mb-0">
                <div id="searchResults" class="space-y-4">
                    <?php if (empty($searchResults)): ?>
                        <p>No results found.</p>
                    <?php else: ?>
                        <?php foreach ($searchResults as $index => $result): ?>
                            <div class="bg-white p-4 rounded shadow-md">
                                <h2 class="text-xl font-semibold mb-2">
                                    <a href="#" class="text-blue-600 hover:underline" onmouseover="showSideInfo(<?php echo $index; ?>)"><?php echo htmlspecialchars($result['business_name']); ?></a>
                                </h2>
                                <p class="text-gray-600"><?php echo htmlspecialchars($result['address']); ?></p>
                                
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="w-full md:w-1/3">
                <div id="sideInfo" class="bg-white p-4 rounded shadow-md sticky top-4">
                   
                </div>
            </div>
        </div>
    </div>
    <script>
        const searchResults = <?php echo json_encode($searchResults); ?>;

        function showSideInfo(index) {
            const sideInfoContainer = document.getElementById('sideInfo');
            const info = searchResults[index];
            if (info) {
                sideInfoContainer.innerHTML = `
                    <h3 class="text-lg font-semibold mb-2">${info.business_name}</h3><iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15959.268333963459!2d37.655057!3d0.0451149!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x178821ea25e67a7d%3A0xcaef0f8a3fc108b0!2sMeru%20Doctors%20Plaza%20Ltd!5e0!3m2!1sen!2ske!4v1707987751672!5m2!1sen!2ske" width="500" height="700" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <p><strong>Address:</strong>  
     ${info.address}</p>
                    <p><strong>Phone:</strong> ${info.phone_number}</p>
                    <p><strong>Open Hours:</strong> ${info.open_hours}</p>
                `;
            } else {
                sideInfoContainer.innerHTML = '<p>No additional information available.</p>';
            }
        }

       
        if (searchResults.length > 0) {
            showSideInfo(0);
        }
    </script>
</body>
</html>