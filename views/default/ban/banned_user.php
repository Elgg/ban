<?php

$icon = elgg_view("profile/icon", array(
	'entity' => $vars['entity'],
	'size' => 'small',
));

$details = get_annotations($vars['entity']->guid, '', '', 'ban_release', '', 0, 1);
if ($details) {
	$time_left = $details[0]->value - time();
} else {
	$time_left = 'forever';
}

$info = <<<___END
<table>
	<tr>
		<td>{$vars['entity']->name}</td>
		<td>{$vars['entity']->ban_reason}</td>
		<td>$time_left</td>
	</tr>
</table>
___END;


echo elgg_view_listing($icon, $info);
