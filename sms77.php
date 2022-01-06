<?php
/**
 * This file is part of playSMS.
 * playSMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * playSMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with playSMS. If not, see <http://www.gnu.org/licenses/>.
 */
defined('_SECURE_') || die('Forbidden');

if (!auth_isadmin()) auth_block();

global $core_config, $plugin_config, $status_active;

include $core_config['apps_path']['plug'] . '/gateway/sms77/config.php';

$sms77 = $plugin_config['sms77'];

switch (_OP_) {
    case 'manage':
        $tpl = [
            'name' => 'sms77',
            'vars' => [
                'APIKey' => _mandatory(_('API Key')),
                'BUTTON_BACK' =>
                    _back('index.php?app=main&inc=core_gateway&op=gateway_list'),
                'callback_url' => _HTTP_PATH_BASE_
                    . '/index.php?app=call&cat=gateway&plugin=sms77&access=callback',
                'CALLBACK_URL_ACCESSIBLE' =>
                    _('Your callback URL should be accessible from sms77'),
                'CALLBACK_URL_CREATE' =>
                    _('For receiving, create a GET webhook pointing to the callback URL'),
                'CALLBACK_URL_IS' => _('Your callback URL is'),
                'DIALOG_DISPLAY' => _dialog(),
                'Gateway name' => _('Gateway name'),
                'HINT_API_KEY' => _hint(_('Sign up with sms77.io to get your API Key')),
                'HINT_MODULE_SENDER' =>
                    _hint(_('Max. 16 numeric or 11 alphanumeric characters')),
                'HINT_TIMEZONE' => _hint(_('Eg: +0700 for Jakarta/Bangkok timezone')),
                'Manage sms77' => _('Manage sms77'),
                'Module sender ID' => _('Module sender ID'),
                'Module timezone' => _('Module timezone'),
                'Notes' => _('Notes'),
                'SMS77_FREE_CREDITS' => _('New users receive free credits for testing'),
                'SMS77_PUSH_DLR' =>
                    _('sms77 will push DLR in real time to your callback URL'),
                'SMS77_SIGN_UP' => _('To get your API Key sign up with'),
                'Save' => _('Save'),
                'sms77_param_apikey' => $sms77['APIKey'],
                'sms77_param_datetime_timezone' => $sms77['datetime_timezone'],
                'sms77_param_module_sender' => $sms77['module_sender'],
                'status_active' => $status_active,
            ],
        ];

        _p(tpl_apply($tpl));

        break;

    case 'manage_save':
        $url = $sms77['url'];

        if ($url) {
            $items = [
                'APIKey' => $_REQUEST['up_apikey'],
                'datetime_timezone' => $_REQUEST['up_datetime_timezone'],
                'module_sender' => $_REQUEST['up_module_sender'],
                'url' => $url,
            ];

            if (registry_update(0, 'gateway', 'sms77', $items))
                $_SESSION['dialog']['info'][] =
                    _('Gateway module configuration has been saved');
            else $_SESSION['dialog']['danger'][] =
                _('Failed to save gateway module configuration');
        } else $_SESSION['dialog']['danger'][] = _('All mandatory fields must be filled');

        header('Location: ' . _u('index.php?app=main&inc=gateway_sms77&op=manage'));

        exit();
}
