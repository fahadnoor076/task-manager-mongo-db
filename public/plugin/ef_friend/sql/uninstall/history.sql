
DELETE FROM `entity_history` WHERE history_id IN (
	SELECT `history_id` FROM `history` WHERE `identifier` IN ('{wildcard_identifier}-friend_add','{wildcard_identifier}-friend_accept','{wildcard_identifier}-friend_reject','{wildcard_identifier}-friend_cancel_request','{wildcard_identifier}-friend_delete')
);


DELETE FROM `history_notification` WHERE `history_identifier` IN ('{wildcard_identifier}-friend_add','{wildcard_identifier}-friend_accept');


DELETE FROM `history` WHERE `identifier` IN ('{wildcard_identifier}-friend_add','{wildcard_identifier}-friend_accept','{wildcard_identifier}-friend_reject','{wildcard_identifier}-friend_cancel_request','{wildcard_identifier}-friend_delete');