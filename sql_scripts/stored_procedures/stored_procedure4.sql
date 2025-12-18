use supodcast_db;

DELIMITER //

DROP PROCEDURE IF EXISTS createEpisode//

CREATE PROCEDURE createEpisode(
    IN p_pod_id INT,
    IN e_name VARCHAR(100),
    IN e_duration INT
)
BEGIN
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

END//

DELIMITER ;
