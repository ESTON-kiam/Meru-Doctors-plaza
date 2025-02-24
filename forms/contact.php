<?php

$receiving_email_address = 'engestonbrandonkiama@gmail.com';


if (file_exists($php_email_form = '')) {
    include($php_email_form);
} else {
    die('Unable to load the "PHP Email Form" Library!');
}

$contact = new PHP_Email_Form;
$contact->ajax = true;

$contact->to = $receiving_email_address;
$contact->from_name = filter_input(INPUT_POST, 'name');
$contact->from_email = filter_input(INPUT_POST, 'email');
$contact->subject = filter_input(INPUT_POST, 'subject');


$contact->smtp = array(
    'host' => 'smtp.gmail.com',
    'username' => 'engestonbrandon@gmail.com',
    'password' => 'pxmh wzte wcuy adnc', 
    'port' => '587',
    'encryption' => 'tls'
);

$contact->add_message($contact->from_name, 'From');
$contact->add_message($contact->from_email, 'Email');
$contact->add_message(filter_input(INPUT_POST, 'message'), 'Message', 10);

if ($contact->send()) {
    echo 'Message sent successfully!';
} else {
    echo 'Error sending message. Please try again later.';
}

?>
