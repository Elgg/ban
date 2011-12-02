<?php
/**
// Ban User Managing
// Start.php elgg 1.8 only
*/

elgg_register_event_handler('init', 'system', 'ban_init');

function ban_init() {
	
	// Register a page handler, so we can have nice URLs

	elgg_register_page_handler('ban', 'ban_page_handler');
	
	// Resister Event Handler
	elgg_register_event_handler('pagesetup', 'system', 'ban_admin_menu');
	
    // Resister Plugin Hook Handler
	elgg_register_plugin_hook_handler('cron', 'hourly', 'ban_cron');
	elgg_register_plugin_hook_handler('display', 'view', 'menu:user_hover', 'ban_user_hover_menu');
	elgg_register_plugin_hook_handler('register', 'menu:user_hover', 'ban_user_hover_menu');
	
	// Extend the main css view
	elgg_extend_view('css', 'ban/css');

	global $CONFIG;
	$action_path = "{$CONFIG->pluginspath}ban/actions";
	register_action('ban', FALSE, "$action_path/ban.php", TRUE);
	register_action('admin/user/unban', FALSE, "$action_path/unban.php", TRUE);
}

// Ban Page handler
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
//Admin menu
function ban_admin_menu() {
	if (get_context () == 'admin' && isadminloggedin ()) {
		global $CONFIG;
		$url = $CONFIG->wwwroot . 'pg/ban/list/';
		add_submenu_item(elgg_echo('ban:admin_menu'), $url);
	}
}
// hover menu elgg 1.8
function ban_user_hover_menu($hook, $type, $menu, $params) {
	$user = $params['entity'];
	$logged_in_user = elgg_get_logged_in_user_entity();

//don't ban admin himself
	if ($logged_in_user == $user) {
		return $menu;
	}

	$url = "/ban/user/{$params['entity']->username}/guid";
	$menu[] = ElggMenuItem::factory(array(
		'name' => 'ban',
		'text' => elgg_echo('ban:menu'),
		'href' => $url,
		'is_action' => true,
		'section' => 'admin'
	));

	return $menu;
}

/**
 * Rewrite ban link to use our code
 *
 * @param string $hook   Hook name
 * @param string $type   Hook type
 * @param string $return The current view string
 * @param array  $params Parameters from elgg_view()
 * @return string
 */
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
		$releases = get_annotations($user->guid, '', '', 'ban_release', '', 0, 1, 0, 'desc');

		foreach ($releases as $release) {
			if ($release->value < $now) {
				if ($user->unban()) {
					$release->delete();
				}
			}
		}
	}

	elgg_set_ignore_access(false);
}
