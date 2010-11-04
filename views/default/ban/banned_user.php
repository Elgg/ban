<?php

$icon = elgg_view("profile/icon", array(
	'entity' => $vars['entity'],
	'size' => 'small',
));

echo elgg_view_listing($icon, $vars['entity']->ban_reason);
