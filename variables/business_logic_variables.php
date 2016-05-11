<?php

$legal_params_pending_submission = array('limit', 'order', 'page');
$legal_params_country_artists = array('limit', 'order', 'page');

$maximum_submission_of_each_type = 5;

$default_value_limit_param = 20;

$default_order_get_deletions = " ORDER BY S.deletion_creation_date";
$default_order_get_additions = " ORDER BY S.addition_creation_date";


?>
