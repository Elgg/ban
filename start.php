<?php

register_elgg_event_handler('init', 'system', 'ban_init');

function ban_init() {

	register_page_handler('ban', 'ban_page_handler');
	register_elgg_event_handler('pagesetup', 'system', 'ban_admin_menu');

	elgg_extend_view('profile/menu/adminlinks', 'ban/user_menu_entry');

	global $CONFIG;
	$action_path = "{$CONFIG->pluginspath}ban/actions";
	register_action('ban', FALSE, "$action_path/ban.php", TRUE);
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

function ban_count_users() {
	global $CONFIG;

	$query = "SELECT COUNT(*) as total FROM {$CONFIG->dbprefix}users_entity WHERE banned = 'yes'";
	$total = get_data_row($query);
	return (int)$total->total;
}

function ban_get_user_guids($limit = 10, $offset = 0) {
	global $CONFIG;

	$query = "SELECT guid FROM {$CONFIG->dbprefix}users_entity WHERE banned = 'yes'";
	$query .= " LIMIT $offset, $limit";
	$guids = get_data($query);
	array_walk($guids, create_function('&$v', '$v = $v->guid;'));
	return $guids;
}