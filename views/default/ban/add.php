<?php


?>
<div class="contentWrapper">
<?php
	$body = elgg_view('ban/form', $vars);
	$params = array(
		'body' => $body,
		'action' => "{$vars['url']}action/ban/",
	);
	echo elgg_view('input/form', $params);
?>
</div>
