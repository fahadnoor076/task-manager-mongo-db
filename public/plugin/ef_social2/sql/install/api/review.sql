INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Review : Add','social/{wildcard_identifier}/{target_identifier}/review/add','API request to add review','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,221,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/review/add','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/add','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/add','required','text','review','Review',3,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/add','optional','text','reference_data','Reference Data (JSON string OR Text)',4,'{wildcard_datetime}');



INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Review : Edit','social/{wildcard_identifier}/{target_identifier}/review/edit','API request to edit review','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,222,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/review/edit','required','int','{wildcard_identifier}_review_id','{wildcard_ucword} Review ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/edit','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/edit','required','text','review','Review',3,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/edit','optional','text','reference_data','Reference Data (JSON string OR Text)',4,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Review : Delete','social/{wildcard_identifier}/{target_identifier}/review/delete','API request to delete review','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,223,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/review/delete','required','int','{wildcard_identifier}_review_id','{wildcard_ucword} Review ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/delete','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Review : Get','social/{wildcard_identifier}/{target_identifier}/review/get','API request to get review','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,224,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/review/get','required','int','{wildcard_identifier}_review_id','{wildcard_ucword} Review ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/get','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Social : Review : Get All','social/{wildcard_identifier}/{target_identifier}/review/get_all','API request to get all reviews in {wildcard_identifier}','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,225,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('social/{wildcard_identifier}/{target_identifier}/review/get_all','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/get_all','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/comment/get_all','optional','text','keyword','{target_ucword} Name',3,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/get_all','optional','int','page_no','Page Number default : 1',4,'{wildcard_datetime}'),
('social/{wildcard_identifier}/{target_identifier}/review/get_all','optional','int','limit','Limit default : 10',5,'{wildcard_datetime}');