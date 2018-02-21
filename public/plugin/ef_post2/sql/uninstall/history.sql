
DELETE FROM `entity_history` WHERE history_id IN (
	SELECT `history_id` FROM `history` WHERE `identifier` IN ('test')
);


DELETE FROM `history_notification` WHERE `history_identifier` IN ('test');


DELETE FROM `history` WHERE `identifier` IN ('test');


DELETE FROM `entity_history` WHERE `plugin_identifier` = '{plugin_identifier}-{wildcard_identifier}_{target_identifier}';