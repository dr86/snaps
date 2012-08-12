-- phpMyAdmin SQL Dump
-- version 2.10.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Waktu pembuatan: 12. Agustus 2012 jam 19:06
-- Versi Server: 5.0.45
-- Versi PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Database: `db_snap`
-- 

-- --------------------------------------------------------

-- 
-- Struktur dari tabel `snap_contents`
-- 

CREATE TABLE `snap_contents` (
  `content_id` bigint(20) unsigned NOT NULL auto_increment,
  `content_parent` tinyint(3) unsigned default '0',
  `content_type` varchar(255) NOT NULL,
  `content_name` varchar(255) NOT NULL,
  `content_slug` varchar(255) NOT NULL,
  `content_image` varchar(255) default NULL,
  `content_description` text NOT NULL,
  `content_order` tinyint(4) default '0',
  `content_date` datetime NOT NULL,
  `content_status` char(1) NOT NULL,
  PRIMARY KEY  (`content_id`),
  UNIQUE KEY `content_id` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- 
-- Dumping data untuk tabel `snap_contents`
-- 

INSERT INTO `snap_contents` (`content_id`, `content_parent`, `content_type`, `content_name`, `content_slug`, `content_image`, `content_description`, `content_order`, `content_date`, `content_status`) VALUES 
(1, 0, 'page', 'About Us', 'about-us', '', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 2, '2012-05-23 10:32:25', 'L'),
(2, 1, 'page', 'History', 'history', '', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 6, '2012-05-23 10:32:54', 'L'),
(3, 1, 'page', 'Phylosophy', 'phylosophy', '', '', 2, '2012-05-23 10:33:20', 'L'),
(4, 1, 'page', 'Mission', 'mission', '', '', 3, '2012-05-23 10:33:37', 'L'),
(5, 1, 'page', 'Values', 'values', '', '<h2>Teamwork</h2>\r\n<p>&quot;Coming together is beginning. keeping together is  progress. Working together is success.&quot; &ndash; Henry Ford In brand activation, there is no success without teamwork. Therefore  every division in our team holds high commitment towards group effort.<br />\r\nWe also work together with our clients as partners, which enabled us to  understand their products and services, their target market, their  expectation and their goal.</p>\r\n<p align="center"><img src="http://localhost/dsnap/public/uploads/image/img.png" alt="" /></p>\r\n<h2>Efficient</h2>\r\n<p>&quot;The combination of hard work and smart work is efficient work&quot; &ndash; Robert  Half We always strive to understand what our client''s needs to provide the  best alternative solution for Their communication strategy in the most  cost efficient way, without reducing the impact we deliver to your  potential audience.</p>\r\n<h2>Target Driven</h2>\r\n<p>&quot;Being Target Driven can create a higher motivation in works.&quot; &ndash; unknown Every project has its own goals to achieve. We always aim to make those  gools a really by creating marketing strategy that is practical and  achievable. Each member of our team has the experience, professionalism,  energy and drive to exceed targets.</p>\r\n<h2>Agility</h2>\r\n<p>&quot;Agility &ndash; is theme that runs through almost everything we''re taking  about these day. We can''t know what future''s going to look like. We have  to be ogle enough to be able to respond to that uncertainty.&quot; &ndash; Linton  Wells II<br />\r\nWe execute our work from a meticulous planning. But when encountered  with opportunities and challenges we reach our potential by being  flexible. That way we can respond to changes quickly to deliver what our  client needs.</p>', 4, '2012-05-23 10:33:45', 'L'),
(6, 1, 'page', 'Our People', 'our-people', NULL, '<p>Our people make all the differences. We are passionate about what we do, well versed in all aspect of our business, and thrive on the success of our clients.</p>', 5, '2012-05-23 10:34:02', 'L'),
(7, 1, 'page', 'Strategic Partner', 'strategic-partner', '', '', 1, '2012-05-23 10:34:16', 'L'),
(8, 0, 'page', 'Program', 'program', NULL, '', 3, '2012-05-23 10:34:41', 'D'),
(9, 0, 'page', 'Services', 'services', NULL, '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 3, '2012-05-23 10:34:52', 'L'),
(10, 9, 'page', 'Email Marketing', 'email-marketing', NULL, '', 0, '2012-05-23 10:36:54', 'L'),
(11, 0, 'post', 'News 1', 'news-1', 'news-1.png', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 0, '2012-05-23 11:41:06', 'L'),
(12, 0, 'post', 'News 2', 'news-2', NULL, '<p><img width="95" height="110" align="left" src="http://localhost/dsnap/public/uploads/image/img2.png" alt="" />Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<span class="page-break"><!--more--></span></p>\r\n<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.<br />\r\n<br />\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 0, '2012-05-23 11:59:10', 'L'),
(13, 0, 'page', 'Portfolio', 'portfolio', '', '', 4, '2012-05-24 10:34:37', 'L'),
(16, 0, 'portfolio', 'Portfolio 1', 'portfolio-1', 'portfolio-1.png', '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim. Sed tortor. Curabitur molestie.</p>\r\n<p>Duis velit augue, condimentum at, ultrices a, luctus ut, orci. Donec pellentesque egestas eros. Integer cursus, augue in cursus faucibus, eros pede bibendum sem, in tempus tellus justo quis ligula. Etiam eget tortor. Vestibulum rutrum, est ut placerat elementum, lectus nisl aliquam velit, tempor aliquam eros nunc nonummy metus. In eros metus, gravida a, gravida sed, lobortis id, turpis. Ut ultrices, ipsum at venenatis fringilla, sem nulla lacinia tellus, eget aliquet turpis mauris non enim. Nam turpis. Suspendisse lacinia. Curabitur ac tortor ut ipsum egestas elementum. Nunc imperdiet gravida mauris.</p>', 0, '2012-05-24 11:29:00', 'L'),
(17, 0, 'portfolio', 'Portfolio 2', 'portfolio-2', 'portfolio-2.png', '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim. Sed tortor. Curabitur molestie.<br />\r\n<br />\r\nDuis velit augue, condimentum at, ultrices a, luctus ut, orci. Donec pellentesque egestas eros. Integer cursus, augue in cursus faucibus, eros pede bibendum sem, in tempus tellus justo quis ligula. Etiam eget tortor. Vestibulum rutrum, est ut placerat elementum, lectus nisl aliquam velit, tempor aliquam eros nunc nonummy metus. In eros metus, gravida a, gravida sed, lobortis id, turpis. Ut ultrices, ipsum at venenatis fringilla, sem nulla lacinia tellus, eget aliquet turpis mauris non enim. Nam turpis. Suspendisse lacinia. Curabitur ac tortor ut ipsum egestas elementum. Nunc imperdiet gravida mauris.</p>', 0, '2012-05-24 11:30:06', 'L'),
(18, 0, 'page', 'Home', 'home', NULL, '', 1, '2012-05-25 19:08:34', 'L'),
(19, 0, 'page', 'News', 'news', NULL, '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim. Sed tortor. Curabitur molestie.</p>\r\n<p>Duis velit augue, condimentum at, ultrices a, luctus ut, orci. Donec pellentesque egestas eros. Integer cursus, augue in cursus faucibus, eros pede bibendum sem, in tempus tellus justo quis ligula. Etiam eget tortor. Vestibulum rutrum, est ut placerat elementum, lectus nisl aliquam velit, tempor aliquam eros nunc nonummy metus. In eros metus, gravida a, gravida sed, lobortis id, turpis. Ut ultrices, ipsum at venenatis fringilla, sem nulla lacinia tellus, eget aliquet turpis mauris non enim. Nam turpis. Suspendisse lacinia. Curabitur ac tortor ut ipsum egestas elementum. Nunc imperdiet gravida mauris.</p>', 5, '2012-05-25 19:27:49', 'L'),
(20, 0, 'page', 'Contact', 'contact', NULL, '', 6, '2012-05-25 19:28:32', 'L'),
(21, 0, 'portfolio', 'Portfolio 3', 'portfolio-3', 'portfolio-3.png', '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim. Sed tortor. Curabitur molestie.</p>\r\n<p>Duis velit augue, condimentum at, ultrices a, luctus ut, orci. Donec pellentesque egestas eros. Integer cursus, augue in cursus faucibus, eros pede bibendum sem, in tempus tellus justo quis ligula. Etiam eget tortor. Vestibulum rutrum, est ut placerat elementum, lectus nisl aliquam velit, tempor aliquam eros nunc nonummy metus. In eros metus, gravida a, gravida sed, lobortis id, turpis. Ut ultrices, ipsum at venenatis fringilla, sem nulla lacinia tellus, eget aliquet turpis mauris non enim. Nam turpis. Suspendisse lacinia. Curabitur ac tortor ut ipsum egestas elementum. Nunc imperdiet gravida mauris.</p>', 0, '2012-05-25 21:25:28', 'L'),
(22, 9, 'page', 'Direct to Client', 'direct-to-client', NULL, '', 0, '2012-05-25 22:16:53', 'L'),
(23, 9, 'page', 'In-Store Marketing', 'in-store-marketing', NULL, '', 0, '2012-05-25 22:17:15', 'L'),
(24, 9, 'page', 'Sub Contractor to BTL', 'sub-contractor-to-btl', NULL, '', 0, '2012-05-25 22:17:37', 'L'),
(26, 6, 'people', 'Irke Silvany Riasanty', 'irke-silvany-riasanty', NULL, '<p><img width="95" height="110" align="left" alt="" src="http://localhost/dsnap/public/uploads/image/img2(1).png" />Centralize all your customer conversations so nothing gets ignored and everything is searchable from one place. Easily organize, prioritize and engage with others on support requests to ensure your customers get accurate and timely responses. But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure<span class="page-break"><!--more--></span></p>\r\n<p>Centralize all your customer conversations so nothing gets ignored and everything is searchable from one place. Easily organize, prioritize and engage with others on support requests to ensure your customers get accurate and timely responses. But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure</p>\r\n<p>Centralize all your customer conversations so nothing gets ignored and everything is searchable from one place. Easily organize, prioritize and engage with others on support requests to ensure your customers get accurate and timely responses. But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure</p>', 0, '2012-05-27 19:24:42', 'L');

-- --------------------------------------------------------

-- 
-- Struktur dari tabel `snap_content_images`
-- 

CREATE TABLE `snap_content_images` (
  `image_id` bigint(20) unsigned NOT NULL auto_increment,
  `content_id` bigint(20) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  PRIMARY KEY  (`image_id`),
  UNIQUE KEY `image_id` (`image_id`),
  KEY `content_id` (`content_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- 
-- Dumping data untuk tabel `snap_content_images`
-- 

INSERT INTO `snap_content_images` (`image_id`, `content_id`, `filename`) VALUES 
(1, 16, 'portfolio-1-detailgallery.png'),
(2, 16, 'portfolio-1-detailgallery2.png'),
(3, 16, 'portfolio-1-detailgallery3.png');

-- --------------------------------------------------------

-- 
-- Struktur dari tabel `snap_content_profiles`
-- 

CREATE TABLE `snap_content_profiles` (
  `profile_id` bigint(20) unsigned NOT NULL auto_increment,
  `profile_name` varchar(255) NOT NULL,
  `profile_value` text NOT NULL,
  PRIMARY KEY  (`profile_id`,`profile_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

-- 
-- Dumping data untuk tabel `snap_content_profiles`
-- 

INSERT INTO `snap_content_profiles` (`profile_id`, `profile_name`, `profile_value`) VALUES 
(26, 'file_name', 'irke-silvany-riasanty.pdf'),
(26, 'file_type', 'pdf');

-- --------------------------------------------------------

-- 
-- Struktur dari tabel `snap_options`
-- 

CREATE TABLE `snap_options` (
  `option_id` bigint(20) unsigned NOT NULL auto_increment,
  `option_name` varchar(255) NOT NULL,
  `option_value` text NOT NULL,
  PRIMARY KEY  (`option_id`,`option_name`),
  UNIQUE KEY `option_id` (`option_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- 
-- Dumping data untuk tabel `snap_options`
-- 

INSERT INTO `snap_options` (`option_id`, `option_name`, `option_value`) VALUES 
(1, 'option_general', 'a:11:{s:5:"email";s:14:"admin@snap.com";s:9:"show_news";s:1:"5";s:15:"show_portfolios";s:1:"9";s:10:"front_page";s:4:"home";s:9:"news_page";s:4:"news";s:15:"portfolios_page";s:9:"portfolio";s:6:"g_mail";s:20:"dani.gojay@gmail.com";s:6:"g_pass";s:9:"123456789";s:13:"ga_profile_id";i:54153050;s:9:"rss_title";s:15:"Snaps Indonesia";s:3:"rss";a:2:{i:0;s:4:"post";i:1;s:9:"portfolio";}}'),
(2, 'option_social', 'a:3:{s:8:"facebook";s:28:"http://www.facebook.com/snap";s:7:"twitter";s:28:"http://www.facebook.com/snap";s:8:"linkedin";s:31:"http://www.linkedin.com/in/snap";}'),
(3, 'option_office', 'a:1:{i:0;a:5:{s:4:"city";s:15:"Jakarta Selatan";s:7:"address";s:37:"Jl. Mendawai I No. 40A Kebayoran Baru";s:5:"phone";s:13:"021 - 7222337";s:5:"email";s:0:"";s:3:"fax";s:13:"021 - 7253055";}}'),
(4, 'option_analytics', 'a:3:{s:6:"g_mail";s:20:"dani.gojay@gmail.com";s:10:"g_password";s:44:"5LBWxGSWxR96II85qeVvjUAMd0ip+NyCyvdV8W2i6+I=";s:13:"ga_profile_id";i:54153050;}'),
(5, 'gallery_slide', 'a:3:{i:0;s:15:"slide_slide.png";i:1;s:17:"slide_1_slide.png";i:2;s:17:"slide_1_slide.png";}'),
(6, 'widget_page', 'a:6:{s:5:"title";s:4:"Page";s:11:"description";s:53:"Use this widget to add one of your pages as a widget.";s:6:"action";s:5:"about";s:9:"use_count";b:0;s:8:"use_page";b:1;s:4:"data";a:1:{i:2;a:4:{s:5:"title";s:5:"About";s:5:"count";i:0;s:4:"page";i:18;s:4:"menu";N;}}}'),
(7, 'register_widgets', 'a:6:{s:19:"widget_sidebar_left";a:2:{i:0;s:13:"widget_page-2";i:1;s:14:"widget_menus-1";}s:20:"widget_sidebar_right";a:1:{i:0;s:15:"widget_search-1";}s:20:"widget_footer_left_1";a:0:{}s:20:"widget_footer_left_2";a:0:{}s:21:"widget_footer_right_1";a:0:{}s:21:"widget_footer_right_2";a:0:{}}'),
(8, 'widget_menus', 'a:6:{s:5:"title";s:11:"Custom Menu";s:11:"description";s:60:"Use this widget to add one of your custom menus as a widget.";s:6:"action";s:4:"menu";s:9:"use_count";b:0;s:8:"use_menu";b:1;s:4:"data";a:1:{i:1;a:4:{s:5:"title";s:7:"Company";s:5:"count";i:0;s:4:"page";i:0;s:4:"menu";s:12:"menu_company";}}}'),
(9, 'widget_search', 'a:5:{s:5:"title";s:11:"Search Site";s:11:"description";s:27:"A search form for your site";s:6:"action";s:6:"search";s:9:"use_count";b:0;s:4:"data";a:1:{i:1;a:4:{s:5:"title";s:0:"";s:5:"count";i:0;s:4:"page";i:0;s:4:"menu";N;}}}'),
(10, 'widget_feed', 'a:4:{s:5:"title";s:5:"Feeds";s:11:"description";s:34:"Entries from any RSS or Atom feed ";s:6:"action";s:4:"feed";s:9:"use_count";b:0;}'),
(11, 'widget_category', 'a:4:{s:5:"title";s:8:"Category";s:11:"description";s:20:"A list of categories";s:6:"action";s:8:"category";s:9:"use_count";b:0;}'),
(12, 'widget_post', 'a:4:{s:5:"title";s:12:"Recent Posts";s:11:"description";s:34:"The most recent posts on your site";s:6:"action";s:4:"post";s:9:"use_count";b:1;}'),
(13, 'widget_social', 'a:4:{s:5:"title";s:6:"Social";s:11:"description";s:42:"Use this widget to add social as a widget.";s:6:"action";s:6:"social";s:9:"use_count";b:0;}'),
(14, 'navigation', 'menu_navigation'),
(15, 'menu_navigation', 'a:2:{s:4:"name";s:10:"navigation";s:5:"menus";a:6:{i:18;a:5:{s:5:"label";s:4:"Home";s:5:"title";s:4:"Home";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:4:"home";}s:5:"route";s:4:"page";}i:1;a:6:{s:5:"label";s:8:"About Us";s:5:"title";s:8:"About Us";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:8:"about-us";}s:5:"route";s:4:"page";s:5:"pages";a:6:{i:2;a:5:{s:5:"label";s:7:"History";s:5:"title";s:7:"History";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"history";}s:5:"route";s:8:"about-us";}i:3;a:5:{s:5:"label";s:10:"Phylosophy";s:5:"title";s:10:"Phylosophy";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:10:"phylosophy";}s:5:"route";s:8:"about-us";}i:4;a:5:{s:5:"label";s:7:"Mission";s:5:"title";s:7:"Mission";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"mission";}s:5:"route";s:8:"about-us";}i:5;a:5:{s:5:"label";s:6:"Values";s:5:"title";s:6:"Values";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:6:"values";}s:5:"route";s:8:"about-us";}i:6;a:5:{s:5:"label";s:10:"Our People";s:5:"title";s:6:"Values";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:10:"our-people";}s:5:"route";s:8:"about-us";}i:7;a:5:{s:5:"label";s:17:"Strategic Partner";s:5:"title";s:17:"Strategic Partner";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:17:"strategic-partner";}s:5:"route";s:8:"about-us";}}}i:9;a:6:{s:5:"label";s:8:"Services";s:5:"title";s:8:"Services";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:8:"services";}s:5:"route";s:4:"page";s:5:"pages";a:4:{i:10;a:5:{s:5:"label";s:15:"Email Marketing";s:5:"title";s:15:"Email Marketing";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:15:"email-marketing";}s:5:"route";s:8:"services";}i:22;a:5:{s:5:"label";s:16:"Direct to Client";s:5:"title";s:16:"Direct to Client";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:16:"direct-to-client";}s:5:"route";s:8:"services";}i:24;a:5:{s:5:"label";s:21:"Sub Contractor to BTL";s:5:"title";s:21:"Sub Contractor to BTL";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:21:"sub-contractor-to-btl";}s:5:"route";s:8:"services";}i:23;a:5:{s:5:"label";s:18:"In-Store Marketing";s:5:"title";s:18:"In-Store Marketing";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:18:"in-store-marketing";}s:5:"route";s:8:"services";}}}i:19;a:5:{s:5:"label";s:4:"News";s:5:"title";s:4:"News";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:4:"news";}s:5:"route";s:4:"page";}i:13;a:5:{s:5:"label";s:9:"Portfolio";s:5:"title";s:9:"Portfolio";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:9:"portfolio";}s:5:"route";s:4:"page";}i:20;a:5:{s:5:"label";s:7:"Contact";s:5:"title";s:7:"Contact";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"contact";}s:5:"route";s:4:"page";}}}'),
(16, 'menu_company', 'a:2:{s:4:"name";s:7:"company";s:5:"menus";a:7:{i:1;a:5:{s:5:"label";s:8:"About Us";s:5:"title";s:8:"About Us";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:8:"about-us";}s:5:"route";s:4:"page";}i:2;a:5:{s:5:"label";s:7:"History";s:5:"title";s:7:"History";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"history";}s:5:"route";s:4:"page";}i:3;a:5:{s:5:"label";s:10:"Phylosophy";s:5:"title";s:10:"Phylosophy";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:10:"phylosophy";}s:5:"route";s:4:"page";}i:4;a:5:{s:5:"label";s:7:"Mission";s:5:"title";s:7:"Mission";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:7:"mission";}s:5:"route";s:4:"page";}i:5;a:5:{s:5:"label";s:6:"Values";s:5:"title";s:6:"Values";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:6:"values";}s:5:"route";s:4:"page";}i:6;a:5:{s:5:"label";s:10:"Our People";s:5:"title";s:10:"Our People";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:10:"our-people";}s:5:"route";s:4:"page";}i:7;a:5:{s:5:"label";s:17:"Strategic Partner";s:5:"title";s:17:"Strategic Partner";s:10:"controller";s:5:"index";s:6:"params";a:1:{s:5:"title";s:17:"strategic-partner";}s:5:"route";s:4:"page";}}}');

-- --------------------------------------------------------

-- 
-- Struktur dari tabel `snap_users`
-- 

CREATE TABLE `snap_users` (
  `user_id` bigint(20) unsigned NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(25) NOT NULL,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data untuk tabel `snap_users`
-- 

INSERT INTO `snap_users` (`user_id`, `username`, `password`, `firstname`, `lastname`, `email`, `role`) VALUES 
(1, 'snap', 'f5e975aa551d1ae4e91e8ce93b9696b0', 'admin', 'snap', 'admin@snap.com', 'administrator');

-- 
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
-- 

-- 
-- Ketidakleluasaan untuk tabel `snap_content_images`
-- 
ALTER TABLE `snap_content_images`
  ADD CONSTRAINT `snap_content_images_ibfk_1` FOREIGN KEY (`content_id`) REFERENCES `snap_contents` (`content_id`);

-- 
-- Ketidakleluasaan untuk tabel `snap_content_profiles`
-- 
ALTER TABLE `snap_content_profiles`
  ADD CONSTRAINT `snap_content_profiles_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `snap_contents` (`content_id`);
