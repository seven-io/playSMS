<?php
defined('_SECURE_') || die('Forbidden');

$data = registry_search(0, 'gateway', 'sms77');
$plugin_config['sms77'] = $data['gateway']['sms77'];
$plugin_config['sms77']['name'] = 'sms77';
$plugin_config['sms77']['url'] = 'https://gateway.sms77.io/api/sms';

$plugin_config['sms77']['_smsc_config_'] = [
    'APIKey' => _('API Key'),
    'datetime_timezone' => _('Module timezone'),
    'module_sender' => _('Module sender ID'),
];
