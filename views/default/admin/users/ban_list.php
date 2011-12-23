<?php
/**
 * List banned users
 */

// elgg makes it hard to list entities with an alternate view
elgg_register_plugin_hook_handler('view', 'user/default', 'banned_user_view');
function banned_user_view($hook, $type, $return, $params) {
	return elgg_view('ban/banned_user', $params['vars']);
}


$joins = array(
	"JOIN {$CONFIG->dbprefix}users_entity u on e.guid = u.guid",
);

$params = array(
	'type'   => 'user',
	'joins'  => $joins,
	'wheres' => array("u.banned = 'yes'"),
	'full_view' => FALSE,
);


$list = elgg_list_entities_from_metadata($params);
if ($list) {
	echo $list;
} else {
	echo elgg_echo('ban:none');
}
