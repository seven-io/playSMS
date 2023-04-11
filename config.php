<?php
defined('_SECURE_') || die('Forbidden');

$data = registry_search(0, 'gateway', 'seven');
$plugin_config['seven'] = $data['gateway']['seven'];
$plugin_config['seven']['name'] = 'seven';
$plugin_config['seven']['url'] = 'https://gateway.seven.io/api/sms';

$plugin_config['seven']['_smsc_config_'] = [
    'APIKey' => _('API Key'),
    'datetime_timezone' => _('Module timezone'),
    'module_sender' => _('Module sender ID'),
];
