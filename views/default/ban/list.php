<?php

// elgg makes it hard to list entities with an alternate view
register_plugin_hook('display', 'view', 'banned_user_view');
function banned_user_view($hook, $type, $return, $params) {
	if ($params['view'] == 'profile/listing') {
		return elgg_view('ban/banned_user', $params['vars']);
	}
}

$offset = (int)get_input('offset');

$guids = ban_get_user_guids(10, $offset);
$users = array();
foreach ($guids as $guid) {
	$users[] = get_entity($guid);
}
?>
<div class="contentWrapper">
<?php
	echo elgg_view_entity_list($users, ban_count_users(), $offset, 10, false);
?>
</div>
