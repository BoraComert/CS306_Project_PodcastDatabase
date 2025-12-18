DELIMITER //
-- this stored procedure creates a review for a given episode, it is the updated version that includes auto increment
CREATE PROCEDURE createReview(
    IN r_ep_id INT,
    IN rating INT,
    IN r_comment VARCHAR(200)
)
BEGIN
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
END //

DELIMITER ;
