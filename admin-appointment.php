<?php
session_start();


if (!isset($_SESSION['email'])) {
    header("Location: login.php"); 
    exit();
}


$host = 'localhost';
$dbname = 'meru doctors plaza';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT profile_picture FROM members WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();


if (empty($profile_picture)) {
    $profile_picture = 'uploads/profile_pictures/default.png'; 
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Meru Doctors' Plaza Appointments</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
  <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        /* Notification Bell Styles */
        .notification-bell {
            position: relative;
            cursor: pointer;
            margin: 20px; /* Adjust for spacing */
            float: right; /* Align to the right */
        }

        .notification-bell i {
            font-size: 24px;
            color: #0056b3; /* Blue color for the bell icon */
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background: blue; /* Notification bubble color */
            color: white;
            border-radius: 50%;
            padding: 5px 8px;
            font-size: 14px;
            font-weight: bold;
        }

        /* Notification Dropdown */
        .notification-dropdown {
            display: none; /* Hidden by default */
            position: absolute;
            right: 0;
            background: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 1000; /* Above other elements */
            min-width: 200px; /* Minimum width */
            max-height: 300px; /* Max height */
            overflow-y: auto; /* Scroll if needed */
        }

        .notification-dropdown ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .notification-dropdown li {
            padding: 10px;
            border-bottom: 1px solid #ddd; /* Divider */
        }

        .notification-dropdown li:last-child {
            border-bottom: none; /* Remove last border */
        }

        .notification-dropdown li:hover {
            background: #f0f0f0; /* Highlight on hover */
        }
    </style>
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">
    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="d-none d-md-flex align-items-center">
          <i class="bi bi-phone me-1"></i>  Call us now +25470565626
        </div>
        <div class="d-flex align-items-center">
          <i class="bi bi-clock me-1"></i> Monday - Sunday, Open 24 Hours Services
        </div>
      </div>
    </div>

    <div class="branding d-flex align-items-center">
      <div class="container position-relative d-flex align-items-center justify-content-end">
        <br href="index.html" class="logo d-flex align-items-center me-auto">
          <img src="assets/img/testimonials/logo.png" alt="">
        </br>

        <nav id="navmenu" class="navmenu">
          <ul>
            <li><a href="admin-appointment.php" class="active">Appointments</a></li>
            <li><a href="admin-order.php">Orders</a></li>
            <li><a href="admin-messages.php">Messages</a></li>
            <li><a href="Register.php">Register new member</a></li>
            <li><a href="admin-email.php">Subsciber messaging</a></li>
            <div class="notification-bell" onclick="toggleNotifications()">
        <i class="fas fa-bell"></i>
        <div class="notification-count" id="notificationCount">0</div>
        <div class="notification-dropdown" id="notificationDropdown">
            <ul id="notificationList"></ul>
        </div>
    </div>
            
            <!-- Profile dropdown -->
            <li class="dropdown">
              <button class="dropdown-btn">
                <!-- Display profile picture -->
                <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" style="width: 30px; height: 30px; border-radius: 50%; vertical-align: middle;">
                Profile
              </button>
              <div class="dropdown-content">
                <a href="view_profile.php">View Profile</a>
                <a href="edit_profile.php">Edit Profile</a>
                <a href="change_password.php">Change Password</a>
                <a href="logout.php">Logout</a>
              </div>
            </li>
            
            <li><a href="members.php">Members</a></li>
          </ul>
          <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

      </div>
    </div>
  </header>

  <main class="main"><body class="index-page">
  <header id="header" class="header sticky-top">
    <!-- (header content remains the same) -->
  </header>

  
    <h1>Appointment List</h1>
    <table border="1">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>National ID</th>
          <th>Phone</th>
          <th>Appointment Date</th>
          <th>Department</th>
          <th>Doctor</th>
          <th>Message</th>
          <th>Created At</th>
          <th>Comment</th> <!-- New column for comments -->
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
  <?php include 'fetch_appointments.php'; ?>

  <!-- Display appointment data with comment form and delete button for each entry -->
  <?php
  foreach ($appointment as $appointment) {
      echo "<tr>";
      echo "<td>" . $appointment['id'] . "</td>";
      echo "<td>" . $appointment['name'] . "</td>";
      echo "<td>" . $appointment['national_id'] . "</td>";
      echo "<td>" . $appointment['phone'] . "</td>";
      echo "<td>" . $appointment['appointment_date'] . "</td>";
      echo "<td>" . $appointment['department'] . "</td>";
      echo "<td>" . $appointment['doctor'] . "</td>";
      echo "<td>" . $appointment['message'] . "</td>";
      echo "<td>" . $appointment['created_at'] . "</td>";

      echo "<td>";
      echo "<form method='POST' action='save_comment.php'>";
      echo "<textarea name='comment'>" . $appointment['comment'] . "</textarea>";
      echo "<input type='hidden' name='appointment_id' value='" . $appointment['id'] . "' />";
      echo "<button type='submit'>Save Comment</button>";
      echo "</form>";
      echo "</td>";

      // Add a delete form for each appointment
      echo "<td>";
      echo "<form method='POST' action='delete_appointment.php'>";
      echo "<input type='hidden' name='appointment_id' value='" . $appointment['id'] . "' />";
      echo "<button type='submit' onclick=\"return confirm('Are you sure you want to delete this appointment?');\">Delete</button>";
      echo "</form>";
      echo "</td>";

      echo "</tr>";
  }
  ?>
</tbody>

    </table>

  </main>

  <footer id="footer" class="footer light-background">
    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="index.html" class="logo d-flex align-items-center">
            <span class="sitename">Meru Doctors' Plaza</span>
          </a>
          <div class="footer-contact pt-3">
            <p>MERU</p>
            <p>Meru, I&M Building Second Floor, Meru, Eastern Province, 2828-60200, Meru, Eastern</p>
            <p class="mt-3"><strong>Phone:</strong> <span>+2542345678</span></p>
            <p><strong>Email:</strong> <span>merudoc@gmail.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">2024</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Designed by <a href="https://estonkiama.netlify.app/">Eston Kiama</a>
      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script>
        // Sample notification data (this should be fetched from your backend)
        let notifications = {
            newAppointments: 3,
            newOrders: 2,
            newMessages: 1,
            newSubscribers: 5,
        };

        // Function to update notification count and dropdown
        function updateNotificationCount() {
            const totalNotifications = notifications.newAppointments + notifications.newOrders + notifications.newMessages + notifications.newSubscribers;
            document.getElementById('notificationCount').innerText = totalNotifications;

            // Populate the notification dropdown
            const notificationList = document.getElementById('notificationList');
            notificationList.innerHTML = ''; // Clear existing notifications
            if (totalNotifications > 0) {
                notificationList.innerHTML += `<li>${notifications.newAppointments} new appointments</li>`;
                notificationList.innerHTML += `<li>${notifications.newOrders} new orders</li>`;
                notificationList.innerHTML += `<li>${notifications.newMessages} new messages</li>`;
                notificationList.innerHTML += `<li>${notifications.newSubscribers} new subscribers</li>`;
            } else {
                notificationList.innerHTML = '<li>No new notifications</li>';
            }
        }

        // Function to toggle the display of notifications
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
        }

        // Initial update of notification count
        updateNotificationCount();

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.notification-bell')) {
                document.getElementById('notificationDropdown').style.display = 'none';
            }
        }
    </script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>
</body>
</html>