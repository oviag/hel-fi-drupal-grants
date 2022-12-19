<?php

/**
 * @file
 * Contains site specific overrides.
 */

if (getenv('APP_ENV') == 'production') {
  $config['openid_connect.client.tunnistamo']['settings']['is_production'] = TRUE;
  $config['openid_connect.client.tunnistamo']['settings']['environment_url'] = 'https://api.hel.fi/sso';
  $config['openid_connect.client.tunnistamoadmin']['settings']['is_production'] = TRUE;
  $config['openid_connect.client.tunnistamoadmin']['settings']['environment_url'] = 'https://api.hel.fi/sso';
}
else {
  $config['openid_connect.client.tunnistamo']['settings']['environment_url'] = 'https://tunnistamo.test.hel.ninja';
  $config['openid_connect.client.tunnistamo']['settings']['is_production'] = FALSE;
  $config['openid_connect.client.tunnistamoadmin']['settings']['is_production'] = FALSE;
  $config['openid_connect.client.tunnistamoadmin']['settings']['environment_url'] = 'https://tunnistamo.test.hel.ninja';
}

$config['openid_connect.client.tunnistamo']['settings']['client_id'] = getenv('TUNNISTAMO_CLIENT_ID');
$config['openid_connect.client.tunnistamo']['settings']['client_secret'] = getenv('TUNNISTAMO_CLIENT_SECRET');
$config['openid_connect.client.tunnistamo']['settings']['client_scopes'] = getenv('TUNNISTAMO_CLIENT_SCOPES');

$config['openid_connect.client.tunnistamoadmin']['settings']['client_id'] = getenv('TUNNISTAMOADMIN_CLIENT_ID');
$config['openid_connect.client.tunnistamoadmin']['settings']['client_secret'] = getenv('TUNNISTAMOADMIN_CLIENT_SECRET');
$config['openid_connect.client.tunnistamoadmin']['settings']['client_scopes'] = getenv('TUNNISTAMOADMIN_CLIENT_SCOPES');


//$config['helfi_proxy.settings']['default_proxy_domain'] = 'helfi-proxy.docker.so';
$config['helfi_proxy.settings']['prefixes'] = [
  'en' => 'grants',
  'fi' => 'avustukset',
  'sv' => 'bidrags'
];
$config['helfi_proxy.settings']['asset_path'] = 'avustukset-assets';


if ($robots_header_enabled = getenv('DRUPAL_X_ROBOTS_TAG_HEADER')) {
  $config['helfi_proxy.settings']['robots_header_enabled'] = (bool) $robots_header_enabled;
}
