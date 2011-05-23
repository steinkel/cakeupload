CREATE TABLE `cakeupload_files` (
  `id` int  NOT NULL AUTO_INCREMENT,
  `group` text  DEFAULT NULL,
  `originalFilename` text  NOT NULL COMMENT 'the filename before uploading',
  `uploadedFilename` text  NOT NULL COMMENT 'the filename after uploading',
  `fspath` text  NOT NULL COMMENT 'the filesystem absolute path to the file',
  PRIMARY KEY (`id`)
)
ENGINE = MyISAM
COMMENT = 'uploaded files';
