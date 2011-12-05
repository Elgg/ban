<?php
/**
 * Elgg ban user
 *
 * @package Elgg
 * @subpackage Core
 */


$access_status = access_get_show_hidden_status();
access_show_hidden_entities(true);

// Get the user
$guid = get_input('guid');
$user = get_entity($guid);

if ($user instanceof ElggUser) {
	if ($user->unban()) {
		// remove ban_release annotations.
		$releases = elgg_get_annotations($user->guid, '', '', 'ban_release', '', 0, 10, 0, 'desc');

		if ($releases) {
			foreach ($releases as $release) {
				$release->delete();
			}
		}

		system_message(elgg_echo('admin:user:unban:yes'));
	} else {
		register_error(elgg_echo('admin:user:unban:no'));
	}
} else {
	register_error(elgg_echo('admin:user:unban:no'));
}

access_show_hidden_entities($access_status);

forward($_SERVER['HTTP_REFERER']);
