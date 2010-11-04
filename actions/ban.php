<?php

$reason = get_input('reason');
$length = get_input('length');
$guid = get_input('guid');

$user = get_user($guid);
if (!$user) {

}

$release = time() + $length * 60*60;

if ($release) {
	$user->annotate('ban_release', $release);
}
$user->ban($reason);

system_message(sprintf(elgg_echo('ban:add:success'), $user->name));
forward('pg/admin/');