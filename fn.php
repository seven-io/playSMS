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

// hook_sendsms
// called by main sms sender
// return true for success delivery
// $smsc : smsc
// $sms_sender : sender mobile number
// $sms_footer : sender sms footer or sms sender ID
// $sms_to : destination sms number
// $sms_msg : sms message tobe delivered
// $uid : sender User ID
// $gpid : group phonebook id (optional)
// $smslog_id : sms ID
// $sms_type : sms type
// $unicode : unicode
function seven_hook_sendsms($smsc, $sms_sender, $sms_footer, $sms_to, $sms_msg, $uid = '',
                            $gpid = 0, $smslog_id = 0, $sms_type = 'text', $unicode = 0) {
    global $plugin_config;

    _log('enter smsc:' . $smsc . ' smslog_id:' . $smslog_id . ' uid:' . $uid . ' to:'
        . $sms_to, 3, 'seven_hook_sendsms');

    $pluginConfig = gateway_apply_smsc_config($smsc, $plugin_config)['seven'];
    $from = stripslashes($sms_sender);
    $moduleSender = $pluginConfig['module_sender'];
    if ($moduleSender) $from = $moduleSender;

    $footer = stripslashes($sms_footer);
    $text = stripslashes($sms_msg);
    $ok = false;

    if ($footer) $text .= $footer;

    if ($sms_to && $text) {
        if ($unicode && function_exists('mb_convert_encoding'))
            $text = mb_convert_encoding($text, 'UTF-8', 'auto');

        $url = $pluginConfig['url'];
        $ch = curl_init($url);
        $options = [
            CURLOPT_POSTFIELDS => json_encode(compact('from', 'text') + [
                    'flash' => (int)($sms_type === 'flash'),
                    'foreign_id' => $smslog_id,
                    'json' => 1,
                    'to' => $sms_to,
                    'unicode' => (int)$unicode,
                ]),
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json',
                'SentWith: playSMS',
                'X-Api-Key: ' . $pluginConfig['APIKey'],
            ],
            CURLOPT_RETURNTRANSFER => true,
        ];
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        _log('send url:[' . $url . ']', 3, 'seven_hook_sendsms');

        $msgId = 0;

        if ($response) {
            $obj = json_decode($response);
            $msgId = $obj->messages[0]->id;
            $code = (int)$obj->success;
            if (100 !== $code) $c_error_code = $code;
        }

        if ($msgId) {
            $ok = true;
            $log = 'sent smslog_id:' . $smslog_id . ' message_id:' . $msgId;
            dlr($smslog_id, $uid, 1);
        } else if (isset($c_error_code)) $log = 'failed smslog_id:' . $smslog_id
            . ' message_id:' . $msgId . ' error_code:' . $c_error_code;
        else $log = 'invalid smslog_id:' . $smslog_id . ' resp:[' . $response . ']';

        _log($log . ' smsc:' . $smsc, 2, 'seven_hook_sendsms');
    }

    if (!$ok) dlr($smslog_id, $uid, 2);

    return $ok;
}

function seven_hook_call($requests) {
    global $core_config, $plugin_config; // please note that we must globalize these 2 variables
    $called_from_hook_call = true;
    $access = $requests['access'];

    if ($requests['access'] !== 'callback') return;

    $fn = $core_config['apps_path']['plug'] . '/gateway/seven/callback.php';
    _log('start load:' . $fn, 2, 'seven call');
    include $fn;
    _log('end load callback', 2, 'seven call');
}
