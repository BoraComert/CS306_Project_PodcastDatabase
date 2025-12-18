CREATE DATABASE supodcast_db;
USE supodcast_db;
CREATE TABLE PODCASTS(
pod_name VARCHAR(100) not null,
pod_avg_ep_rating DECIMAL(3,2),
pod_id INT,
PRIMARY KEY (pod_id),
pod_description VARCHAR(100) not null
);
CREATE TABLE CATEGORIES(
category_name VARCHAR(100),
PRIMARY KEY (category_name),
category_description VARCHAR(200)
);
CREATE TABLE EPISODES (
ep_duration INT not null,
ep_name VARCHAR(100) not null,
ep_id INT,
pod_id INT,
PRIMARY KEY (ep_id),
FOREIGN KEY (pod_id) references podcasts(pod_id)
ON DELETE CASCADE
ON UPDATE CASCADE
);
CREATE TABLE REVIEWS (
review_comment VARCHAR(200),
review_id int ,
PRIMARY KEY (review_id),
review_star int,
ep_id INT,
FOREIGN KEY (ep_id) references episodes(ep_id)
ON DELETE CASCADE
);
CREATE TABLE HOSTS (
host_id INT,
host_name VARCHAR(50) not null,
PRIMARY KEY (host_id),
host_country VARCHAR(50),
host_organization VARCHAR(100)
);
CREATE TABLE USERS(
user_id INT,
user_name VARCHAR(200) not null,
PRIMARY KEY(user_id)
);
CREATE TABLE USERPLAYLISTS(
user_id INT,
user_playlistName VARCHAR(100) NOT NULL,
user_playlist_id INT,
PRIMARY KEY(user_playlist_id),
FOREIGN KEY(user_id) references users(user_id)
);

CREATE TABLE Pod_Belongs_to_Cat (
pod_id INT,
category_name VARCHAR(100),
PRIMARY KEY (pod_id,category_name),
FOREIGN KEY (pod_id) REFERENCES PODCASTS(pod_id),
FOREIGN KEY (category_name) REFERENCES
CATEGORIES(category_name)
);
CREATE TABLE Ep_Hosted_by_Host (
ep_id INT,
host_id INT,
PRIMARY KEY (ep_id,host_id),
FOREIGN KEY (ep_id) REFERENCES EPISODES(ep_id),
FOREIGN KEY (host_id) REFERENCES HOSTS(host_id)
);
CREATE TABLE Ep_Added_to_UserPlaylist (
ep_id int,
user_playlist_id INT,
PRIMARY KEY (user_playlist_id, ep_id),
FOREIGN KEY (ep_id) REFERENCES EPISODES(ep_id)
ON DELETE CASCADE
ON UPDATE CASCADE,
FOREIGN KEY (user_playlist_id) REFERENCES
USERPLAYLISTS(user_playlist_id)
ON DELETE NO ACTION
ON UPDATE CASCADE
);
