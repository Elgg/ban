<?php

$reason = get_input('reason');
$length = get_input('length');
$guid = get_input('guid');
$referrer = urldecode(get_input('referrer'));

$user = get_user($guid);
if (!$user) {
	register_error('ban:add:failure');
	forward($referrer);
}

$release = time() + $length * 60*60;

if ($release) {
	$user->annotate('ban_release', $release);
}
$user->ban($reason);

system_message(sprintf(elgg_echo('ban:add:success'), $user->name));
forward($referrer);