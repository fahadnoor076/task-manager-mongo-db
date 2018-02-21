
INSERT INTO `api_method`(`name`,`uri`,`description`,`plugin_identifier`,`is_active`,`order`,`created_at`) values ('{wildcard_ucword} Post2 : Feedback : Types','post2/{wildcard_identifier}_{target_identifier}/feedback/types','API request to get Feedback Types','{plugin_identifier}-{wildcard_identifier}_{target_identifier}',1,410,'{wildcard_datetime}');

INSERT INTO `api_method_field`(`method_uri`,`type`,`data_type`,`name`,`description`,`order`,`created_at`) values ('post2/{wildcard_identifier}_{target_identifier}/feedback/types','optional','text','order_by','Order By (title [default], identifier, created_at)',1,'{wildcard_datetime}'),
('post2/{wildcard_identifier}_{target_identifier}/feedback/types','optional','text','sorting','Sorting (asc [default], desc)',2,'{wildcard_datetime}');