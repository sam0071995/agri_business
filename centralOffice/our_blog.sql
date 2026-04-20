/*
SQLyog Professional v12.09 (64 bit)
MySQL - 5.7.19 : Database - adminhtml
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`adminhtml` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `our_blog`;

/*Table structure for table `master_menu` */

DROP TABLE IF EXISTS `master_menu`;

CREATE TABLE `master_menu` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `page_title` varchar(100) DEFAULT NULL,
  `page_name` varchar(100) DEFAULT NULL,
  `master_id` int(10) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `status` enum('0','1') DEFAULT '1',
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `master_menu` */

insert  into `master_menu`(`id`,`page_title`,`page_name`,`master_id`,`date`,`status`) values (4,'ADMIN','#',0,NULL,'1'),(5,'MAIN MENU','#',4,NULL,'1'),(6,'Menu 1','index.php',5,NULL,'1'),(7,'Menu 2','index.php',5,NULL,'1');

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `added_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` enum('0','1') DEFAULT '0',
  `login_status` enum('0','1') DEFAULT '0',
  `login_time` varchar(100) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/*Data for the table `user` */

insert  into `user`(`id`,`name`,`email`,`mobile`,`username`,`password`,`added_date`,`updated_date`,`date`,`status`,`login_status`,`login_time`) values (4,'Ajit Nandvana','nandvanaajit@gmail.com','8758030207','admin','admin','2018-07-02 12:01:34','2018-07-02 12:01:38','2018-07-02','1','0',''),(5,NULL,'nandvanaajit@gmail.com','9687641497','ajitnandvana','123456','2018-07-02 12:05:38',NULL,'2018-07-02','1','1','1530513484');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
