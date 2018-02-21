INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Comment : Add','social/{wildcard_identifier}/{target_identifier}/comment/add','API request to add comment','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,201,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/comment/add','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/add','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/add','required','text','comment','Comment',3,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/add','optional','text','reference_data','Reference Data (JSON string OR Text)',4,'{wildcard_datetime}');



INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Comment : Edit','social/{wildcard_identifier}/{target_identifier}/comment/edit','API request to edit comment','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,202,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/comment/edit','required','int','{wildcard_identifier}_comment_id','{wildcard_ucword} Comment ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','required','text','comment','Comment',3,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/edit','optional','text','reference_data','Reference Data (JSON string OR Text)',4,'{wildcard_datetime}');



INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Comment : Delete','social/{wildcard_identifier}/{target_identifier}/comment/delete','API request to delete comment','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,203,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/comment/delete','required','int','{wildcard_identifier}_comment_id','{wildcard_ucword} Comment ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/delete','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Comment : Get','social/{wildcard_identifier}/{target_identifier}/comment/get','API request to get comment','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,204,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/comment/get','required','int','{wildcard_identifier}_comment_id','{wildcard_ucword} Comment ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/get','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}');


INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Comment : Get All','social/{wildcard_identifier}/{target_identifier}/comment/get_all','API request to get all comments in {wildcard_identifier}','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,205,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/comment/get_all','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/get_all','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/get_all','optional','text','keyword','{target_ucword} Name',3,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/get_all','optional','int','page_no','Page Number default : 1',4,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/get_all','optional','int','limit','Limit default : 10',5,'{wildcard_datetime}');