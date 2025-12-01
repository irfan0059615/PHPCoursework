USE `db2419804`;

CREATE TABLE IF NOT EXISTS `books_table` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `published_year` year(4) DEFAULT NULL,
  `updated_on` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
);

INSERT INTO `books_table` (`user_id`, `title`, `author`, `genre`, `published_year`, `updated_on`) VALUES
	(1, 'The Stranger', 'Albert Camus', 'Absurdist Fiction', '1942', '2025-11-28 11:22:42'),
	(1, 'The Martian', 'Andy Weir', 'Science Fiction', '2011', '2025-11-28 11:23:02'),
	(1, 'The Road', 'Cormac McCarthy', 'Post-Apocalyptic', '2006', '2025-11-28 11:23:23'),
	(1, '1984', 'George Orwell', 'Dystopian', '1949', '2025-11-28 11:25:07'),
	(1, 'The Hobbit', 'J.R.R. Tolkien', 'Fantasy', '1937', '2025-12-01 17:38:48');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);

INSERT INTO `users` (`username`, `password`, `created_at`) VALUES
	('irfan', '$2y$12$5sVW2HD5zwLbATze5G9yi.79dCDyejG./h0EThXXJptomTSkKhN5a', '2025-11-28 11:21:13');