<?php
/**
 * Ban user action
 */

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
	$subject = elgg_echo('ban:subject', array(elgg_get_site_entity()->name));
	$message = elgg_echo('ban:body', array($reason, $length));
	notify_user($user->guid, elgg_get_logged_in_user_entity(), $subject, $message, null, 'email');
}

system_message(elgg_echo('ban:add:success', array($user->name)));
forward($referrer);
