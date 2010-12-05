<?php

$reason = get_input('reason');
$length = get_input('length');
$guid = get_input('guid');
$referrer = urldecode(get_input('referrer'));
$notify = get_input('notify');

$user = get_user($guid);
if (!$user) {
	register_error('ban:add:failure');
	forward($referrer);
}

if ($length) {
	$release = time() + $length * 60*60;
	$user->annotate('ban_release', $release);
}

$user->ban($reason);

if ($notify !== '0') {
	$subject = sprintf(elgg_echo('ban:subject'), $CONFIG->site->name);
	$message = sprintf(elgg_echo('ban:body'), $reason, $length);
	notify_user($user->guid, get_loggedin_userid(), $subject, $message, null, 'email');
}

system_message(sprintf(elgg_echo('ban:add:success'), $user->name));
forward($referrer);
