<?php
/**
 * Catch profile plugin ban and send to our ban page
 */

$guid = get_input('guid');

$user = get_user($guid);

$url = $CONFIG->wwwroot . "pg/ban/user/$user->username/";
forward($url);