<?php

echo elgg_view_entity($vars['user']);

echo '<p>';
echo '<label>' . elgg_echo('ban:reason') . '</label>';
echo elgg_view('input/text', array('internalname' => 'reason'));
echo '</p>';

echo '<p>';
echo '<label>' . elgg_echo('ban:length') . '</label>';
echo elgg_view('input/text', array('internalname' => 'length'));
echo '</p>';

echo '<p>';
$options = array(elgg_echo('ban:notify') => 'yes');
echo elgg_view('input/checkboxes', array('internalname' => 'notify', 'options' => $options));
echo '</p>';

echo elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $vars['user']->guid));

$referrer = urlencode($_SERVER['HTTP_REFERER']);
echo elgg_view('input/hidden', array('internalname' => 'referrer', 'value' => $referrer));

echo elgg_view('input/submit', array('value' => elgg_echo('ban')));
