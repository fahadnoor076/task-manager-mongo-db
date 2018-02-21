DROP TABLE `{wildcard_identifier}_comment`;
DROP TABLE `{wildcard_identifier}_review`;
DROP TABLE `{wildcard_identifier}_like`;
DROP TABLE `{target_identifier}_follower`;

DELETE FROM `api_method_field` WHERE `method_uri` IN (
	SELECT uri FROM `api_method`
	WHERE `plugin_identifier`= '{plugin_identifier}_{wildcard_identifier}_{target_identifier}'
);

DELETE FROM `api_method` WHERE `plugin_identifier`= '{plugin_identifier}_{wildcard_identifier}_{target_identifier}';

