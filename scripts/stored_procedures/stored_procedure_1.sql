use supodcast_db;

DELIMITER //

create procedure createReview( in r_ep_id int , in rating int, in r_comment varchar(200))

begin

declare rid int;



-- if a review doesn't exist for an episode then first rid is set as 1 

if not exists (select 1 from reviews) then
set rid = 1;


-- Generates a new review id based on previous review id count
else 


SELECT MAX(review_id)+1
INTO rid
FROM REVIEWS;

end if;

-- check if an episode actually exists

if not exists (
select ep_id 
from episodes e
where e.ep_id = r_ep_id
) then signal sqlstate '45000' set message_text = 'You cant review an episode that doesnt exist!';

end if;

-- add a constraint on values because the star rating is 1 from 5

if rating < 1 or rating > 5 Then 
signal sqlstate '45000' set message_text = 'Rating should be between 1 and 5';
end if;



insert into reviews (review_id ,review_star,review_comment ,ep_id )
Values(rid,rating,r_comment,r_ep_id);

end //

DELIMITER ;


