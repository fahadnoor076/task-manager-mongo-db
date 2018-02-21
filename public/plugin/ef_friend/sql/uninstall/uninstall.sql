DROP TABLE `{wildcard_identifier}_friend`;

DELETE FROM `api_method_field` WHERE `method_uri` IN (
	SELECT uri FROM `api_method`
	WHERE `plugin_identifier`= '{plugin_identifier}_{wildcard_identifier}_friend'
);

DELETE FROM `api_method` WHERE `plugin_identifier`= '{plugin_identifier}_{wildcard_identifier}_friend';

