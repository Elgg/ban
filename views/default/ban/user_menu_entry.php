<?php

$view = elgg_view('output/url', array(
	'href' => "{$vars['url']}pg/ban/user/{$vars['entity']->username}/",
	'text' => elgg_echo('ban:profile_link')
));

echo $view;
