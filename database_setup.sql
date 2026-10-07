-- ================================================================
-- HOTELIER — Unified Database Setup & Seed Script
-- Safe to rerun at any time
-- ================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------
-- 1. Table Definitions (with roomNumber included in Room schema)
-- ----------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `User` (
    `userID` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Guest` (
    `guestID` INT AUTO_INCREMENT PRIMARY KEY,
    `fullName` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `phoneNumber` VARCHAR(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Room` (
    `roomID` INT AUTO_INCREMENT PRIMARY KEY,
    `roomNumber` INT NOT NULL DEFAULT 0,
    `roomType` VARCHAR(50) NOT NULL,
    `priceRate` DECIMAL(10,2) NOT NULL,
    `status` VARCHAR(20) NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Booking` (
    `bookingID` INT AUTO_INCREMENT PRIMARY KEY,
    `userID` INT NULL,
    `guestID` INT NULL,
    `roomID` INT NOT NULL,
    `checkInDate` DATE NOT NULL,
    `checkOutDate` DATE NOT NULL,
    FOREIGN KEY (`userID`) REFERENCES `User`(`userID`) ON DELETE SET NULL,
    FOREIGN KEY (`guestID`) REFERENCES `Guest`(`guestID`) ON DELETE SET NULL,
    FOREIGN KEY (`roomID`) REFERENCES `Room`(`roomID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Facilities` (
    `facilID` INT AUTO_INCREMENT PRIMARY KEY,
    `type` VARCHAR(50) NOT NULL,
    `location` VARCHAR(100),
    `priceRate` DECIMAL(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `Transaction` (
    `transactionID` INT AUTO_INCREMENT PRIMARY KEY,
    `bookingID` INT NOT NULL,
    `totalAmount` DECIMAL(10,2) NOT NULL,
    `paymentDate` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`bookingID`) REFERENCES `Booking`(`bookingID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ----------------------------------------------------------------
-- 2. Clear Existing Data (Idempotent execution)
-- ----------------------------------------------------------------
DELETE FROM `Transaction`;
DELETE FROM `Booking`;
DELETE FROM `Guest`;
DELETE FROM `User`;
DELETE FROM `Room`;
DELETE FROM `Facilities`;

ALTER TABLE `User` AUTO_INCREMENT = 1;
ALTER TABLE `Guest` AUTO_INCREMENT = 1;
ALTER TABLE `Room` AUTO_INCREMENT = 1;
ALTER TABLE `Booking` AUTO_INCREMENT = 1;
ALTER TABLE `Facilities` AUTO_INCREMENT = 1;
ALTER TABLE `Transaction` AUTO_INCREMENT = 1;

-- ----------------------------------------------------------------
-- 3. Seed Demo Users (Passwords: guest123, staff123, admin123)
-- ----------------------------------------------------------------
INSERT INTO `User` (`userID`, `username`, `password`, `role`) VALUES
(1, 'guest@hotelier.com', '$2y$10$u8QcI./l9zfV3cYR5Q4v7.4dPw0gH2zBQBV6.NKT5ELvDfFzTLiMi', 'guest'),
(2, 'staff@hotelier.com', '$2y$10$Xn3lPbHM5VPPgR7SSmBGW.mBF4.IlOCN0sGMBQbIR6bnH5q.W.OtC', 'staff'),
(3, 'admin@hotelier.com', '$2y$10$kIjx1V0kTDkdqPrB55vbwuWUSYiFHImxWI1o79z5UJ5FKxqJZBv6.', 'admin');

-- ----------------------------------------------------------------
-- 4. Seed Guest Profile (linked to demo guest user)
-- ----------------------------------------------------------------
INSERT INTO `Guest` (`guestID`, `fullName`, `email`, `phoneNumber`) VALUES
(1, 'Guest User', 'guest@hotelier.com', '+60 12-345 6789');

-- ----------------------------------------------------------------
-- 5. Seed Facilities (6 hotel amenities)
-- ----------------------------------------------------------------
INSERT INTO `Facilities` (`facilID`, `type`, `location`, `priceRate`) VALUES
(1, 'Fitness Center', '4th Floor',  20.00),
(2, 'Luxury Spa',     '6th Floor',  80.00),
(3, 'Infinity Pool',  '5th Floor',  15.00),
(4, 'Airport Pickup', 'Lobby',      45.00),
(5, 'Fine Dining',    '3rd Floor',  60.00),
(6, 'Concierge',      'Lobby',       0.00);

-- ----------------------------------------------------------------
-- 6. Seed 100 Rooms Across 5 Floors (301-720)
-- ----------------------------------------------------------------
INSERT INTO `Room` (`roomNumber`, `roomType`, `priceRate`, `status`) VALUES
-- Floor 3: Standard Rooms (301-320)
(301, 'Standard Room', 80.00, 'available'),
(302, 'Standard Room', 80.00, 'available'),
(303, 'Standard Room', 80.00, 'available'),
(304, 'Standard Room', 80.00, 'available'),
(305, 'Standard Room', 80.00, 'available'),
(306, 'Standard Room', 80.00, 'available'),
(307, 'Standard Room', 80.00, 'available'),
(308, 'Standard Room', 80.00, 'available'),
(309, 'Standard Room', 80.00, 'available'),
(310, 'Standard Room', 80.00, 'available'),
(311, 'Standard Room', 80.00, 'available'),
(312, 'Standard Room', 80.00, 'available'),
(313, 'Standard Room', 80.00, 'available'),
(314, 'Standard Room', 80.00, 'available'),
(315, 'Standard Room', 80.00, 'available'),
(316, 'Standard Room', 80.00, 'available'),
(317, 'Standard Room', 80.00, 'available'),
(318, 'Standard Room', 80.00, 'available'),
(319, 'Standard Room', 80.00, 'available'),
(320, 'Standard Room', 80.00, 'available'),
-- Floor 4: Standard Rooms (401-420)
(401, 'Standard Room', 80.00, 'available'),
(402, 'Standard Room', 80.00, 'available'),
(403, 'Standard Room', 80.00, 'available'),
(404, 'Standard Room', 80.00, 'available'),
(405, 'Standard Room', 80.00, 'available'),
(406, 'Standard Room', 80.00, 'available'),
(407, 'Standard Room', 80.00, 'available'),
(408, 'Standard Room', 80.00, 'available'),
(409, 'Standard Room', 80.00, 'available'),
(410, 'Standard Room', 80.00, 'available'),
(411, 'Standard Room', 80.00, 'available'),
(412, 'Standard Room', 80.00, 'available'),
(413, 'Standard Room', 80.00, 'available'),
(414, 'Standard Room', 80.00, 'available'),
(415, 'Standard Room', 80.00, 'available'),
(416, 'Standard Room', 80.00, 'available'),
(417, 'Standard Room', 80.00, 'available'),
(418, 'Standard Room', 80.00, 'available'),
(419, 'Standard Room', 80.00, 'available'),
(420, 'Standard Room', 80.00, 'available'),
-- Floor 5: Deluxe Rooms (501-520)
(501, 'Deluxe Room', 150.00, 'available'),
(502, 'Deluxe Room', 150.00, 'available'),
(503, 'Deluxe Room', 150.00, 'available'),
(504, 'Deluxe Room', 150.00, 'available'),
(505, 'Deluxe Room', 150.00, 'available'),
(506, 'Deluxe Room', 150.00, 'available'),
(507, 'Deluxe Room', 150.00, 'available'),
(508, 'Deluxe Room', 150.00, 'available'),
(509, 'Deluxe Room', 150.00, 'available'),
(510, 'Deluxe Room', 150.00, 'available'),
(511, 'Deluxe Room', 150.00, 'available'),
(512, 'Deluxe Room', 150.00, 'available'),
(513, 'Deluxe Room', 150.00, 'available'),
(514, 'Deluxe Room', 150.00, 'available'),
(515, 'Deluxe Room', 150.00, 'available'),
(516, 'Deluxe Room', 150.00, 'available'),
(517, 'Deluxe Room', 150.00, 'available'),
(518, 'Deluxe Room', 150.00, 'available'),
(519, 'Deluxe Room', 150.00, 'available'),
(520, 'Deluxe Room', 150.00, 'available'),
-- Floor 6: Executive Suites (601-620)
(601, 'Executive Suite', 250.00, 'available'),
(602, 'Executive Suite', 250.00, 'available'),
(603, 'Executive Suite', 250.00, 'available'),
(604, 'Executive Suite', 250.00, 'available'),
(605, 'Executive Suite', 250.00, 'available'),
(606, 'Executive Suite', 250.00, 'available'),
(607, 'Executive Suite', 250.00, 'available'),
(608, 'Executive Suite', 250.00, 'available'),
(609, 'Executive Suite', 250.00, 'available'),
(610, 'Executive Suite', 250.00, 'available'),
(611, 'Executive Suite', 250.00, 'available'),
(612, 'Executive Suite', 250.00, 'available'),
(613, 'Executive Suite', 250.00, 'available'),
(614, 'Executive Suite', 250.00, 'available'),
(615, 'Executive Suite', 250.00, 'available'),
(616, 'Executive Suite', 250.00, 'available'),
(617, 'Executive Suite', 250.00, 'available'),
(618, 'Executive Suite', 250.00, 'available'),
(619, 'Executive Suite', 250.00, 'available'),
(620, 'Executive Suite', 250.00, 'available'),
-- Floor 7: Penthouses (701-720)
(701, 'Penthouse', 500.00, 'available'),
(702, 'Penthouse', 500.00, 'available'),
(703, 'Penthouse', 500.00, 'available'),
(704, 'Penthouse', 500.00, 'available'),
(705, 'Penthouse', 500.00, 'available'),
(706, 'Penthouse', 500.00, 'available'),
(707, 'Penthouse', 500.00, 'available'),
(708, 'Penthouse', 500.00, 'available'),
(709, 'Penthouse', 500.00, 'available'),
(710, 'Penthouse', 500.00, 'available'),
(711, 'Penthouse', 500.00, 'available'),
(712, 'Penthouse', 500.00, 'available'),
(713, 'Penthouse', 500.00, 'available'),
(714, 'Penthouse', 500.00, 'available'),
(715, 'Penthouse', 500.00, 'available'),
(716, 'Penthouse', 500.00, 'available'),
(717, 'Penthouse', 500.00, 'available'),
(718, 'Penthouse', 500.00, 'available'),
(719, 'Penthouse', 500.00, 'available'),
(720, 'Penthouse', 500.00, 'available');

SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- Verified Credentials:
--   guest@hotelier.com / guest123
--   staff@hotelier.com / staff123
--   admin@hotelier.com / admin123
-- ================================================================
