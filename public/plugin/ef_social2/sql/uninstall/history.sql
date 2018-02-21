
DELETE FROM `entity_history` WHERE history_id IN (
	SELECT `history_id` FROM `history` WHERE `identifier` IN ('{wildcard_identifier}_{target_identifier}-comment_add','{wildcard_identifier}_{target_identifier}-comment_edit','{wildcard_identifier}_{target_identifier}-comment_delete','{wildcard_identifier}_{target_identifier}-review_add','{wildcard_identifier}_{target_identifier}-review_edit','{wildcard_identifier}_{target_identifier}-review_delete','{wildcard_identifier}_{target_identifier}-like_add','{wildcard_identifier}_{target_identifier}-like_edit','{wildcard_identifier}_{target_identifier}-like_delete','{wildcard_identifier}_{target_identifier}-share','{target_identifier}-follower_add','{target_identifier}-follower_delete')
);


DELETE FROM `history_notification` WHERE `history_identifier` IN ('{wildcard_identifier}_{target_identifier}-comment_add','{wildcard_identifier}_{target_identifier}-review_add','{wildcard_identifier}_{target_identifier}-like_add','{target_identifier}_follower_add');


DELETE FROM `history` WHERE `identifier` IN ('{wildcard_identifier}_{target_identifier}-comment_add','{wildcard_identifier}_{target_identifier}-comment_edit','{wildcard_identifier}_{target_identifier}-comment_delete','{wildcard_identifier}_{target_identifier}-review_add','{wildcard_identifier}_{target_identifier}-review_edit','{wildcard_identifier}_{target_identifier}-review_delete','{wildcard_identifier}_{target_identifier}-like_add','{wildcard_identifier}_{target_identifier}-like_edit','{wildcard_identifier}_{target_identifier}-like_delete','{wildcard_identifier}_{target_identifier}-share','{target_identifier}-follower_add','{target_identifier}-follower_delete');


DELETE FROM `entity_history` WHERE `plugin_identifier` = '{plugin_identifier}_{wildcard_identifier}_{target_identifier}';