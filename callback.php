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
error_reporting(0);

if (!$called_from_hook_call) {
    chdir('../../../');

    $core_config['init']['ignore_csrf'] = true;

    include 'init.php';
    include $core_config['apps_path']['libs'] . '/function.php';
    chdir('plugin/gateway/sms77/');
    $requests = $_REQUEST;
}

//die(var_dump($requests));

$log = '';
if (is_array($requests)) {
    foreach ($requests as $k => $v) $log .= $k . ':' . $v . ' ';
    _log('pushed ' . $log, 2, 'sms77 callback');
}

$idSms77 = $requests['msg_id'];
$idLocal = $requests['foreign_id'];
$messageStatus = $requests['status'];
$isDLR = 'dlr' === $requests['webhook_event'];

if ($isDLR && $idSms77 && $messageStatus && $idLocal) {
    $data = sendsms_get_sms($idLocal);
    $uid = $data['uid'];
    $p_status = $data['p_status'];

    switch ($messageStatus) {
        case 'DELIVERED':
            $p_status = 3; // delivered
            break;
        case 'ACCEPTED':
        case 'BUFFERED':
        case 'TRANSMITTED':
            $p_status = 1; // sent
            break;
        default :
            $p_status = 2; // failed
            break;
    }

    _log('dlr uid:' . $uid . ' smslog_id:' . $idLocal . ' messageid:'
        . $idSms77 . ' status:' . $messageStatus, 2, 'sms77 callback');

    dlr($idLocal, $uid, $p_status);

    ob_end_clean();

    exit('ACK/sms77');
}