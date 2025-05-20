-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 01:27 AM
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
-- Database: `carrentalsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `CarID` int(11) NOT NULL,
  `Model` varchar(100) NOT NULL,
  `Year` int(11) NOT NULL,
  `PlateID` varchar(20) NOT NULL,
  `Status` enum('Active','Out of Service','Rented') DEFAULT 'Active',
  `OfficeID` int(11) DEFAULT NULL,
  `Type` varchar(50) NOT NULL,
  `PricePerDay` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`CarID`, `Model`, `Year`, `PlateID`, `Status`, `OfficeID`, `Type`, `PricePerDay`, `image_url`) VALUES
(4, 'Mercedes Mclaren SLR', 2002, 'ABC123', 'Active', 1, 'sport', 1300.00, 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/76/Mercedes-Benz_SLR_McLaren_%288615164079%29.jpg/1920px-Mercedes-Benz_SLR_McLaren_%288615164079%29.jpg'),
(5, 'Bugatti Chiron', 2024, 'DEF456', 'Active', 1, 'sport', 2600.00, 'https://images.hgmsites.net/hug/bugatti-chiron-first-drive_100596770_h.jpg'),
(6, 'Koeingsegg Jesko', 2021, 'GHI789', 'Active', 1, 'sport', 2450.00, 'https://images.squarespace-cdn.com/content/v1/6371cc49cda4fd302dde5ccd/efb91f6e-3273-4f85-8eb9-1c525f13eb72/Jesko+Odin+Front.jpg'),
(7, 'Pagani Huayra', 2019, 'JKL012', 'Active', 1, 'sport', 2160.00, 'https://www.topgear.com/sites/default/files/cars-car/carousel/2016/08/rh_huayrabc-67.jpg'),
(8, 'BMW M3', 2005, 'MNO345', 'Active', 1, 'Sedan', 820.00, 'https://cdn.motor1.com/images/mgl/eoKjQP/s3/bmw-m3-gtr-from-2005-s-need-for-speed-most-wanted.webp'),
(9, 'Aston Martin DB11', 2016, 'PQR678', 'Active', 1, 'sport', 1700.00, 'https://www.stratstone.com/-/media/stratstone/aston-martin/models/inline-images/db11/reviews/road-test/amr/aston-martin-db11-amr-720x405px.ashx?'),
(11, 'Porsche 911', 2015, 'VWX234', 'Active', 1, 'sport', 1670.00, 'https://img.wprost.pl/img/nowe-porsche-911-gt3-r-rennsport-nie-wystarczy-milion-dolarow/44/e7/2b54e77606b31bc4b2e7f42dabf0.webp'),
(13, 'Brilliance V6', 2019, 'BCD890', 'Active', 1, 'SUV', 910.00, 'https://i1.autocango.com/spec/771182e6dad29b4676cbc263ffcc89ef95f498d711f0ed9c14f3c2e8854cbe10.webp'),
(14, 'Ferrari F90', 2021, 'EFG123', 'Active', 1, 'sport', 1320.00, 'https://www.topgear.com/sites/default/files/cars-car/carousel/2020/07/dsc09285.jpg'),
(15, 'Mercedes G Class', 2022, 'HIJ456', 'Active', 1, 'SUV', 1100.00, 'https://exfordrentacar.com/program/images/products/1706532243imageFile.jpg'),
(16, 'lamborghini aventador svj', 2018, 'KLM789', 'Active', 1, 'sport', 1550.00, 'https://www.wrapstyle.com/content/img_cache/1200x675/1663328332-2068-Lamborghini-Aventador-SVJ-Stripes-Design-Wrapstyle-1.jpeg'),
(17, 'RollsRoyce Ghost', 2019, 'NOP012', 'Active', 1, 'Sedan', 1290.00, 'https://www.barchemagazine.com/wp-content/uploads/2022/05/P90442130-rolls-royce-announce-800x600.jpg'),
(18, 'Dodge Hellcat', 2017, 'QRS345', 'Active', 1, 'Sedan', 710.00, 'https://hips.hearstapps.com/hmg-prod/images/2019-dodge-challenger-srt-hellcat-redeye-comparison-104-1581425446.jpg'),
(21, 'Lamborghini URUS', 2017, 'ZAB234', 'Active', 1, 'SUV', 840.00, 'https://www.topgear.com/sites/default/files/2024/10/1-Lamborghini-Urus-SE-review-2024.jpg'),
(22, 'BMW X7', 2023, 'CDE567', 'Active', 1, 'SUV', 640.00, 'https://www.carscoops.com/wp-content/uploads/2022/03/Manhart-MHX7-650-a.jpg'),
(23, 'Porsche 918 Spyder', 2014, 'FGH890', 'Active', 1, 'sport', 950.00, 'https://www.pcarmarket.com/static/media/uploads/galleries/photos/uploads/galleries/2015-918-spyder-martini-livery/.thumbnails/7539698F-CC7C-_hxuSWvc.jpg/7539698F-CC7C-_hxuSWvc-tiny-2048x0-0.5x0.jpg'),
(35, 'Opel', 234, '342', 'Active', NULL, '22', 222.00, 'https://ellaithy.com.eg/wp-content/uploads/2021/08/1627819835_326_163851_whatsappimage20210801at1.58.05pm.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Address` varchar(255) DEFAULT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL,
  `RegistrationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`CustomerID`, `FirstName`, `LastName`, `Email`, `PasswordHash`, `PhoneNumber`, `Address`, `City`, `Country`, `RegistrationDate`) VALUES
(1, 'Moustafa', 'mohamed', 'moustafa.mohamedelsayed0@gmail.com', '$2y$10$S.FQ9x7AY89XSFUchbE7EeUUN/9gkZWAniuE/DYtYSApasexcSIb6', NULL, NULL, NULL, NULL, '2024-12-30 17:27:51'),
(2, 'omar', 'tallal', 'omartallal@emaill.com', '$2y$10$k5r9JUH.Y0Cv60CDNBpTb.XpqzuUp4HV0oYxdpIzHRApjqidCnM1K', NULL, NULL, NULL, NULL, '2024-12-30 17:32:58'),
(6, 'ahmed', 'Mohamed', 'ahmedmohamed@gmail.com', '$2y$10$R.2hzDtngUgiod8yV.KcxuD0K9nPPvMccR0vBIJkvJmshFIB73cze', NULL, NULL, NULL, NULL, '2024-12-30 21:14:43'),
(7, 'aya', 'moustafa', 'aya@gmail.com', '$2y$10$XLWwVaWzMqQkZsLOy0z8xOgrCg1cyZf1651gSUX6Bh4u5syhigX9.', NULL, NULL, NULL, NULL, '2024-12-31 07:35:54'),
(8, 'abanoub', 'hany', 'abanoub@gmail.com', '$2y$10$kqnS1iJWWMpLXyeP45NJaOrLF8Kj/ZyrVUA1/v9MGZAKs1jD/8ItW', NULL, NULL, NULL, NULL, '2024-12-31 08:42:23'),
(9, 'Mohamed', 'Elsayed', 'mohamed.elsayed@gmail.com', '$2y$10$0eVKAYhWJnWZYBoEraM4KO4ksGzPWY33OZIhChWMFONcW3KdeB3X.', NULL, NULL, NULL, NULL, '2024-12-31 13:17:59'),
(10, 'Ali', 'Ali', 'alihassanmagix2017@gmail.com', '$2y$10$fJKLdJxz1dxMUSa0/RMvsuLJc5TNLzGdBEeWJpMwt05j2x8/wJoQm', NULL, NULL, NULL, NULL, '2025-05-01 11:04:28');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `EmployeeID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `user` varchar(50) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `RegistrationDate` datetime DEFAULT current_timestamp(),
  `isAdmin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`EmployeeID`, `name`, `user`, `PasswordHash`, `RegistrationDate`, `isAdmin`) VALUES
(6, 'joe', 'joe', '$2y$10$YK6/DT.KNMIm8BIThE6/1.4mBm1EJ/QKCVICf8JerboEWHToccBce', '2025-05-18 22:31:46', 1);

-- --------------------------------------------------------

--
-- Table structure for table `offices`
--

CREATE TABLE `offices` (
  `OfficeID` int(11) NOT NULL,
  `OfficeName` varchar(100) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `City` varchar(100) DEFAULT NULL,
  `Country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offices`
--

INSERT INTO `offices` (`OfficeID`, `OfficeName`, `Address`, `City`, `Country`) VALUES
(1, 'Downtown Office', '123 Main St', 'Alexandria', 'Egypt'),
(2, 'Airport Office', '456 Airport Blvd', 'Cairo', 'Egypt');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `ReservationID` int(11) NOT NULL,
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Amount` decimal(10,2) NOT NULL,
  `PaymentMethod` enum('Credit Card','Debit Card','Cash','Bank Transfer') NOT NULL,
  `CardNumber` varchar(19) NOT NULL,
  `ExpirationDate` varchar(5) NOT NULL,
  `CVC` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`PaymentID`, `ReservationID`, `PaymentDate`, `Amount`, `PaymentMethod`, `CardNumber`, `ExpirationDate`, `CVC`) VALUES
(8, 13, '2024-12-30 20:33:18', 51840.00, 'Credit Card', '4567456745674567', '10/29', '456'),
(9, 14, '2024-12-30 20:39:47', 3280.00, 'Credit Card', '4567456745674567', '10/29', '456'),
(10, 15, '2024-12-30 21:23:53', 2560.00, 'Credit Card', '7894789478947894', '11/27', '437'),
(11, 16, '2024-12-31 07:19:36', -2600.00, 'Credit Card', '88888888888888', '5855', '555'),
(13, 18, '2024-12-31 07:28:32', 7750.00, 'Credit Card', '99999999999999', '2222', '555'),
(14, 19, '2024-12-31 07:29:35', 2850.00, 'Credit Card', '154661999588845', '1020', '541'),
(15, 20, '2024-12-31 07:33:53', 8500.00, 'Credit Card', '5674567456744555', '10/29', '156'),
(16, 21, '2024-12-31 08:47:05', 5200.00, 'Credit Card', '4567456745674567', '10/29', '456'),
(17, 22, '2024-12-31 09:05:51', 5280.00, 'Credit Card', '1234123412341234', '10/29', '456'),
(18, 23, '2024-12-31 10:01:24', -15060500.00, 'Credit Card', '3', 'w', 'e');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `ReservationID` int(11) NOT NULL,
  `CustomerID` int(11) NOT NULL,
  `CarID` int(11) NOT NULL,
  `ReservationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `StartDate` date NOT NULL,
  `EndDate` date NOT NULL,
  `Status` enum('Reserved','Picked Up','Returned','Cancelled') DEFAULT 'Reserved',
  `CarModel` varchar(255) DEFAULT NULL,
  `PlateID` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`ReservationID`, `CustomerID`, `CarID`, `ReservationDate`, `StartDate`, `EndDate`, `Status`, `CarModel`, `PlateID`) VALUES
(13, 1, 7, '2024-12-30 20:33:18', '2025-01-01', '2025-01-25', 'Reserved', 'Pagani Huayra', 'JKL012'),
(14, 1, 8, '2024-12-30 20:39:47', '2025-01-01', '2025-01-05', 'Reserved', 'BMW M3', 'MNO345'),
(15, 1, 22, '2024-12-30 21:23:53', '2025-01-01', '2025-01-05', 'Reserved', 'BMW X6', 'CDE567'),
(16, 1, 5, '2024-12-31 07:19:36', '2024-12-05', '2024-12-04', 'Reserved', 'Bugatti Chiron', 'DEF456'),
(18, 1, 16, '2024-12-31 07:28:32', '2024-12-12', '2024-12-17', 'Reserved', 'Lamborghini Gallardo', 'KLM789'),
(19, 1, 23, '2024-12-31 07:29:35', '2024-12-12', '2024-12-15', 'Reserved', 'Porsche 918 Spyder', 'FGH890'),
(20, 1, 9, '2024-12-31 07:33:53', '2025-01-05', '2025-01-10', 'Reserved', 'Aston Martin DB11', 'PQR678'),
(21, 1, 4, '2024-12-31 08:47:05', '2025-01-01', '2025-01-05', 'Reserved', 'Mercedes Mclaren SLR', 'ABC123'),
(22, 1, 14, '2024-12-31 09:05:51', '2025-01-01', '2025-01-05', 'Reserved', 'Ferrari F90', 'EFG123'),
(23, 1, 4, '2024-12-31 10:01:24', '0034-12-23', '0003-04-05', 'Reserved', 'Mercedes Mclaren SLR', 'ABC123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`CarID`),
  ADD UNIQUE KEY `PlateID` (`PlateID`),
  ADD UNIQUE KEY `UNIQUE_CarModel` (`Model`),
  ADD UNIQUE KEY `UNIQUE_PlateID` (`PlateID`),
  ADD KEY `OfficeID` (`OfficeID`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`CustomerID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Indexes for table `offices`
--
ALTER TABLE `offices`
  ADD PRIMARY KEY (`OfficeID`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `ReservationID` (`ReservationID`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`ReservationID`),
  ADD KEY `CustomerID` (`CustomerID`),
  ADD KEY `CarID` (`CarID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `CarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `EmployeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `offices`
--
ALTER TABLE `offices`
  MODIFY `OfficeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `ReservationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`OfficeID`) REFERENCES `offices` (`OfficeID`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`ReservationID`) REFERENCES `reservations` (`ReservationID`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customers` (`CustomerID`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`CarID`) REFERENCES `cars` (`CarID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
