use supodcast_db;

DELIMITER //

create procedure add_ep_to_userplaylist( in pl_user_id int,in  pl_playlist_id int ,in pl_ep_id int)

begin

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

end //

DELIMITER ;


