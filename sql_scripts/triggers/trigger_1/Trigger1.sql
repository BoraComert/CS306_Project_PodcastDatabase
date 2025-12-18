
use supodcast_db;


DELIMITER //

CREATE TRIGGER update_podcast_rating
AFTER INSERT on reviews

FOR EACH ROW

BEGIN

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
    
END //

DELIMITER ;