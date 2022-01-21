CREATE TABLE `microblog_db`.`post` ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(20) NOT NULL , `content` VARCHAR(2000) NOT NULL , `username` VARCHAR(20) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `microblog_db`.`user` ( `username` VARCHAR(20) NOT NULL , `pass` VARCHAR(20) NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `post` ADD `date` VARCHAR(100) NOT NULL AFTER `username`;
ALTER TABLE `user` ADD PRIMARY KEY(`username`);
ALTER TABLE `post` ADD FOREIGN KEY (`username`) REFERENCES `user`(`username`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `user` ADD `token` VARCHAR(20) NOT NULL AFTER `pass`;
ALTER TABLE `user` ADD `imgURL` VARCHAR(200) NULL AFTER `token`;
ALTER TABLE `post` ADD `likes` INT(9) NOT NULL DEFAULT '0' AFTER `username`;
CREATE TABLE `microblog_db`.`likes` ( `username` VARCHAR(20) NOT NULL ,  `post_id` INT NOT NULL ) ENGINE = InnoDB;
ALTER TABLE `likes` ADD PRIMARY KEY(`username`, `post_id`);
CREATE TABLE `followers` ( `follower` VARCHAR(20) NULL DEFAULT NULL , `following` VARCHAR(20) NULL DEFAULT NULL ) ENGINE = InnoDB;
ALTER TABLE `followers` ADD PRIMARY KEY(`follower`, `following`);
ALTER TABLE `post` ADD `img` VARCHAR(100) NOT NULL AFTER `date`;
/*
SELECT * FROM post P LEFT JOIN followers F ON P.username = F.following WHERE F.follower = 'teodor'
*/