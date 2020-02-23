-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2020 at 08:04 PM
-- Server version: 10.3.22-MariaDB-0+deb10u1
-- PHP Version: 7.3.14-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpe`
--

-- --------------------------------------------------------

--
-- Table structure for table `incomingmails`
--

CREATE TABLE `incomingmails` (
  `id` bigint(20) NOT NULL,
  `address` varchar(32) DEFAULT NULL,
  `subject` tinytext DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timecreated` datetime DEFAULT NULL,
  `completed` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ipsregistered`
--

CREATE TABLE `ipsregistered` (
  `ipaddress` varchar(15) NOT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `ipaddress` varchar(15) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `timecreated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `query` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `outgoing_mails`
--

CREATE TABLE `outgoing_mails` (
  `id` int(11) NOT NULL,
  `email` varchar(32) DEFAULT NULL,
  `subject` tinytext DEFAULT NULL,
  `body` text DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `response_types`
--

CREATE TABLE `response_types` (
  `value` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `response_types`
--

INSERT INTO `response_types` (`value`, `description`) VALUES
(1, 'success'),
(-1, 'error'),
(-2, 'unauthorized'),
(3, 'redirect');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `auth` varchar(8) NOT NULL,
  `value` varchar(8) DEFAULT NULL,
  `user` blob DEFAULT NULL,
  `temporary` blob DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_action` datetime DEFAULT NULL,
  `loggedin` tinyint(1) DEFAULT 0,
  `address` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE `system` (
  `name` varchar(25) DEFAULT NULL,
  `value` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `type_id` int(11) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `issued_userid` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `data` blob DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticket_types`
--

CREATE TABLE `ticket_types` (
  `id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT NULL,
  `level` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `translations_eng`
--

CREATE TABLE `translations_eng` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `value` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `translations_eng`
--

INSERT INTO `translations_eng` (`id`, `name`, `value`) VALUES
(1, 'mail_error', 'There is a problem with the mail server.'),
(2, 'duplicate_username', 'Username does already exists in the database.'),
(3, 'duplicate_email', 'E-mail does address already exists in the database.'),
(4, 'alert_error', 'Operation failed.'),
(5, 'alert_success', 'Operation successful.'),
(6, 'registration_success', 'Registration successful.'),
(7, 'username_error', 'Username does not exist.'),
(8, 'password_error', 'You have entered a wrong password.'),
(9, 'ip_blocked', 'Ip address is blocked.'),
(10, 'account_blocked', 'Your account is blocked.'),
(11, 'email_format', 'The entered e-mail is not valid.'),
(12, 'password_mismatch', 'The entered passwords does not match.'),
(13, 'email_not_exist', 'The e-mail address does not exist in our database.'),
(14, 'forgotten_success', 'You have requested a new password.'),
(15, 'email_forgotten_password_greet', 'Hello'),
(16, 'email_forgotten_password_message', '<p>You have requested a new password. You can change your password by clicking the link below:<p>'),
(17, 'email_forgotten_password_subject', 'Forgotten Password'),
(18, 'email_newpassword_greet', 'Hello'),
(19, 'email_newpassword_message', '<p>The password has changed on your account.</p>'),
(20, 'newpassword_success', 'Password has changed successfully.'),
(21, 'email_newpassword_subject', 'New password');

-- --------------------------------------------------------

--
-- Table structure for table `translations_hun`
--

CREATE TABLE `translations_hun` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `value` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `translations_hun`
--

INSERT INTO `translations_hun` (`id`, `name`, `value`) VALUES
(1, 'mail_error', 'Gond van a levélküldő szolgáltatással.'),
(2, 'duplicate_username', 'A felhasználónév már szerepel az adatbázisban.'),
(3, 'duplicate_email', 'Az email cím már szerepel az adatbázisban'),
(4, 'alert_error', 'Nem sikerült végrehajtani a műveletet.'),
(5, 'alert_success', 'A művelet sikeresen végrehajtva.'),
(6, 'registration_success', 'Sikeres regisztráció.'),
(7, 'username_error', 'Nem létezik felhasználó a megadott néven.'),
(8, 'password_error', 'Rossz jelszót adott meg.'),
(9, 'ip_blocked', 'Ip cím blokkolva.'),
(10, 'account_blocked', 'A fiók blokkolva van.'),
(11, 'email_format', 'Az e-mail cím formátuma helytelen.'),
(12, 'password_mismatch', 'A megadott kódok nem egyeznek.'),
(13, 'email_not_exist', 'A megadott e-mail cím nem létezik a rendszerben.'),
(14, 'forgotten_success', 'Új jelszót igényelt.'),
(15, 'email_forgotten_password_greet', 'Hello'),
(16, 'email_forgotten_password_message', '<p>Felhasználói fiókjához új jelszót igényelt. Az alábbi hivatkozásra kattintva adhat meg új jelszót:<p>'),
(17, 'email_forgotten_password_subject', 'Elfelejtett jelszó'),
(18, 'email_newpassword_greet', 'Hello'),
(19, 'email_newpassword_message', '<p>A fiókjához tartozó jelszó megváltoztatásra került.</p>'),
(20, 'newpassword_success', 'A jelszó sikeresen megváltoztatva.'),
(21, 'email_newpassword_subject', 'Új jelszó');

-- --------------------------------------------------------

--
-- Table structure for table `usergroups`
--

CREATE TABLE `usergroups` (
  `id` int(11) NOT NULL,
  `name` varchar(25) DEFAULT 'NULL',
  `label` varchar(255) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `su_ids` blob DEFAULT NULL,
  `member_ids` blob DEFAULT NULL,
  `updated` tinyint(1) DEFAULT 1,
  `status` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergroups`
--

INSERT INTO `usergroups` (`id`, `name`, `label`, `level`, `owner_id`, `su_ids`, `member_ids`, `updated`, `status`) VALUES
(1, 'super_admin', 'Moderators of the system', 0, 1, 0x613a313a7b693a303b693a313b7d, 0x613a313a7b693a303b693a313b7d, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(32) DEFAULT NULL,
  `realname` varchar(200) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `membersince` date DEFAULT NULL,
  `usergroups` blob DEFAULT NULL,
  `temporary` blob DEFAULT NULL,
  `lat` double DEFAULT NULL,
  `lng` double DEFAULT NULL,
  `county_id` int(11) DEFAULT NULL,
  `town_id` int(11) DEFAULT NULL,
  `postcode_id` int(11) DEFAULT NULL,
  `postcode` varchar(25) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `updated` tinyint(1) DEFAULT 0,
  `blocked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `realname`, `phone`, `password`, `salt`, `token`, `lastlogin`, `membersince`, `usergroups`, `temporary`, `lat`, `lng`, `county_id`, `town_id`, `postcode_id`, `postcode`, `address`, `updated`, `blocked`) VALUES
(1, 'adam@sztefanov.com', 'admin', 'Admin User', '', 'u7U\"]#`+QxRR-Vs%r}wt>lw-8^PH<,N^D9x:F7hOuS\\/Fq`^gdbv{jxZ){S2},b\'/^co%o>_`V[1Q#I3HkMbU!CQ95ErMP?ikz?GS;/0hvNOy(O_\'f#xT/,e:7`xH5Fq', 'qd%GUN\\\'Ms~|WOoK@ICCc;@&2.xBdYtZigH`@18tq~X(>k/Z2]2?H1DUPK{/E(^LU._>}8e\'+O&VxKB.v7r^!I=Ib-r?yx<a6wk@Pc)]8CFIIOJ&P3ICL\')ah2]sD0l;', 'ed5d77eced1ffe3e956cb912387792b3', '2020-02-23 11:09:24', NULL, 0x613a313a7b693a303b693a313b7d, 0x613a303a7b7d, NULL, NULL, NULL, NULL, NULL, '', NULL, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE `verification` (
  `username` varchar(32) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `realname` varchar(200) DEFAULT NULL,
  `tel` varchar(200) DEFAULT NULL,
  `salt` varchar(128) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `incomingmails`
--
ALTER TABLE `incomingmails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD KEY `ipaddress` (`ipaddress`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created` (`created`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `outgoing_mails`
--
ALTER TABLE `outgoing_mails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `response_types`
--
ALTER TABLE `response_types`
  ADD UNIQUE KEY `value` (`value`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`auth`),
  ADD UNIQUE KEY `auth` (`auth`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
  ADD UNIQUE KEY `variable` (`name`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`type_id`),
  ADD KEY `name` (`name`),
  ADD KEY `issued_userid` (`issued_userid`);

--
-- Indexes for table `ticket_types`
--
ALTER TABLE `ticket_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `translations_eng`
--
ALTER TABLE `translations_eng`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `translations_hun`
--
ALTER TABLE `translations_hun`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `usergroups`
--
ALTER TABLE `usergroups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `level` (`level`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `verification`
--
ALTER TABLE `verification`
  ADD PRIMARY KEY (`username`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `incomingmails`
--
ALTER TABLE `incomingmails`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `outgoing_mails`
--
ALTER TABLE `outgoing_mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_types`
--
ALTER TABLE `ticket_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `translations_eng`
--
ALTER TABLE `translations_eng`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `translations_hun`
--
ALTER TABLE `translations_hun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `usergroups`
--
ALTER TABLE `usergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
