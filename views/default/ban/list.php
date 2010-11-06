<?php

// elgg makes it hard to list entities with an alternate view
register_plugin_hook('display', 'view', 'banned_user_view');
function banned_user_view($hook, $type, $return, $params) {
	if ($params['view'] == 'profile/listing') {
		return elgg_view('ban/banned_user', $params['vars']);
	}
}


$joins = array(
	"JOIN {$CONFIG->dbprefix}users_entity u on e.guid = u.guid",
);

$params = array(
	'type'   => 'user',
	'joins'  => $joins,
	'wheres' => array("u.banned='yes'"),
	'full_view' => FALSE,
);

?>
<div class="contentWrapper members">
<?php
	$list = elgg_list_entities_from_metadata($params);
	if ($list) {
		echo $list;
	} else {
		echo elgg_echo('ban:none');
	}
?>
</div>
