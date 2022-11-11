<?php
$conf['x_frame_options'] = '';
$config['x_frame_options'] = '';
$settings['x_frame_options'] = '';


/**
 * Enable local development services.
 */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';


/**
 * Show all error messages, with backtrace information.
 *
 * In case the error level could not be fetched from the database, as for
 * example the database connection failed, we rely only on this value.
 */
$config['system.logging']['error_level'] = 'verbose';

/**
 * Disable CSS and JS aggregation.
 */
$config['system.performance']['css']['preprocess'] = FALSE;
$config['system.performance']['js']['preprocess'] = FALSE;


/**
 * @file
 * An example of Drupal 9 development environment configuration file.
 */
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';


$settings['file_public_base_url'] = 'https://hel-fi-drupal-grant-applications.docker.so/sites/default/files';
$settings['skip_permissions_hardening'] = TRUE;
$settings['class_loader_auto_detect'] = FALSE;

$settings['twig_debug'] = TRUE;

$config['system.performance']['css']['preprocess'] = 0;
$config['system.performance']['js']['preprocess'] = 0;
$config['system.logging']['error_level'] = 'some';

putenv('AVUSTUS2_ENDPOINT=https://avustus-integration-dev.apps.arodevtest.hel.fi/apply');
putenv('AVUSTUS2_USERNAME=avustusas1test');
putenv('AVUSTUS2_PASSWORD=OJaMfvdWwFOwdR1n0aor');

putenv('AVUSTUS2_LIITE_ENDPOINT=https://avustus-integration-dev.agw.arodevtest.hel.fi/attachment');

putenv('BACKEND_MODE=nondev');

putenv('YJDH_USERNAME=avustustest');
putenv('YJDH_PASSWD=6gbNSLl19ekVvJj2iQpn');
putenv('YJDH_ENDPOINT=https://ytj-integration-dev.agw.arodevtest.hel.fi');

putenv('YRTTI_USERNAME=avustustest');
putenv('YRTTI_PASSWD=uXGIH6V8yWlYm4n76XLV');
putenv('YRTTI_ENDPOINT=https://yrtti-integration-dev.agw.arodevtest.hel.fi');

putenv('USERINFO_ENDPOINT=https://tunnistamo.test.hel.ninja/openid/userinfo');
putenv('USERINFO_PROFILE_ENDPOINT=https://profile-api.test.hel.ninja/graphql/');

//putenv('ATV_API_KEY=t8mGBSyL.eNd0sfHzvG4k1pXbRFA3XibMQL6a36bn');
putenv('ATV_API_KEY=341NQJFl.DtIpp9IoHHI7vN6qs4c8Djw4W0xjaJoX');

putenv('ATV_BASE_URL=https://atv-api-hki-kanslia-atv-test.apps.arodevtest.hel.fi');
putenv('ATV_VERSION=v1');
putenv('ATV_TOKEN_NAME=https://api.hel.fi/auth/atvapidev');
putenv('ATV_SCHEMA_PATH=/app/conf/tietoliikennesanoma_schema.json');

putenv('APP_QUERY_CACHE_TIME=10');



$settings['file_private_path'] = 'sites/default/files/private';

putenv('APP_ENV=local');

putenv('TUNNISTAMO_CLIENT_ID=lomaketyokalu-ui-dev');
putenv('TUNNISTAMO_CLIENT_SECRET=0cf09212235cc0fa16f6b7c3194fc3bde81c7d920ff3b2773a047a7b');

putenv('FORM_TOOL_TOKEN=89s79as87f98as7df98as7df98asf67');


putenv('HP_USER_ROLES=helsinkiprofiili_vahva,helsinkiprofiili_heikko');
putenv('HP_USER_ROLE_STRONG=helsinkiprofiili_vahva');
putenv('HP_USER_ROLE_WEAK=helsinkiprofiili_heikko');

putenv('HP_USER_CLIENT=tunnistamo');
putenv('HP_ADMIN_CLIENT=tunnistamoadmin');

putenv('ADMIN_USER_ROLES=verkkolomake_hallinnoija');




$config['openid_connect.client.tunnistamo']['settings']['client_id'] = 'lomaketyokalu-ui-dev';
$config['openid_connect.client.tunnistamo']['settings']['client_secret'] = '0cf09212235cc0fa16f6b7c3194fc3bde81c7d920ff3b2773a047a7b';

//$config['openid_connect.client.tunnistamo']['settings']['scopes'] = 'openid,profile,email,https://api.hel.fi/auth/helsinkiprofile,https://api.hel.fi/lomaketyokalu';
$config['openid_connect.client.tunnistamo']['settings']['client_scopes'] = 'openid,profile,email,https://api.hel.fi/auth/helsinkiprofile,https://api.hel.fi/avustusasiointi,https://api.hel.fi/auth/lomaketyokaluapidev,https://api.hel.fi/auth/atvapidev';
$config['openid_connect.client.tunnistamo']['settings']['environment_url'] = 'https://tunnistamo.test.hel.ninja';
$config['openid_connect.client.tunnistamo']['settings']['is_production'] = false;
$config['openid_connect.client.tunnistamo']['settings']['client_roles'] = [];

$config['openid_connect.client.tunnistamoadmin']['settings']['client_id'] = 'lomaketyokalu-admin-ui-dev';
$config['openid_connect.client.tunnistamoadmin']['settings']['client_secret'] = '9c560dcc72abae6064d2434b5e98559142b31aadeb451ac5639a00f3';
$config['openid_connect.client.tunnistamoadmin']['settings']['client_scopes'] = 'openid,profile,email,https://api.hel.fi/auth/helsinkiprofile,https://api.hel.fi/avustusasiointi,https://api.hel.fi/auth/lomaketyokaluapidev,https://api.hel.fi/auth/atvapidev';
$config['openid_connect.client.tunnistamoadmin']['settings']['environment_url'] = 'https://tunnistamo.test.hel.ninja';
$config['openid_connect.client.tunnistamoadmin']['settings']['is_production'] = FALSE;
