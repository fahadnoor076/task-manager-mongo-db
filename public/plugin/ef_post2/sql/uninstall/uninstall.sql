
DROP TABLE IF EXISTS `pl_attachment`;
DROP TABLE IF EXISTS `pl_attachment_type`;
DROP TABLE IF EXISTS `pl_post`;
DROP TABLE IF EXISTS `pl_post_attachment_map`;
DROP TABLE IF EXISTS `pl_post_feedback_type`;
DROP TABLE IF EXISTS `pl_post_schedule_map`;
DROP TABLE IF EXISTS `pl_post_schedule_type`;
DROP TABLE IF EXISTS `pl_tag`;
DROP TABLE IF EXISTS `pl_tag_type`;
DROP TABLE IF EXISTS `pl_tag_type_map`;
DROP TABLE IF EXISTS `pl_post_type`;

DELETE FROM `api_method_field` WHERE `method_uri` IN (
	SELECT uri FROM `api_method`
	WHERE `plugin_identifier` = '{plugin_identifier}-{wildcard_identifier}_{target_identifier}'
);

DELETE FROM `api_method` WHERE `plugin_identifier` = '{plugin_identifier}-{wildcard_identifier}_{target_identifier}';

