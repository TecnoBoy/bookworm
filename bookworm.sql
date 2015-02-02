SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `bookworm`
--
DROP DATABASE IF EXISTS `bookworm`;
CREATE DATABASE `bookworm`;
USE `bookworm`;

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
`id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=160 DEFAULT CHARSET=latin1;

--
-- Table `authors` initial content
--

INSERT INTO `authors` (`id`, `first_name`, `last_name`) VALUES
(1, 'Edwin', 'Abbott');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `edition` int(11) NOT NULL DEFAULT '1',
  `inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('read','not_read','reading','almost','partially') NOT NULL DEFAULT 'not_read',
  `type` enum('book','article','notes','manual','normative') NOT NULL DEFAULT 'book',
  `language` enum('en','it') NOT NULL DEFAULT 'en',
  `file` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL DEFAULT '0000/default.jpg'
) ENGINE=InnoDB AUTO_INCREMENT=127 DEFAULT CHARSET=latin1;

--
-- Table `books` initial content
--

INSERT INTO `books` (`id`, `title`, `edition`, `inserted`, `status`, `type`, `language`, `file`, `image`) VALUES
(1, 'Flatland - A Romance of Many Dimensions', 1, '2015-01-11 10:24:16', 'not_read', 'book', 'en', 'Flatland - Abbott.pdf', 'Flatland - Abbott.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `books_authors`
--

CREATE TABLE IF NOT EXISTS `books_authors` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table `books_authors` initial content
--

INSERT INTO `books_authors` (`book_id`, `author_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `books_categories`
--

CREATE TABLE IF NOT EXISTS `books_categories` (
  `book_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table `books_categories` initial content
--

INSERT INTO `books_categories` (`book_id`, `category_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=latin1;

--
-- Table `categories` initial content
--

INSERT INTO `categories` (`id`, `name`, `parent_id`) VALUES
(1, 'Novels', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `first_name` (`first_name`,`last_name`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books_authors`
--
ALTER TABLE `books_authors`
 ADD PRIMARY KEY (`book_id`,`author_id`), ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `books_categories`
--
ALTER TABLE `books_categories`
 ADD PRIMARY KEY (`book_id`,`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=160;
--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=127;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
