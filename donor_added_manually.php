<?php
require_once("conn.php"); 

$donors = [
    [
        'fname' => 'John',
        'mname' => 'A.',
        'lname' => 'Smith',
        'dob' => '1985-06-15',
        'weight' => 70.5,
        'gender' => 'Male',
        'blood_group' => 'A+',
        'email' => 'john.smith@example.com',
        'phone_no' => '9876543210',
        'house_number' => '123',
        'locality' => 'Elm Street',
        'city' => 'New York',
        'pin_code' => '10001'
    ],
    [
        'fname' => 'Emma',
        'mname' => 'B.',
        'lname' => 'Johnson',
        'dob' => '1990-09-12',
        'weight' => 55.0,
        'gender' => 'Female',
        'blood_group' => 'O-',
        'email' => 'emma.johnson@example.com',
        'phone_no' => '8765432109',
        'house_number' => '456',
        'locality' => 'Oak Avenue',
        'city' => 'Los Angeles',
        'pin_code' => '90001'
    ],
    [
        'fname' => 'Liam',
        'mname' => 'C.',
        'lname' => 'Williams',
        'dob' => '1995-02-20',
        'weight' => 65.3,
        'gender' => 'Male',
        'blood_group' => 'B+',
        'email' => 'liam.williams@example.com',
        'phone_no' => '7654321098',
        'house_number' => '789',
        'locality' => 'Pine Crescent',
        'city' => 'Chicago',
        'pin_code' => '60601'
    ],
    [
        'fname' => 'Olivia',
        'mname' => 'D.',
        'lname' => 'Brown',
        'dob' => '1987-03-10',
        'weight' => 50.2,
        'gender' => 'Female',
        'blood_group' => 'AB+',
        'email' => 'olivia.brown@example.com',
        'phone_no' => '6543210987',
        'house_number' => '321',
        'locality' => 'Maple Lane',
        'city' => 'Houston',
        'pin_code' => '77001'
    ],
    [
        'fname' => 'Noah',
        'mname' => 'E.',
        'lname' => 'Jones',
        'dob' => '1988-11-05',
        'weight' => 68.0,
        'gender' => 'Male',
        'blood_group' => 'O+',
        'email' => 'noah.jones@example.com',
        'phone_no' => '5432109876',
        'house_number' => '654',
        'locality' => 'Birch Road',
        'city' => 'Phoenix',
        'pin_code' => '85001'
    ],
    [
        'fname' => 'Sophia',
        'mname' => 'F.',
        'lname' => 'Garcia',
        'dob' => '1992-07-22',
        'weight' => 60.7,
        'gender' => 'Female',
        'blood_group' => 'A-',
        'email' => 'sophia.garcia@example.com',
        'phone_no' => '4321098765',
        'house_number' => '987',
        'locality' => 'Cedar Boulevard',
        'city' => 'San Francisco',
        'pin_code' => '94101'
    ],
];

foreach ($donors as $donor) {
    $query = "INSERT INTO donor (
                donor_first_name, donor_middle_name, donor_last_name, dob, weight, gender, 
                donor_blood_group, donor_email, donor_phone_no, house_number, locality, city, pin_code
            ) VALUES (
                '" . mysqli_real_escape_string($conn, $donor['fname']) . "',
                '" . mysqli_real_escape_string($conn, $donor['mname']) . "',
                '" . mysqli_real_escape_string($conn, $donor['lname']) . "',
                '" . mysqli_real_escape_string($conn, $donor['dob']) . "',
                " . $donor['weight'] . ",
                '" . mysqli_real_escape_string($conn, $donor['gender']) . "',
                '" . mysqli_real_escape_string($conn, $donor['blood_group']) . "',
                '" . mysqli_real_escape_string($conn, $donor['email']) . "',
                '" . mysqli_real_escape_string($conn, $donor['phone_no']) . "',
                '" . mysqli_real_escape_string($conn, $donor['house_number']) . "',
                '" . mysqli_real_escape_string($conn, $donor['locality']) . "',
                '" . mysqli_real_escape_string($conn, $donor['city']) . "',
                '" . mysqli_real_escape_string($conn, $donor['pin_code']) . "'
            )";

    if (mysqli_query($conn, $query)) {
        echo "Donor " . $donor['fname'] . " added successfully.<br>";
    } else {
        echo "Error adding donor " . $donor['fname'] . ": " . mysqli_error($conn) . "<br>";
    }
}

mysqli_close($conn);
?>
