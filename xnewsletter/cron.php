<?php
/**
 * ****************************************************************************
 *  - A Project by Developers TEAM For Xoops - ( http://www.xoops.org )
 * ****************************************************************************
 *  XNEWSLETTER - MODULE FOR XOOPS
 *  Copyright (c) 2007 - 2012
 *  Goffy ( wedega.com )
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  ---------------------------------------------------------------------------
 *
 * @copyright  Goffy ( wedega.com )
 * @license    GPL 2.0
 * @package    xnewsletter
 * @author     Goffy ( webmaster@wedega.com )
 *
 *  Version : $Id $
 * ****************************************************************************
 */

$currentFile = basename(__FILE__);
include_once __DIR__ . '/header.php';

// Disable xoops debugger in dialog window
include_once $GLOBALS['xoops']->path('/class/logger/xoopslogger.php');
$xoopsLogger =& XoopsLogger::getInstance();
$xoopsLogger->activated = false;
error_reporting(0);

include_once XNEWSLETTER_ROOT_PATH . '/include/functions.task.php';
// execute all pending tasks
$executeTasksResult = xnewsletter_executeTasks($xnewsletter->getConfig('xn_send_in_packages'), 0);

if ($executeTasksResult != '') {
    $protocolObj = $xnewsletter->getHandler('protocol')->protocol(0, 0, 'Cron: ' . $executeTasksResult, _XNEWSLETTER_PROTOCOL_STATUS_CRON, array('%result_exec' => $executeTasksResult), true);
    echo "[" . date(_DATESTRING) . "] result cron: {$executeTasksResult}";
} else {
    // NOP
}
