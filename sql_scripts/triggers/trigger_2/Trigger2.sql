use supodcast_db;

DELIMITER //

CREATE TRIGGER delete_podcasts_without_episodes
After delete on episodes 
FOR EACH ROW
BEGIN

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
END //

DELIMITER ;