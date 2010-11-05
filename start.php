<?php

register_elgg_event_handler('init', 'system', 'ban_init');

function ban_init() {

	register_page_handler('ban', 'ban_page_handler');
	register_elgg_event_handler('pagesetup', 'system', 'ban_admin_menu');

	register_plugin_hook('cron', 'hourly', 'ban_cron');

	elgg_extend_view('css', 'ban/css');

	global $CONFIG;
	$action_path = "{$CONFIG->pluginspath}ban/actions";
	register_action('ban', FALSE, "$action_path/ban.php", TRUE);
	register_action('admin/user/ban', FALSE, "$action_path/ban_redirect.php", TRUE);
}

function ban_page_handler($page) {

	set_context('admin');
	admin_gatekeeper();

	switch ($page[0]) {
		case "user":
			$user = get_user_by_username($page[1]);
			$title = sprintf(elgg_echo('ban:add:title'), $user->name);
			$content = elgg_view('ban/add', array('user' => $user));
			break;
		case "list":
			$title = elgg_echo('ban:list:title');
			$content = elgg_view('ban/list');
			break;
	}

	$content = elgg_view_title($title) . $content;
	$body = elgg_view_layout('two_column_left_sidebar', '', $content);
	page_draw($title, $body);
}

function ban_admin_menu() {
	if (get_context () == 'admin' && isadminloggedin ()) {
		global $CONFIG;
		$url = $CONFIG->wwwroot . 'pg/ban/list/';
		add_submenu_item(elgg_echo('ban:admin_menu'), $url);
	}
}

function ban_cron() {
	global $CONFIG;

	elgg_set_ignore_access();
	
	$params = array(
		'type'   => 'user',
		'annotation_names' => array('ban_release'),
		'joins'  => array("JOIN {$CONFIG->dbprefix}users_entity u on e.guid = u.guid"),
		'wheres' => array("u.banned='yes'"),
	);

	$now = time();

	$users = elgg_get_entities_from_annotations($params);
	foreach ($users as $user) {
		$details = get_annotations($user->guid, '', '', 'ban_release', '', 0, 1, 0, 'desc');
		if ($details->value < $now) {
			$user->unban();
		}
	}

	elgg_set_ignore_access(false);
}
