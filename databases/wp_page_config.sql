CREATE TABLE `wp_page_config` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `element_name_id` varchar(255) DEFAULT NULL,
  `content` varchar(2048) DEFAULT NULL,
  `image` varchar(2048) DEFAULT NULL,
  `status` varchar(1) NOT NULL DEFAULT 'A',
  `created_by` varchar(255) DEFAULT NULL,
  `created_on` datetime DEFAULT CURRENT_TIMESTAMP,
  `modified_by` varchar(255) DEFAULT NULL,
  `modified_on` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
