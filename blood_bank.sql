-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2024 at 10:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blood_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_username` varchar(50) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_contact_no` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_username`, `admin_email`, `admin_password`, `admin_contact_no`) VALUES
(1, 'Gautam_321', 'gautam@gmail.com', '$2y$10$N4fhImMvRsDyDrwIwrKbu.supWkjUeYYIFeJIBCxSuMP20yZ3UFha', '9998887776'),
(2, 'riya_18', 'riya18@gmail.com', '$2y$10$EzdHjzgxJBBzwXU.awCYj.Q5z87HFLiUf4x71v4OhbCQi9L3eSqUC', '8899766557'),
(3, 'keyuri_23', 'keyuri23@gmail.com', '$2y$10$MAzmLNyQrmKHpkn.mUhWxOnV8OkQ7JDc7Pu/ZprLrO41bkM7Pew2K', '8899766556');

-- --------------------------------------------------------

--
-- Table structure for table `blood_request`
--

CREATE TABLE `blood_request` (
  `request_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
  `quantity_require` int(11) NOT NULL,
  `requested_date` date NOT NULL,
  `status` enum('Approved','Pending','Rejected','Completed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blood_request`
--

INSERT INTO `blood_request` (`request_id`, `hospital_id`, `blood_group`, `quantity_require`, `requested_date`, `status`) VALUES
(1, 1, 'AB+', 3, '2024-11-19', 'Approved'),
(2, 1, 'AB+', 5, '2024-11-19', 'Pending'),
(3, 1, 'A+', 1, '2024-11-19', 'Approved'),
(4, 1, 'A+', 100, '2024-11-30', 'Rejected'),
(5, 1, 'O+', 20, '2024-12-02', 'Pending'),
(6, 1, 'O+', 15, '2024-12-02', 'Pending'),
(7, 6, 'A-', 7, '2024-12-02', 'Pending'),
(8, 5, 'O-', 5, '2024-12-02', 'Pending'),
(9, 5, 'O+', 10, '2024-12-02', 'Pending'),
(10, 5, 'AB+', 2, '2024-12-02', 'Pending'),
(11, 5, 'A+', 5, '2024-12-02', 'Approved'),
(12, 5, 'A+', 12, '2024-12-27', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE `donation` (
  `donation_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `donation_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `updated_status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation`
--

INSERT INTO `donation` (`donation_id`, `donor_id`, `quantity`, `donation_date`, `expiry_date`, `updated_status`) VALUES
(1, 16, 1, '2024-12-02', '2024-01-06', 1),
(2, 15, 1, '2024-01-11', '2024-02-15', 1),
(3, 19, 2, '2024-11-28', '2025-01-02', 0),
(4, 23, 1, '2024-12-02', '2025-01-06', 0),
(5, 30, 2, '2024-12-06', '2025-01-10', 0),
(6, 28, 2, '2024-12-08', '2025-01-12', 0),
(7, 15, 2, '2024-12-08', '2025-01-12', 0),
(8, 16, 1, '2024-12-02', '2024-12-06', 1),
(9, 15, 1, '2024-01-11', '2024-02-15', 1),
(10, 19, 2, '2024-11-28', '2025-01-02', 0),
(11, 23, 1, '2024-12-02', '2025-01-06', 0),
(12, 30, 2, '2024-12-06', '2025-01-10', 0),
(13, 28, 2, '2024-12-08', '2025-01-12', 0),
(14, 15, 2, '2024-12-08', '2025-01-12', 0),
(15, 15, 2, '2024-11-11', '2024-12-16', 1),
(16, 26, 1, '2024-12-10', '2025-01-14', 0),
(17, 19, 1, '2024-12-10', '2025-01-14', 0),
(18, 23, 1, '2024-12-11', '2024-12-15', 1),
(21, 15, 1, '2024-12-11', '2025-01-15', 0),
(22, 23, 1, '2024-12-11', '2025-01-15', 0),
(23, 23, 1, '2024-12-11', '2025-01-15', 0),
(24, 23, 1, '2024-12-10', '2025-01-14', 0),
(25, 17, 1, '2024-12-12', '2025-01-16', 0),
(26, 18, 2, '2024-03-06', '2024-04-10', 1),
(27, 24, 1, '2024-06-12', '2024-07-17', 1),
(28, 25, 1, '2024-12-26', '2025-01-30', 0),
(29, 31, 1, '2024-01-01', '2024-02-05', 1),
(30, 31, 1, '2024-03-01', '2024-04-05', 1),
(31, 31, 1, '2024-05-01', '2024-06-05', 1),
(32, 31, 2, '2024-07-01', '2024-08-05', 1),
(33, 31, 1, '2024-09-01', '2024-10-06', 1),
(34, 31, 2, '2024-11-01', '2024-12-06', 1),
(35, 18, 1, '2024-12-27', '2025-01-31', 0),
(36, 24, 1, '2024-12-27', '2025-01-31', 0),
(37, 32, 2, '2024-12-27', '2025-01-31', 0),
(39, 33, 1, '2024-12-28', '2025-02-01', 0);

-- --------------------------------------------------------

--
-- Table structure for table `donor`
--

CREATE TABLE `donor` (
  `donor_id` int(11) NOT NULL,
  `donor_first_name` varchar(50) NOT NULL,
  `donor_middle_name` varchar(50) NOT NULL,
  `donor_last_name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `gender` enum('male','female','others') NOT NULL,
  `donor_blood_group` enum('A+','A-','B+','B-','O+','O-','AB+','AB-') NOT NULL,
  `donor_email` varchar(100) NOT NULL,
  `donor_phone_no` varchar(15) NOT NULL,
  `house_number` varchar(100) NOT NULL,
  `locality` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `pin_code` varchar(6) NOT NULL,
  `donor_status` enum('active','inactive','blacklisted') NOT NULL,
  `status_remark` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor`
--

INSERT INTO `donor` (`donor_id`, `donor_first_name`, `donor_middle_name`, `donor_last_name`, `dob`, `weight`, `gender`, `donor_blood_group`, `donor_email`, `donor_phone_no`, `house_number`, `locality`, `city`, `pin_code`, `donor_status`, `status_remark`) VALUES
(15, 'Ruchita', 'Sanjay', 'Chauhan', '2004-09-18', 45.00, 'female', 'A-', 'ruchi04ta@gmail.com', '7359252386', 'B3, Setubandh Park Soc., Opp. Bhagwati High School', 'Hirawadi', 'Ahmedabad', '382350', 'active', ''),
(16, 'Keyuri', 'Balubhai', 'Makwana', '2005-01-23', 45.70, 'female', 'B-', 'kryurimakwana7@gmail.com', '9725639392', 'A1, Rampur Apartments', 'Bawla', 'Ahmedabad', '382240', 'active', NULL),
(17, 'John', 'A.', 'Smith', '1985-06-15', 70.50, 'male', 'A+', 'john.smith@example.com', '9876543210', '123', 'Elm Street', 'New York', '10001', 'active', NULL),
(18, 'Emma', 'B.', 'Johnson', '1990-09-12', 55.00, 'female', 'O-', 'emma.johnson@example.com', '8765432109', '456', 'Oak Avenue', 'Los Angeles', '90001', 'active', NULL),
(19, 'Liam', 'C.', 'Williams', '1995-02-20', 65.30, 'male', 'B+', 'liam.williams@example.com', '7654321098', '789', 'Pine Crescent', 'Chicago', '60601', 'active', NULL),
(20, 'Olivia', 'D.', 'Brown', '1987-03-10', 50.20, 'female', 'AB+', 'olivia.brown@example.com', '6543210987', '321', 'Maple Lane', 'Houston', '77001', 'active', NULL),
(21, 'Noah', 'E.', 'Jones', '1988-11-05', 68.00, 'male', 'O+', 'noah.jones@example.com', '5432109876', '654', 'Birch Road', 'Phoenix', '85001', 'active', NULL),
(22, 'Sophia', 'F.', 'Garcia', '1992-07-22', 60.70, 'female', 'A-', 'sophia.garcia@example.com', '4321098765', '987', 'Cedar Boulevard', 'San Francisco', '94101', 'active', NULL),
(23, 'Gautam', 'Pravinbhai', 'Chauhan', '2005-07-23', 81.00, 'male', 'O+', 'chauhangautam23@gmail.com', '8153935673', 'A302, Saffron Binori Residency', 'Anandnagar, Satellite', 'Ahmedabad', '380015', 'active', NULL),
(24, 'Ruchita', 'Jignesh', 'Mahale', '1997-11-26', 67.00, 'female', 'AB-', 'ruchita09@gmail.com', '9988654673', 'B3, Setubandh Park Soc., Opp. Bhagwati High School', 'Hirawadi', 'Ahmedabad', '382240', 'active', NULL),
(25, 'John', 'A.', 'Smith', '1985-06-15', 70.50, 'male', 'A+', 'john.smith@example.com', '9876543210', '123', 'Elm Street', 'New York', '10001', 'active', NULL),
(26, 'Emma', 'B.', 'Johnson', '1990-09-12', 55.00, 'female', 'O-', 'emma.johnson@example.com', '8765432109', '456', 'Oak Avenue', 'Los Angeles', '90001', 'active', NULL),
(27, 'Liam', 'C.', 'Williams', '1995-02-20', 65.30, 'male', 'B+', 'liam.williams@example.com', '7654321098', '789', 'Pine Crescent', 'Chicago', '60601', 'active', NULL),
(28, 'Olivia', 'D.', 'Brown', '1987-03-10', 50.20, 'female', 'AB+', 'olivia.brown@example.com', '6543210987', '321', 'Maple Lane', 'Houston', '77001', 'active', NULL),
(29, 'Noah', 'E.', 'Jones', '1988-11-05', 68.00, 'male', 'O+', 'noah.jones@example.com', '5432109876', '654', 'Birch Road', 'Phoenix', '85001', 'active', NULL),
(30, 'Sophia', 'F.', 'Garcia', '1992-07-22', 60.70, 'female', 'A-', 'sophia.garcia@example.com', '4321098765', '987', 'Cedar Boulevard', 'San Francisco', '94101', 'active', NULL),
(31, 'Sanjay', 'Pitamber', 'Mahale', '1972-02-16', 64.00, 'male', 'A-', 'sanju16@gmail.com', '9992224676', 'B1, Simandher Recidency, Opp. Apple Cinema', 'Chandlodia', 'Ahmedabad', '380097', 'active', NULL),
(32, 'Manishaben', 'Sanjay', 'Mahale', '1979-01-17', 52.00, 'female', 'AB-', 'manisha11@gmail.com', '9954368444', 'B3, Setubandh Park Soc., Opp. Bhagwati High School', 'Bawla', 'Ahmedabad', '380015', 'active', NULL),
(33, 'Poonam', 'Pravinbai', 'Chauhan', '2005-12-03', 56.00, 'female', 'B-', 'poonam89@gmail.com', '8153935677', 'A302, Saffron binory Residency', 'Seema Hall, Anandnagar', 'ahmedabad', '380015', 'active', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `hospital_id` int(11) NOT NULL,
  `hospital_username` varchar(50) NOT NULL,
  `hospital_email` varchar(100) NOT NULL,
  `hospital_password` varchar(255) NOT NULL,
  `hospital_name` varchar(100) NOT NULL,
  `hospital_address` varchar(200) NOT NULL,
  `hospital_city` varchar(50) NOT NULL,
  `hospital_pin_code` varchar(6) NOT NULL,
  `hospital_contact_no` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`hospital_id`, `hospital_username`, `hospital_email`, `hospital_password`, `hospital_name`, `hospital_address`, `hospital_city`, `hospital_pin_code`, `hospital_contact_no`) VALUES
(1, 'arn_0111', 'aarna@gmail.com', 'abc@0111', 'Aarna Superspeciality Hospital', 'Hirawadi Road, Opp. Sunrise Complex, Ahmedabad', 'Ahmedabad', '338004', '9998887776'),
(5, 'rajasthan_001', 'rajasthan_hospital001@gmail.com', '$2y$10$hx9a2D78107XdppQupk/ReC1MbOLjNR2pBSzdNLqy1TZFyYVmELqW', 'Rajasthan Hospital', 'Camp Road, Shahibaug', 'Ahmedabad', '380004', '9997765431'),
(6, 'nidhi111', 'nidhi_hospital@gmail.com', '$2y$10$DujfIQMA62mTqaOpkySKOuUumZRTWodt9Jb9by7FeW4t.VWWmORAe', 'Nidhi Hospital', 'Nr. Stadium Cross Road, Navrangpura', 'Ahmedabad', '380009', '9874653678'),
(7, 'cims_23', 'cims_hospital@yahoo.com', '$2y$10$OALZa1nZGQ.QExXm9w3asOPREEovQqgWUA9zk0y8pcEUF0R.7pufG', 'Cims Hospital', 'Nr. Shukan Mall, Opp. Science City Road, Sola', 'Ahmedabad', '380060', '9997765434'),
(8, 'sal_99', 'sal_hospital99@gmail.com', '$2y$10$HRcl0huxR1P05RkQQf4aR.JV0CJB39kpxgp2Q18MsyNbSvf6Ms79u', 'Sal Hospital', 'Opp. Durdarshan, Drive in Road', 'Ahmedabad', '380054', '7865439901');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `blood_group` varchar(3) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `blood_group`, `quantity`) VALUES
(1, 'A+', 2),
(2, 'A-', 9),
(3, 'B+', 5),
(4, 'B-', 1),
(5, 'O+', 5),
(6, 'O-', 2),
(7, 'AB+', 4),
(8, 'AB-', 3);

-- --------------------------------------------------------

--
-- Table structure for table `supply`
--

CREATE TABLE `supply` (
  `supply_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `supply_date_time` datetime NOT NULL,
  `supply_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `blood_request`
--
ALTER TABLE `blood_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `donor`
--
ALTER TABLE `donor`
  ADD PRIMARY KEY (`donor_id`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`hospital_id`),
  ADD UNIQUE KEY `hospital_email` (`hospital_email`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`);

--
-- Indexes for table `supply`
--
ALTER TABLE `supply`
  ADD PRIMARY KEY (`supply_id`),
  ADD KEY `request_id` (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blood_request`
--
ALTER TABLE `blood_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
  MODIFY `donation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `donor`
--
ALTER TABLE `donor`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `hospital`
--
ALTER TABLE `hospital`
  MODIFY `hospital_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `supply`
--
ALTER TABLE `supply`
  MODIFY `supply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blood_request`
--
ALTER TABLE `blood_request`
  ADD CONSTRAINT `blood_request_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospital` (`hospital_id`);

--
-- Constraints for table `donation`
--
ALTER TABLE `donation`
  ADD CONSTRAINT `donation_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donor` (`donor_id`);

--
-- Constraints for table `supply`
--
ALTER TABLE `supply`
  ADD CONSTRAINT `supply_ibfk_1` FOREIGN KEY (`request_id`) REFERENCES `blood_request` (`request_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
