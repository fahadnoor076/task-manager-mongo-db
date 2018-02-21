INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Like : Add','social/{wildcard_identifier}/{target_identifier}/like/add','API request to post Like','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,241,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/like/add','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/add','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/add','optional','text','activity_type_id','Activity Type ID (default:1 - Thumb)',3,'{wildcard_datetime}');


INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Like : Edit','social/{wildcard_identifier}/{target_identifier}/like/edit','API request to edit Like','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,242,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/like/edit','required','int','{wildcard_identifier}_like_id','{wildcard_ucword} Like ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/edit','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/edit','optional','text','activity_type_id','Activity Type ID (default:1 - Thumb)',3,'{wildcard_datetime}');


INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Like : Delete','social/{wildcard_identifier}/{target_identifier}/like/delete','API request to delete Like','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,243,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/like/delete','required','int','{wildcard_identifier}_like_id','{wildcard_ucword} Like ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/delete','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}');


INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Like : Get All','social/{wildcard_identifier}/{target_identifier}/like/get_all','API request to get all likes in {wildcard_identifier}','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,244,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/like/get_all','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/get_all','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/get_all','optional','int','activity_type_id','Activity Type ID',3,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/get_all','optional','text','keyword','{target_ucword} Name',4,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/get_all','optional','int','page_no','Page Number default : 1',5,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/like/get_all','optional','int','limit','Limit default : 10',6,'{wildcard_datetime}');