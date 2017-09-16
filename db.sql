-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Авг 24 2017 г., 07:12
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `monstercms`
--

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_module_page`
--

CREATE TABLE IF NOT EXISTS `mcms_module_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_create` int(11) NOT NULL,
  `date_update` int(11) NOT NULL,
  `url_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_page_url_idx` (`url_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `mcms_module_page`
--

INSERT INTO `mcms_module_page` (`id`, `date_create`, `date_update`, `url_id`, `name`) VALUES
(3, 1502033915, 1502033915, 3, 'dsdsds'),
(4, 1502033999, 1502033999, 4, 'fdsaffdsafd'),
(5, 1502034058, 1502034058, 5, 'fdsafdsafdsa'),
(6, 1502034117, 1502034117, 6, 'fdsa'),
(7, 1502034242, 1502034242, 7, 'fdsafdsaf'),
(8, 1502034376, 1502034376, 8, 'jhfjhfjgf'),
(10, 1502035307, 1502570756, 10, 'fdsafdsaf');

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_module_page_images`
--

CREATE TABLE IF NOT EXISTS `mcms_module_page_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `pos` int(11) NOT NULL,
  `widgets_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_images_widgets_idx` (`widgets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_module_site_menu`
--

CREATE TABLE IF NOT EXISTS `mcms_module_site_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `cache` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `mcms_module_site_menu`
--

INSERT INTO `mcms_module_site_menu` (`id`, `name`, `cache`) VALUES
(1, 'Левый сайдбар', ''),
(2, 'Футер - Category', ''),
(3, 'Футер - Our Account', '');

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_module_site_menu_items`
--

CREATE TABLE IF NOT EXISTS `mcms_module_site_menu_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `url_id` int(11) NOT NULL,
  `url` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `css_class` varchar(255) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `target` varchar(50) NOT NULL,
  `pos` int(11) NOT NULL,
  `index` int(1) NOT NULL DEFAULT '0',
  `hide` int(1) NOT NULL DEFAULT '0',
  `object_id` int(11) NOT NULL,
  `child_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `pos` (`pos`),
  KEY `index` (`index`),
  KEY `fk_menu_item_url_idx` (`url_id`),
  KEY `fk_menu_item_menu_idx` (`menu_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `mcms_module_site_menu_items`
--

INSERT INTO `mcms_module_site_menu_items` (`id`, `name`, `module`, `item_type`, `url_id`, `url`, `parent_id`, `css_class`, `menu_id`, `target`, `pos`, `index`, `hide`, `object_id`, `child_count`) VALUES
(2, 'fdsafdsaf', 'Page', 'page_text', 10, '', 0, '', 1, '', 0, 1, 0, 10, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_module_widgets`
--

CREATE TABLE IF NOT EXISTS `mcms_module_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget` varchar(255) NOT NULL,
  `cache` text NOT NULL,
  `pos` int(11) NOT NULL,
  `object_id` int(11) NOT NULL,
  `css_class` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=55 ;

--
-- Дамп данных таблицы `mcms_module_widgets`
--

INSERT INTO `mcms_module_widgets` (`id`, `widget`, `cache`, `pos`, `object_id`, `css_class`) VALUES
(2, 'Heading', '<h2>Главная</h2>\r\n', 2, 10, ''),
(22, 'Text', '<div  class="fdsfds"    ><p><img src="/Upload/Widgets/10/50f1389820f18cdd5e6650e3442392aa.jpg" style="margin-left: 10px; margin-right: 10px; float: left; width: 300px; height: 224px;" /> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla.</p>\r\n</div>', 7, 10, 'fdsfds'),
(50, 'Code', '<div >\r\n    <pre><code id="code7683">safdsafsdfsd</code></pre>\r\n    <script>\r\n        $(function(){\r\n            $(document).ready(function() {\r\n                $(''#code7683'').each(function(i, block) {\r\n                    console.log(''block'');\r\n                    console.dir(block);\r\n                    hljs.highlightBlock(block);\r\n                });\r\n            });\r\n        });\r\n    </script>\r\n</div>\r\n', 9, 10, ''),
(52, 'Line', '<hr style="color:red" />\r\n', 8, 10, ''),
(53, 'Line', '<hr style="color:red" />\r\n', 10, 10, ''),
(54, 'Line', '<hr style="color:red" />\r\n', 11, 10, '');

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_module_widgets_options`
--

CREATE TABLE IF NOT EXISTS `mcms_module_widgets_options` (
  `widget_id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`widget_id`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `mcms_module_widgets_options`
--

INSERT INTO `mcms_module_widgets_options` (`widget_id`, `key`, `value`) VALUES
(2, 'heading', 'Главная'),
(2, 'level', '2'),
(22, 'css_class', 'fdsfds'),
(22, 'id', ''),
(22, 'text', '<p><img src="/Upload/Widgets/10/50f1389820f18cdd5e6650e3442392aa.jpg" style="margin-left: 10px; margin-right: 10px; float: left; width: 300px; height: 224px;" /> Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, nulla.</p>\r\n'),
(50, 'code', 'safdsafsdfsd'),
(50, 'css_class', ''),
(50, 'id', ''),
(50, 'language', ''),
(52, 'heading', ''),
(52, 'level', '1'),
(53, 'heading', ''),
(53, 'level', '1'),
(54, 'heading', ''),
(54, 'level', '1');

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_page_semantic`
--

CREATE TABLE IF NOT EXISTS `mcms_page_semantic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL,
  `module` varchar(255) NOT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_canonical` varchar(255) DEFAULT NULL,
  `seo_noindex` int(1) DEFAULT NULL,
  `theme` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`,`module`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `mcms_page_semantic`
--

INSERT INTO `mcms_page_semantic` (`id`, `object_id`, `module`, `seo_title`, `seo_description`, `seo_keywords`, `seo_canonical`, `seo_noindex`, `theme`) VALUES
(1, 1, 'Page', '', '', '', '', 0, NULL),
(2, 2, 'Page', '', '', '', '', 0, NULL),
(3, 3, 'Page', '', '', '', '', 0, NULL),
(4, 4, 'Page', '', '', '', '', 0, NULL),
(5, 5, 'Page', '', '', '', '', 0, NULL),
(6, 6, 'Page', '', '', '', '', 0, NULL),
(7, 7, 'Page', '', '', '', '', 0, NULL),
(8, 8, 'Page', '', '', '', '', 0, NULL),
(9, 9, 'Page', '', '', '', '', 0, NULL),
(10, 10, 'Page', '', '', '', '', 0, 'Surfhouse/Home');

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_urls`
--

CREATE TABLE IF NOT EXISTS `mcms_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `options` text NOT NULL,
  `module` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `object_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Дамп данных таблицы `mcms_urls`
--

INSERT INTO `mcms_urls` (`id`, `url`, `options`, `module`, `action`, `object_id`) VALUES
(1, 'index', '', 'Page', 'View', 1),
(2, 'index2', '', 'Page', 'View', 2),
(3, 'dsdsds', '', 'Page', 'View', 3),
(4, 'fdsafdsa', '', 'Page', 'View', 4),
(5, 'fdsafdsafdsa', '', 'Page', 'View', 5),
(6, 'fdsafdsafds', '', 'Page', 'View', 6),
(7, 'fdsafdsaee', '', 'Page', 'View', 7),
(8, 'jhfjhgyyyyy', '', 'Page', 'View', 8),
(10, 'kitesurf2', '', 'Page', 'View', 10);

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_users`
--

CREATE TABLE IF NOT EXISTS `mcms_users` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `hash` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Дамп данных таблицы `mcms_users`
--

INSERT INTO `mcms_users` (`id_user`, `login`, `password`, `hash`) VALUES
(1, 'delta', 'b6a5c1e024f7381b2f5595c3e26b86cf', NULL),
(72, 'admin', '8561758c35128a174051b17bd2b52ffa', NULL),
(73, 'raymin', '69270d667b51ba2bda135af332c2ac7c', NULL),
(75, 'sutadmin', '3a640e8b3aedc605432f8d32c151a167', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `mcms_users_data`
--

CREATE TABLE IF NOT EXISTS `mcms_users_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `name_conf` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name_conf` (`name_conf`),
  KEY `fk_user_data_user_idx` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `mcms_users_data`
--

INSERT INTO `mcms_users_data` (`id`, `id_user`, `name_conf`, `value`) VALUES
(1, 1, 'name', 'Данила'),
(2, 1, 'job', '3'),
(3, 72, 'name', 'Админ'),
(4, 72, 'job', '3'),
(5, 73, 'name', 'raymin'),
(6, 73, 'job', '3');

-- --------------------------------------------------------

--
-- Структура таблицы `unit_test_db`
--

CREATE TABLE IF NOT EXISTS `unit_test_db` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `addres` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

--
-- Дамп данных таблицы `unit_test_db`
--

INSERT INTO `unit_test_db` (`id`, `name`, `phone`, `addres`) VALUES
(32, '1239496819', '1189177208', '1105156848'),
(33, '1208987192', '1381820851', '1201090730'),
(34, '1055362834', '1050995377', '1396862923'),
(35, '1318987037', '1375188323', '1055700718'),
(36, '1307962024', '1302843715', '1141835978'),
(37, '1267941603', '1226882502', '1380031320'),
(38, '1378767385', '1374149645', '1004304885'),
(39, '1131111306', '1291580932', '1266327270'),
(40, '1297312437', '1300453502', '1384223578'),
(41, '1316859623', '1115468552', '1400467014'),
(42, '1163460521', '1236681123', '1197912122'),
(44, '1281244201', '1176863232', '1093643781'),
(45, '1056676825', '1090803057', '1123615298');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `mcms_users_data`
--
ALTER TABLE `mcms_users_data`
  ADD CONSTRAINT `fk_user_data_user` FOREIGN KEY (`id_user`) REFERENCES `mcms_users` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;
