-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 24, 2018 at 08:11 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `arwin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `CommentID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `PostID` int(11) NOT NULL,
  `CommentDesc` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`CommentID`, `UserID`, `PostID`, `CommentDesc`) VALUES
(1, 2, 2, 'FUCK U'),
(2, 2, 6, 'test'),
(3, 2, 3, 'wooo'),
(4, 2, 6, 'sdfsdf'),
(5, 2, 3, '&lt;a href=\'\'&gt;,/a&lt;/a&gt;'),
(6, 2, 4, 'wtf'),
(7, 2, 8, 'duturtiee');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `PhotoID` int(11) NOT NULL,
  `PhotoLocation` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`PhotoID`, `PhotoLocation`) VALUES
(1, 'photos/bc6678f63cecc9437ed1561b9570c87e'),
(2, 'photos/33881672_1950640511825100_7496683467468439552_n.jpg'),
(3, 'photos/0491d03074b02c703a951e70032bb1ea'),
(4, 'photos/4709415e9334ecc50d6d96ea3582351f'),
(5, 'photos/75d61f8212f84a60443c72df13fcba1b'),
(6, 'photos/23b1953b4e112fe039a0effc9f0ec41b');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `PostID` int(11) NOT NULL,
  `PhotoID` int(11) NOT NULL,
  `PostDesc` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `Poster` int(11) NOT NULL,
  `PostedOn` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`PostID`, `PhotoID`, `PostDesc`, `Poster`, `PostedOn`) VALUES
(3, 2, 'Anyways...back to this album...new single out now!!! Link in bio #drake', 1, '2018-06-24 20:09:31'),
(4, 2, 'Anyways...back to this album...new single out now!!! Link in bio', 1, '2018-06-24 20:09:31'),
(6, 4, 'this is a test #pogi', 2, '2018-06-24 23:37:36'),
(7, 5, '#pogi test tagging', 2, '2018-06-24 23:37:58'),
(8, 6, '', 1, '2018-06-25 01:43:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `ProfileDesc` text NOT NULL,
  `ProfilePicture` varchar(255) NOT NULL DEFAULT 'photos/bc6678f63cecc9437ed1561b9570c87e',
  `IsAdmin` int(11) NOT NULL DEFAULT '0',
  `IsActive` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `ProfileDesc`, `ProfilePicture`, `IsAdmin`, `IsActive`) VALUES
(1, 'champagnepapi', '202cb962ac59075b964b07152d234b70', 'youtu.be/rIhx2wZ8jnA ', 'photos/bc6678f63cecc9437ed1561b9570c87e', 0, 1),
(2, 'admin', '81dc9bdb52d04dc20036dbd8313ed055', '', 'photos/b4accefdfc69dc2be7ed04eb2e2ec434', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_followers`
--

CREATE TABLE `user_followers` (
  `ID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `FollowerID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_followers`
--

INSERT INTO `user_followers` (`ID`, `UserID`, `FollowerID`) VALUES
(7, 1, 2),
(9, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `SettingID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `TimelinePrivacy` int(11) NOT NULL COMMENT '1 for public, 0 for private'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`SettingID`, `UserID`, `TimelinePrivacy`) VALUES
(1, 1, 0),
(2, 2, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`CommentID`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`PhotoID`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`PostID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- Indexes for table `user_followers`
--
ALTER TABLE `user_followers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`SettingID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `PhotoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `PostID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_followers`
--
ALTER TABLE `user_followers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `user_settings`
--
ALTER TABLE `user_settings`
  MODIFY `SettingID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
