<?php
$receiving_email_address = 'engestonbrandon@gmail.com';


if (!file_exists($php_email_form = '../assets/vendor/php-email-form/php-email-form.php')) {
    die(json_encode(['status' => 'error', 'message' => 'Unable to load the "PHP Email Form" Library!']));
}

include($php_email_form);

$contact = new PHP_Email_Form;
$contact->ajax = true;
$contact->to = $receiving_email_address;
$contact->subject = 'New Appointment Request';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(strip_tags($_POST['name']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(strip_tags($_POST['phone']));
    $date = htmlspecialchars(strip_tags($_POST['date']));
    $department = htmlspecialchars(strip_tags($_POST['department']));
    $doctor = htmlspecialchars(strip_tags($_POST['doctor']));
    $message = htmlspecialchars(strip_tags($_POST['message']));

    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($department) || empty($doctor)) {
        die(json_encode(['status' => 'error', 'message' => 'All fields except message are required!']));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die(json_encode(['status' => 'error', 'message' => 'Invalid email format!']));
    }

    
    $contact->from_name = $name;
    $contact->from_email = $email;
    $contact->add_message($name, 'Name');
    $contact->add_message($email, 'Email');
    $contact->add_message($phone, 'Phone');
    $contact->add_message($date, 'Appointment Date');
    $contact->add_message($department, 'Department');
    $contact->add_message($doctor, 'Doctor');
    $contact->add_message($message, 'Message (Optional)');

   
    if ($contact->send()) {
        echo json_encode(['status' => 'success', 'message' => 'Your appointment request has been sent successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to send the appointment request. Try again later.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
?>
