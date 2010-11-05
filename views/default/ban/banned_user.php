<?php

$icon = elgg_view("profile/icon", array(
	'entity' => $vars['entity'],
	'size' => 'small',
));


$num_bans = count_annotations($vars['entity']->guid, '', '', 'ban_release');

$details = get_annotations($vars['entity']->guid, '', '', 'ban_release', '', 0, 1, 0, 'desc');
if ($details) {
	$secs_left = $details[0]->value - time();
	$hours_left = $secs_left / 3600;
	if ($hours_left < 1) {
		$time_left = sprintf(elgg_echo('ban:hourleft'), '<1');
	} elseif ($hours_left < 2) {
		$time_left = sprintf(elgg_echo('ban:hourleft'), '1');
	} else {
		$time_left = sprintf(elgg_echo('ban:hoursleft'), $hours_left);
	}
} else {
	$time_left = 'forever';
	if ($num_bans == 0) {
		$num_bans = 1;
	}
}

$info = <<<___END
<div class="ban_column ban_name"><b><a href="{$vars['entity']->getUrl()}">{$vars['entity']->name}</a></b></div>
<div class="ban_column ban_reason">{$vars['entity']->ban_reason}</div>
<div class="ban_column ban_count">$num_bans</div>
<div class="ban_column ban_release">$time_left</div>
<div class="clearfloat"></div>
___END;


echo elgg_view_listing($icon, $info);
