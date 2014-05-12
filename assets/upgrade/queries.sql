ALTER TABLE `#__wpl_properties` CHANGE `mls_id` `mls_id` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(99, 'style', 'Googlefont', 0, '', 1, 'wpl-google-font', 'http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic|Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Scada:400italic,700italic,400,700|Archivo+Narrow:400,40', '', '', '1', '', 0, 35.00, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(31, 'shortcode', 'my profile shortcode', 0, 'it used for showing my profile', 1, 'wpl_my_profile', 'wpl_html->load_profile_wizard', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(34, 'shortcode', 'Profile show shortcode', 0, 'it used for showing a profile', 1, 'wpl_profile_show', 'wpl_controller->f:profile_show:display', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(32, 'shortcode', 'Add/Edit listing shortcode', 0, 'it used for showing Add/Edit listing view', 1, 'wpl_add_edit_listing', 'wpl_html->load_add_edit_listing', '', '', '', '', 0, 99.99, 2),
(33, 'shortcode', 'Listing Manager shortcode', 0, 'it used for showing Listing Manager', 1, 'wpl_listing_manager', 'wpl_html->load_listing_manager', '', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(20, 'widget', 'WPL Agents Widget', 0, '', 1, 'widgets.agents.main', 'widgets_init', 'WPL_agents_widget', '', '', '', 0, 99.99, 2);

INSERT INTO `#__wpl_cronjobs` (`id`, `cronjob_name`, `period`, `class_location`, `class_name`, `function_name`, `params`, `enabled`, `latest_run`) VALUES
(3, 'Check All Updates', 24, 'global', 'wpl_global', 'check_all_update', '', 1, '2014-04-05 13:19:29');

UPDATE `#__wpl_cronjobs` SET `cronjob_name`='Remove Expired tmp Directories' WHERE `id`='2';

UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='8';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='13';
UPDATE `#__wpl_dbst` SET `editable`='1' WHERE `id`='14';

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(21, 'sidebar', 'Property Show Bottom', 0, 'Appears on bottom of single property/property show page', 1, 'wpl-pshow-bottom', '', '', '', '', '', 0, 99.99, 2),
(22, 'sidebar', 'Profile Show Top', 0, 'Appears on top of agent show/profile show page', 1, 'wpl-profileshow-top', '', '', '', '', '', 0, 99.99, 2);

UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='2';
UPDATE `#__wpl_dbst` SET `deletable`='0' WHERE `id`='3';

ALTER TABLE `#__wpl_activities` ADD `association_type` TINYINT( 4 ) NOT NULL DEFAULT '1', ADD `associations` TEXT NULL;

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(50, 'backend_listing_target_page', NULL, 1, 4, 'wppages', 'Backend Listing Target', '{"tooltip":"Used for backend views"}', '{"show_empty":1} ', 99.00);

ALTER TABLE `#__wpl_users` ADD `access_change_user` TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `access_public_profile`;

INSERT INTO `#__wpl_settings` (`id`, `setting_name`, `setting_value`, `showable`, `category`, `type`, `title`, `params`, `options`, `index`) VALUES
(36, 'realtyna_username', NULL, 0, 1, 'text', '', '', '', 99.00),
(37, 'realtyna_password', NULL, 0, 1, 'text', '', '', '', 99.00),
(38, 'realtyna_verified', '0', 0, 1, 'text', '', '', '', 99.00);

INSERT INTO `#__wpl_extensions` (`id`, `type`, `title`, `parent`, `description`, `enabled`, `param1`, `param2`, `param3`, `param4`, `param5`, `params`, `editable`, `index`, `client`) VALUES
(105, 'javascript', 'AjaxFileUpload', 0, '', 1, 'ajaxFileUpload', 'js/libs/bower_components/ajaxfileupload.min.js', '', '', '', '', 0, 100.00, 2),
(104, 'javascript', 'HoverIntent', 0, '', 1, 'hoverIntent', 'js/libs/bower_components/hoverintent/jquery.hoverIntent.js', '', '', '', '', 0, 100.00, 1),
(103, 'javascript', 'Transit', 0, '', 1, 'transit', 'js/libs/bower_components/transit/jquery.transit.min.js', '', '', '', '', 0, 100.00, 1),
(102, 'javascript', 'customScrollBarJS', 0, '', 1, 'customScrollBarJS', 'js/libs/bower_components/malihu-custom-scrollbar-plugin-bower/jquery.mCustomScrollbar.concat.min.js', '', '', '', '', 0, 100.00, 1),
(101, 'javascript', 'Chosen', 0, '', 1, 'ChosenJS', 'js/libs/bower_components/chosen/public/chosen.jquery.min.js', '', '', '', '', 0, 100.00, 1);

DELETE FROM `#__wpl_extensions` WHERE `id`='93';
DELETE FROM `#__wpl_extensions` WHERE `id`='95';

UPDATE `#__wpl_activities` SET `activity`='agent_info:profileshow' WHERE `id`='12';

UPDATE `#__wpl_activities` SET `association_type`='1';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='98';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='102';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='89';
UPDATE `#__wpl_extensions` SET `client`='2' WHERE `id`='101';

INSERT INTO `#__wpl_units` (`id`, `name`, `type`, `enabled`, `tosi`, `index`, `extra`, `extra2`, `extra3`, `extra4`, `seperator`, `d_seperator`, `after_before`) VALUES
(7, 'Hectare', 2, 0, 10000, 7, '', '', '', '', '', '', 0);

UPDATE `#__wpl_activities` SET `position`='pshow_video', `enabled`='0' WHERE `id`='11';