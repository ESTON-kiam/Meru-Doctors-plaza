<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start(); 
}

$timeout_duration = 1800; 


if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > $timeout_duration) {
        session_unset(); 
        session_destroy();
        header("Location: http://localhost:8000/admin/");
        exit();
    }
}

$_SESSION['last_activity'] = time();


if (!isset($_SESSION['email'])) {
    header("Location: http://localhost:8000/admin/");
    exit();
}
?>

<?php



if (!isset($_SESSION['email'])) {
    header("Location: http://localhost:8000/admin/"); 
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
?><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Meru Doctors' Plaza Orders</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

 
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/favicon.png" rel="apple-touch-icon">


  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">

  
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  
  <link href="assets/css/main.css" rel="stylesheet">
  <link href="assets/css/notifi.css" rel="stylesheet">
  <script>
      function printOrders() {
          var printContents = document.getElementById('ordersTable').outerHTML;
          var newWindow = window.open('', '', 'width=600,height=400');
          newWindow.document.write('<html><head><title>Print Orders</title>');
          newWindow.document.write('</head><body>');
          newWindow.document.write(printContents);
          newWindow.document.write('</body></html>');
          newWindow.document.close(); 
          newWindow.print();
          newWindow.close();
      }
  </script>
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">

    <div class="topbar d-flex align-items-center">
      <div class="container d-flex justify-content-center justify-content-md-between">
        <div class="d-none d-md-flex align-items-center">
          <i class="bi bi-phone me-1"></i> Call us now +25470565626
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
            <li><a href="admin-appointment.php" >Appointments</a></li>
            <li><a href="admin-order.php">Orders</a></li>
            <li><a href="admin-messages.php">Messages</a></li>
            <li><a href="Register.php">Register new member</a></li>
            <li><a href="admin-email.php">Subsciber messaging</a></li>
         
            
            
            <li class="dropdown">
              <button class="dropdown-btn">
                
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

  <main class="main">
    <div class="container mt-5">
      <h2>Order List</h2>
      <button class="btn btn-primary mb-3" onclick="printOrders()">Print Orders</button>
      <table class="table table-bordered" id="ordersTable" border="1">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>The Orders</th>
            <th>Order_Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php include 'fetch_order.php'; ?>
        </tbody>
      </table>
    </div>
  </main>

  <footer id="footer" class="footer light-background">
    

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename"><?php echo date("Y"); ?></strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        Designed by <a href="https://estonkiama.netlify.app/">Eston Kiama</a>
      </div>
    </div>
  </footer>


  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

 
  <div id="preloader"></div>

 
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <script src="assets/js/main.js"></script>
 
</body>

</html>