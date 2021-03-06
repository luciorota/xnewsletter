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

include_once dirname(__DIR__) . '/include/common.php';

/**
 * @return array
 */
function xnewsletter_plugin_getinfo_evennews()
{
    $pluginInfo = array();
    $pluginInfo['name'] = 'evennews';
    $pluginInfo['icon'] = XOOPS_URL . '/modules/evennews/images/logo.png';
    //$pluginInfo['modulepath'] = XOOPS_ROOT_PATH . '/modules/evennews/xoops_version.php';
    $pluginInfo['tables'][0] = $GLOBALS['xoopsDB']->prefix('evennews_members');
    $pluginInfo['descr'] = 'Import from evennews';
    $pluginInfo['hasform'] = false;

    return $pluginInfo;
}

/**
 * @param $cat_id
 * @param $checkSubscrsAfterRead
 * @param $checkLimit
 * @param $skipCatsubscrExist
 *
 * @return int
 */
function xnewsletter_plugin_getdata_evennews($cat_id, $checkSubscrsAfterRead = true, $checkLimit, $skipCatsubscrExist = true)
{
    $xnewsletter = XnewsletterXnewsletter::getInstance();
    //
    $import_status = ($checkSubscrsAfterRead === false) ? _XNEWSLETTER_IMPORT_STATUS_IMPORTABLE : _XNEWSLETTER_IMPORT_STATUS_TOCHECK;
    $sql = "SELECT `user_email`, `user_name`, `user_nick`";
    $sql .= " FROM {$GLOBALS['xoopsDB']->prefix('evennews_members')}";
    $sql .= " WHERE (`user_email` is not null and not(`user_email`=''))";
    if (!$result_users = $GLOBALS['xoopsDB']->query($sql)) {
        die ('MySQL-Error: ' . mysql_error());
    }
    $j = 0;
    $line = 0;
    while ($lineArray = mysql_fetch_array($result_users)) {
        ++$line;
        $email = $lineArray[0];
        $subscr_id = xnewsletter_pluginCheckEmail($email);
        $catsubscr_id = xnewsletter_pluginCheckCatSubscr($subscr_id, $cat_id);
        if ($skipCatsubscrExist === true && $catsubscr_id > 0) {
            // skip existing subscriptions
            // NOP
        } else {
            $currcatid = $catsubscr_id > 0 ? 0 : $cat_id;
            $importObj = $xnewsletter->getHandler('import')->create();
            $importObj->setVar('import_email', $email);
            $importObj->setVar('import_sex', '');
            $importObj->setVar('import_firstname', $lineArray[1]);
            $importObj->setVar('import_lastname', $lineArray[2]);
            $importObj->setVar('import_cat_id', $currcatid);
            $importObj->setVar('import_subscr_id', $subscr_id);
            $importObj->setVar('import_catsubscr_id', $catsubscr_id);
            $importObj->setVar('import_status', $import_status);
            if (!$xnewsletter->getHandler('import')->insert($importObj)) {
                echo $importObj->getHtmlErrors();
                exit();
            }
            ++$j;
        }
        if ($j == 100000) {
            break;
        } //maximum number of processing to avoid cache overflow
        if ($checkLimit > 0 && $j == $checkLimit) {
            $import_status = _XNEWSLETTER_IMPORT_STATUS_TOCHECK;
        }
    }
    return $j;
}
