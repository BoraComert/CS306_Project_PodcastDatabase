DELIMITER //

CREATE PROCEDURE createPodcast(
    IN p_name VARCHAR(100),
    IN p_description VARCHAR(100)
)
BEGIN
    -- check is podcast name is present 
    IF p_name IS NULL OR p_name = '' THEN
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Podcast name cannot be empty!';
    END IF;

    -- insert podcast
    INSERT INTO PODCASTS (pod_name, pod_description, pod_avg_ep_rating)
    VALUES (p_name, p_description, NULL);

END //

DELIMITER ;
