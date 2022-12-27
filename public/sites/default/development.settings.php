<?php

#$config['helfi_proxy.settings']['tunnistamo_return_url'] = '/fi/dev-avustukset/openid-connect/tunnistamo';

$config['helfi_proxy.settings']['asset_path'] = 'dev-avustukset-assets';
$config['helfi_proxy.settings']['prefixes'] = [
  'en' => 'dev-grants',
  'fi' => 'dev-avustukset',
  'sv' => 'dev-bidrags'
];

$schemes = [
  'azure' => [
    'driver' => 'helfi_azure',
    'config' => [
      'name' => getenv('AZURE_BLOB_STORAGE_NAME'),
      'token' => getenv('BLOBSTORAGE-SAS-TOKEN'),
      'endpointSuffix' => 'core.windows.net',
      'protocol' => 'https',
    ],
    'cache' => TRUE,
  ],
];
$config['helfi_azure_fs.settings']['use_blob_storage'] = TRUE;
$settings['flysystem'] = $schemes;
