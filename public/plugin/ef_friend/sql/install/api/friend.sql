INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Add','{wildcard_identifier}/friend/add','API request to add friend','{plugin_identifier}_{wildcard_identifier}_friend',1,301,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/add','required','int','{wildcard_pk}','{wildcard_ucword} ID',2,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/add','required','int','target_{wildcard_pk}','Target {wildcard_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Accept','{wildcard_identifier}/friend/accept','API request to accept friend request','{plugin_identifier}_{wildcard_identifier}_friend',1,302,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/accept','required','int','{wildcard_identifier}_friend_id','{wildcard_ucword} Friend ID',1,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/accept','required','int','{wildcard_pk}','{wildcard_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Reject','{wildcard_identifier}/friend/reject','API request to reject friend','{plugin_identifier}_{wildcard_identifier}_friend',1,303,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/reject','required','int','{wildcard_identifier}_friend_id','{wildcard_ucword} Friend ID',1,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/reject','required','int','{wildcard_pk}','{wildcard_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Delete','{wildcard_identifier}/friend/delete','API request to delete friend','{plugin_identifier}_{wildcard_identifier}_friend',1,304,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/delete','required','int','{wildcard_identifier}_friend_id','{wildcard_ucword} Friend ID',1,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/delete','required','int','{wildcard_pk}','{wildcard_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Cancel','{wildcard_identifier}/friend/cancel','API request to cancel friend request','{plugin_identifier}_{wildcard_identifier}_friend',1,305,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/cancel','required','int','{wildcard_identifier}_friend_id','{wildcard_ucword} Friend ID',1,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/cancel','required','int','{wildcard_pk}','{wildcard_ucword} ID',2,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Get All','{wildcard_identifier}/friend/get_all','API request to get all pending requests','{plugin_identifier}_{wildcard_identifier}_friend',1,306,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/get_all','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all','optional','int','status','Friendship Status Accepted/Pending (1/0) (default:1)',2,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all','optional','text','keyword','{wildcard_ucword}  Name',3,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all','optional','int','page_no','Page Number default : 1',4,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all','optional','int','limit','Limit default : 10',5,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Get All Pending','{wildcard_identifier}/friend/get_all_pending','API request to get all pending requests','{plugin_identifier}_{wildcard_identifier}_friend',1,307,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/get_all_pending','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all_pending','optional','text','keyword','{wildcard_ucword}  Name',2,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all_pending','optional','int','page_no','Page Number default : 1',3,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all_pending','optional','int','limit','Limit default : 10',4,'{wildcard_datetime}');




INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('Friend : Get All Sent','{wildcard_identifier}/friend/get_all_sent','API request to get all sent requests','{plugin_identifier}_{wildcard_identifier}_friend',1,308,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values 
('{wildcard_identifier}/friend/get_all_sent','required','int','{wildcard_pk}','{wildcard_ucword} ID',1,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all_sent','optional','text','keyword','{wildcard_ucword}  Name',2,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all_sent','optional','int','page_no','Page Number default : 1',3,'{wildcard_datetime}'),
('{wildcard_identifier}/friend/get_all_sent','optional','int','limit','Limit default : 10',4,'{wildcard_datetime}');