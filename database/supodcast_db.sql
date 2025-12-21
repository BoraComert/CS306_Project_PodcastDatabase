-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 21 Ara 2025, 13:25:40
-- Sunucu sürümü: 10.4.32-MariaDB
-- PHP Sürümü: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `supodcast_db`
--

DELIMITER $$
--
-- Yordamlar
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_ep_to_userplaylist` (IN `pl_user_id` INT, IN `pl_playlist_id` INT, IN `pl_ep_id` INT)   begin

-- if an user doesn't exists you can't add an episode to that users playlist

if not exists (

select 1
from users u
where u.user_id = pl_user_id
) then 
signal sqlstate '45000' set message_text = 'user doesnt exists';
end if;

-- if a userplaylist doesn't exists or doesn't belong to someone than you can't add to that playlist

if not exists (

select 1
from userplaylists plst
where plst.user_playlist_id = pl_playlist_id and plst.user_id = pl_user_id
) then 
signal sqlstate '45000' set message_text = 'Playlist doesnt exists or doesnt belong to this user';
end if;


-- if an episode doesn't exists than you can't add to that playlist because there is nothing to add 


if not exists (

select 1
from episodes e
where e.ep_id = pl_ep_id
) then 
signal sqlstate '45000' set message_text = 'Episode doesnt exists';
end if;

-- Check if an episode is already in a users playlist

if exists (

select 1
from ep_added_to_userplaylist uplst
where uplst.user_playlist_id = pl_playlist_id  and uplst.ep_id = pl_ep_id
) then 
signal sqlstate '45000' set message_text = 'Episode already exists in this playlist';
end if;


insert into ep_added_to_userplaylist (ep_id, user_playlist_id )
values (pl_ep_id, pl_playlist_id);

end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createEpisode` (IN `p_pod_id` INT, IN `e_name` VARCHAR(100), IN `e_duration` INT)   BEGIN
    -- check if podcast exists 
    IF NOT EXISTS (
        SELECT 1 FROM PODCASTS p
        WHERE p.pod_id = p_pod_id
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'You cannot create an episode for a podcast that does not exist!';
    END IF;
	-- check episode name exists
    IF e_name IS NULL OR e_name = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Episode name cannot be empty!';
    END IF;

    -- Check episode duration
    IF e_duration <= 0 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Episode duration must be a positive number!';
    END IF;

    INSERT INTO EPISODES (ep_name, ep_duration, pod_id)
    VALUES (e_name, e_duration, p_pod_id);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createPodcast` (IN `p_name` VARCHAR(100), IN `p_description` VARCHAR(100))   BEGIN
    -- check is podcast name is present 
    IF p_name IS NULL OR p_name = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Podcast name cannot be empty!';
    END IF;

    -- insert podcast
    INSERT INTO PODCASTS (pod_name, pod_description, pod_avg_ep_rating)
    VALUES (p_name, p_description, NULL);

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `createReview` (IN `r_ep_id` INT, IN `rating` INT, IN `r_comment` VARCHAR(200))   BEGIN
    -- 1) check if episode really exists
    IF NOT EXISTS (
        SELECT 1
        FROM EPISODES e
        WHERE e.ep_id = r_ep_id
    ) THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'You cant review an episode that doesnt exist!';
    END IF;

    -- check if rating is between 1 and 5
    IF rating < 1 OR rating > 5 THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Rating should be between 1 and 5';
    END IF;

    
    INSERT INTO REVIEWS (review_comment, review_star, ep_id)
    VALUES (r_comment, rating, r_ep_id);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `categories`
--

CREATE TABLE `categories` (
  `category_name` varchar(100) NOT NULL,
  `category_description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `categories`
--

INSERT INTO `categories` (`category_name`, `category_description`) VALUES
('drama', 'there are dramas'),
('feminism', 'female rights'),
('funny', 'makes people laugh (hahaha)'),
('horror', 'scary films'),
('mental_health', 'peoples mental health'),
('pyschology', 'about humans behaviours'),
('scary', 'horror films'),
('Sci-fi', 'scientific flms'),
('tech', 'technological films'),
('violation', 'violational films');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `episodes`
--

CREATE TABLE `episodes` (
  `ep_duration` int(11) DEFAULT NULL,
  `ep_name` varchar(100) DEFAULT NULL,
  `ep_id` int(11) NOT NULL,
  `pod_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `episodes`
--

INSERT INTO `episodes` (`ep_duration`, `ep_name`, `ep_id`, `pod_id`) VALUES
(45, 'ai', 1, 1),
(40, 'computer', 2, 1),
(35, 'bias', 3, 2),
(50, 'health informations', 4, 2),
(30, 'ep name', 5, 3),
(55, 'episode nema', 6, 3),
(60, 'fights of wolrd', 7, 4),
(48, 'mathematics', 8, 4),
(42, 'films', 9, 5),
(47, 'bora', 10, 5);

--
-- Tetikleyiciler `episodes`
--
DELIMITER $$
CREATE TRIGGER `delete_podcasts_without_episodes` AFTER DELETE ON `episodes` FOR EACH ROW BEGIN

	declare ep_count int;
-- check if any episodes left in the podcast series
	select count(*)
	into ep_count
	from episodes
	where pod_id = old.pod_id;

	if ep_count = 0 then

		delete  from pod_belongs_to_cat 
		where pod_id = old.pod_id;
    
		delete from podcasts
		where pod_id = old.pod_id;

	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ep_added_to_userplaylist`
--

CREATE TABLE `ep_added_to_userplaylist` (
  `ep_id` int(11) NOT NULL,
  `user_playlist_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `ep_added_to_userplaylist`
--

INSERT INTO `ep_added_to_userplaylist` (`ep_id`, `user_playlist_id`) VALUES
(1, 1),
(3, 2),
(5, 3),
(7, 4),
(9, 5),
(2, 6),
(4, 7),
(6, 8),
(8, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ep_hosted_by_host`
--

CREATE TABLE `ep_hosted_by_host` (
  `ep_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `ep_hosted_by_host`
--

INSERT INTO `ep_hosted_by_host` (`ep_id`, `host_id`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 2),
(5, 3),
(6, 3),
(7, 4),
(8, 4),
(9, 5),
(10, 5);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `hosts`
--

CREATE TABLE `hosts` (
  `host_id` int(11) NOT NULL,
  `host_name` varchar(50) DEFAULT NULL,
  `host_country` varchar(50) DEFAULT NULL,
  `host_organization` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `hosts`
--

INSERT INTO `hosts` (`host_id`, `host_name`, `host_country`, `host_organization`) VALUES
(1, 'bekir can', 'amerika', 'şirket1'),
(2, 'Bora cömret', 'ingiltere', 'şirket2'),
(3, 'Arda karayel', 'kanada', 'şirket4'),
(4, 'hasan ahmet', 'almanya', 'holding1'),
(5, 'miray santos', 'almanya', 'holding2'),
(6, 'muhammed lana', 'türkiye', 'yurtdışışirket2'),
(7, 'el habibi', 'türkiye', 'şirket'),
(8, 'johny bravo', 'türkiye', 'limited.as'),
(9, 'can günahlar', 'türkiye', 'girişimci'),
(10, 'label c5', 'türkiye', 'şirketler');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `podcasts`
--

CREATE TABLE `podcasts` (
  `pod_name` varchar(100) DEFAULT NULL,
  `pod_id` int(11) NOT NULL,
  `pod_description` varchar(100) DEFAULT NULL,
  `pod_avg_ep_rating` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `podcasts`
--

INSERT INTO `podcasts` (`pod_name`, `pod_id`, `pod_description`, `pod_avg_ep_rating`) VALUES
('Tedx', 1, 'people talks', 2.00),
('biology in science', 2, 'biology talks', 5.00),
('artificial intelligence', 3, 'ai is rising people', NULL),
('konuşanlar', 4, 'hasan can polat', 4.00),
('demon slayer', 5, 'mükemmel anime', 1.67),
('musicians', 6, 'playing music', NULL),
('gamers', 7, 'playing games bro', NULL),
('Economy conference', 8, 'stock exchange info ', NULL),
('inspiring talks', 9, 'people talks about their lives', NULL),
('fight club', 10, 'first rule!', NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `pod_belongs_to_cat`
--

CREATE TABLE `pod_belongs_to_cat` (
  `pod_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `pod_belongs_to_cat`
--

INSERT INTO `pod_belongs_to_cat` (`pod_id`, `category_name`) VALUES
(1, 'tech'),
(2, 'pyschology'),
(3, 'mental_health'),
(4, 'feminism'),
(5, 'horror'),
(6, 'scary'),
(7, 'sci-fi'),
(8, 'violation'),
(9, 'drama'),
(10, 'funny');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `reviews`
--

CREATE TABLE `reviews` (
  `review_comment` varchar(200) DEFAULT NULL,
  `review_id` int(11) NOT NULL,
  `review_star` int(11) DEFAULT NULL,
  `ep_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `reviews`
--

INSERT INTO `reviews` (`review_comment`, `review_id`, `review_star`, `ep_id`) VALUES
('Trigger Test Yorumu', 0, 2, 9),
('great bro', 1, 5, 1),
('niiceee', 2, 2, 2),
('bad.', 3, 5, 3),
('great.', 4, 1, 2),
('relaxed', 5, 1, 1),
('boring', 6, 1, 2),
('I slept', 7, 2, 2),
('Interesting', 8, 4, 8),
('Funny epiosde.', 9, 1, 9),
('stop please', 10, 2, 10);

--
-- Tetikleyiciler `reviews`
--
DELIMITER $$
CREATE TRIGGER `update_podcast_rating` AFTER INSERT ON `reviews` FOR EACH ROW BEGIN

	declare var_pid int;

	select pod_id into var_pid
	from episodes 
	where ep_id = new.ep_id;

	update podcasts p
	set p.pod_avg_ep_rating =(
		select avg(r.review_star)
		from  reviews r
		join episodes e on e.ep_id = r.ep_id 
		where e.pod_id = var_pid 
		 )	
    where p.pod_id = var_pid ;
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `userplaylists`
--

CREATE TABLE `userplaylists` (
  `user_id` int(11) DEFAULT NULL,
  `user_playlistName` varchar(100) NOT NULL,
  `user_playlist_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `userplaylists`
--

INSERT INTO `userplaylists` (`user_id`, `user_playlistName`, `user_playlist_id`) VALUES
(1, 'gym playlist', 1),
(2, 'rock playlist', 2),
(3, 'mix play', 3),
(4, 'playlist', 4),
(5, 'list', 5),
(6, 'listening playlist', 6),
(7, 'playlist46', 7),
(8, 'playlingo', 8),
(9, 'listo', 9),
(10, 'moooddd', 10);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`user_id`, `user_name`) VALUES
(1, 'bora'),
(2, 'bekir'),
(3, 'Ali'),
(4, 'muz'),
(5, 'usernamee'),
(6, 'nameuser'),
(7, 'broa'),
(8, 'bekiro'),
(9, 'borsa'),
(10, 'playlistuser');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_name`);

--
-- Tablo için indeksler `episodes`
--
ALTER TABLE `episodes`
  ADD PRIMARY KEY (`ep_id`),
  ADD KEY `pod_id` (`pod_id`);

--
-- Tablo için indeksler `ep_added_to_userplaylist`
--
ALTER TABLE `ep_added_to_userplaylist`
  ADD PRIMARY KEY (`user_playlist_id`,`ep_id`),
  ADD KEY `ep_id` (`ep_id`);

--
-- Tablo için indeksler `ep_hosted_by_host`
--
ALTER TABLE `ep_hosted_by_host`
  ADD PRIMARY KEY (`ep_id`,`host_id`),
  ADD KEY `host_id` (`host_id`);

--
-- Tablo için indeksler `hosts`
--
ALTER TABLE `hosts`
  ADD PRIMARY KEY (`host_id`);

--
-- Tablo için indeksler `podcasts`
--
ALTER TABLE `podcasts`
  ADD PRIMARY KEY (`pod_id`);

--
-- Tablo için indeksler `pod_belongs_to_cat`
--
ALTER TABLE `pod_belongs_to_cat`
  ADD PRIMARY KEY (`pod_id`,`category_name`),
  ADD KEY `category_name` (`category_name`);

--
-- Tablo için indeksler `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `ep_id` (`ep_id`);

--
-- Tablo için indeksler `userplaylists`
--
ALTER TABLE `userplaylists`
  ADD PRIMARY KEY (`user_playlist_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `podcasts`
--
ALTER TABLE `podcasts`
  MODIFY `pod_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `episodes`
--
ALTER TABLE `episodes`
  ADD CONSTRAINT `episodes_ibfk_1` FOREIGN KEY (`pod_id`) REFERENCES `podcasts` (`pod_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `ep_added_to_userplaylist`
--
ALTER TABLE `ep_added_to_userplaylist`
  ADD CONSTRAINT `ep_added_to_userplaylist_ibfk_1` FOREIGN KEY (`ep_id`) REFERENCES `episodes` (`ep_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ep_added_to_userplaylist_ibfk_2` FOREIGN KEY (`user_playlist_id`) REFERENCES `userplaylists` (`user_playlist_id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Tablo kısıtlamaları `ep_hosted_by_host`
--
ALTER TABLE `ep_hosted_by_host`
  ADD CONSTRAINT `ep_hosted_by_host_ibfk_1` FOREIGN KEY (`ep_id`) REFERENCES `episodes` (`ep_id`),
  ADD CONSTRAINT `ep_hosted_by_host_ibfk_2` FOREIGN KEY (`host_id`) REFERENCES `hosts` (`host_id`);

--
-- Tablo kısıtlamaları `pod_belongs_to_cat`
--
ALTER TABLE `pod_belongs_to_cat`
  ADD CONSTRAINT `pod_belongs_to_cat_ibfk_1` FOREIGN KEY (`pod_id`) REFERENCES `podcasts` (`pod_id`),
  ADD CONSTRAINT `pod_belongs_to_cat_ibfk_2` FOREIGN KEY (`category_name`) REFERENCES `categories` (`category_name`);

--
-- Tablo kısıtlamaları `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`ep_id`) REFERENCES `episodes` (`ep_id`) ON DELETE CASCADE;

--
-- Tablo kısıtlamaları `userplaylists`
--
ALTER TABLE `userplaylists`
  ADD CONSTRAINT `userplaylists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
