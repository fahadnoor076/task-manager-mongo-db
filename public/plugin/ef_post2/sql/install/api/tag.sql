
INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Post2 : Tag : Types','post2/{wildcard_identifier}_{target_identifier}/tag/types','API request to get Tag Types','{plugin_identifier}-{wildcard_identifier}_{target_identifier}',1,410,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values
('post2/{wildcard_identifier}_{target_identifier}/tag/types','optional','text','order_by','Order By (title [default], identifier, type, created_at)',1,'{wildcard_datetime}'),
('post2/{wildcard_identifier}_{target_identifier}/tag/types','optional','text','sorting','Sorting (asc [default], desc)',2,'{wildcard_datetime}');


INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Post2 : Tags','post2/{wildcard_identifier}_{target_identifier}/tags','API request to get Tags','{plugin_identifier}-{wildcard_identifier}_{target_identifier}',1,420,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('post2/{wildcard_identifier}_{target_identifier}/tags','optional','text','tag_type_id','Tag Type ID',1,'{wildcard_datetime}'),
('post2/{wildcard_identifier}_{target_identifier}/tags','optional','text','order_by','Order By (title [default], identifier, type, created_at)',2,'{wildcard_datetime}'),
('post2/{wildcard_identifier}_{target_identifier}/tags','optional','text','sorting','Sorting (asc [default], desc)',3,'{wildcard_datetime}');