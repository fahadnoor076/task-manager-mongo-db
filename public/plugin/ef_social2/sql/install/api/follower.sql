INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Social : Follower : Add','social/{target_identifier}/follower/add','API request to add follower','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,261,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('social/{target_identifier}/follower/add','required','int','{target_pk}','{target_ucword} ID',2,'{wildcard_datetime}'),
('social/{target_identifier}/follower/add','required','int','target_{target_pk}','Target {target_ucword} ID',2,'{wildcard_datetime}');



INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Social : Follower : Delete','social/{target_identifier}/follower/delete','API request to delete follower','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,263,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('social/{target_identifier}/follower/delete','required','int','{target_identifier}_follower_id','{target_ucword} Follower ID',1,'{wildcard_datetime}'),
('social/{target_identifier}/follower/delete','required','int','{target_pk}','Target {target_ucword} ID',2,'{wildcard_datetime}');



INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Social : Follower : Get All Followers','social/{target_identifier}/follower/get_all_followers','API request to get all followers','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,264,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('social/{target_identifier}/follower/get_all_followers','required','int','{target_pk}','{target_ucword} ID',1,'{wildcard_datetime}'),
('social/{target_identifier}/follower/get_all_followers','optional','text','keyword','{target_ucword}  Name',2,'{wildcard_datetime}'),
('social/{target_identifier}/follower/get_all_followers','optional','int','page_no','Page Number default : 1',3,'{wildcard_datetime}'),
('social/{target_identifier}/follower/get_all_followers','optional','int','limit','Limit default : 10',4,'{wildcard_datetime}');


INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Social : Follower : Get All Following','social/{target_identifier}/follower/get_all_following','API request to get all {target_identifier}s following me','{plugin_identifier}_{wildcard_identifier}_{target_identifier}',1,265,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('social/{target_identifier}/follower/get_all_following','required','int','{target_pk}','{target_ucword} ID',1,'{wildcard_datetime}'),
('social/{target_identifier}/follower/get_all_following','optional','text','keyword','{target_ucword}  Name',2,'{wildcard_datetime}'),
('social/{target_identifier}/follower/get_all_following','optional','int','page_no','Page Number default : 1',3,'{wildcard_datetime}'),
('social/{target_identifier}/follower/get_all_following','optional','int','limit','Limit default : 10',4,'{wildcard_datetime}');