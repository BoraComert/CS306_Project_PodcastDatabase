USE supodcast_db;

-- PODCASTS
INSERT INTO PODCASTS (pod_id, pod_name, pod_description) VALUES
(1, 'Tedx', 'people talks'),
(2, 'biology in science', 'biology talks'),
(3, 'artificial intelligence', 'ai is rising people'),
(4, 'konuşanlar', 'hasan can polat'),
(5, 'demon slayer', 'mükemmel anime'),
(6, 'musicians', 'playing music'),
(7, 'gamers', 'playing games bro'),
(8, 'Economy conference', 'stock exchange info '),
(9, 'inspiring talks', 'people talks about their lives'),
(10, 'fight club', 'first rule!');

-- CATEGORIES
INSERT INTO CATEGORIES (category_name, category_description) VALUES
('tech', 'technological films'),
('pyschology', 'about humans behaviours'),
('mental_health', 'peoples mental health'),
('feminism', 'female rights'),
('horror', 'scary films'),
('scary', 'horror films'),
('Sci-fi', 'scientific flms'),
('violation', 'violational films'),
('drama', 'there are dramas'),
('funny', 'makes people laugh (hahaha)');

-- HOSTS
INSERT INTO HOSTS (host_id, host_name, host_country, host_organization) VALUES
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

-- USERS
INSERT INTO USERS (user_id, user_name) VALUES
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

-- USERPLAYLISTS
INSERT INTO USERPLAYLISTS (user_playlist_id, user_playlistName, user_id) VALUES
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

-- EPISODES
INSERT INTO EPISODES (ep_id, ep_name, ep_duration, pod_id) VALUES
(1, 'ai', 45, 1),
(2, 'computer', 40, 1),
(3, 'bias', 35, 2),
(4, 'health informations', 50, 2),
(5, 'ep name', 30, 3),
(6, 'episode nema', 55, 3),
(7, 'fights of wolrd', 60, 4),
(8, 'mathematics', 48, 4),
(9, 'films', 42, 5),
(10, 'bora', 47, 5);

-- Pod_has_Ep
INSERT INTO Pod_has_Ep (pod_id, pod_name, ep_id) VALUES
(1, 'tech', 1),
(1, 'tech', 2),
(2, 'psycho', 3),
(2, 'pyscho', 4),
(3, 'mood', 5),
(3, 'mood', 6),
(4, 'bad_things', 7),
(4, 'bad_things', 8),
(5, 'chatting', 9),
(5, 'chatting', 10);




-- Pod_Belongs_to_Cat
INSERT INTO Pod_Belongs_to_Cat (pod_id, category_name) VALUES
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

-- Ep_Hosted_by_Host
INSERT INTO Ep_Hosted_by_Host (ep_id, host_id) VALUES
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

-- Ep_Added_to_UserPlaylist
INSERT INTO Ep_Added_to_UserPlaylist (user_playlist_id, ep_id) VALUES
(1, 1),
(2, 3),
(3, 5),
(4, 7),
(5, 9),
(6, 2),
(7, 4),
(8, 6),
(9, 8),
(10, 10);

INSERT INTO REVIEWS (review_id, review_comment, review_star, ep_id) VALUES
(1, 'great bro', 0, 1),
(2, 'niiceee', 2, 2),
(3, 'bad.', 5, 3),
(4, 'great.', 1, 2),
(5, 'relaxed', 1, 1),
(6, 'boring', 1, 2),
(7, 'I slept', 2, 2),
(8, 'Interesting', 0, 8),
(9, 'Funny epiosde.', 1, 9),
(10, 'stop please', 0, 10);

SELECT p.pod_name, e.ep_name
FROM PODCASTS p
JOIN Pod_has_Ep pe ON p.pod_id = pe.pod_id
JOIN EPISODES e ON e.ep_id = pe.ep_id;
