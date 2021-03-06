<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * XnewsletterBreadcrumb Class
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author      lucio <lucio.rota@gmail.com>
 * @package     Xnewsletter
 * @since       1.00
 * @version     $Id:$
 *
 * Example:
 * $breadcrumb = new XnewsletterBreadcrumb();
 * $breadcrumb->addLink( 'bread 1', 'index1.php' );
 * $breadcrumb->addLink( 'bread 2', '' );
 * $breadcrumb->addLink( 'bread 3', 'index3.php' );
 * echo $breadcrumb->render();
 */
defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');
include_once dirname(dirname(__DIR__)) . '/include/common.php';

/**
 * Class XnewsletterBreadcrumb
 */
class XnewsletterBreadcrumb
{
    /**
     * @var WfdownloadsWfdownloads
     * @access public
     */
    public $xnewsletter = null;

    private $dirname;
    private $_bread = array();

    /**
     *
     */
    public function __construct()
    {
        $this->xnewsletter = XnewsletterXnewsletter::getInstance();
        $this->dirname =  basename(dirname(dirname(__DIR__)));
    }

    /**
     * Add link to breadcrumb
     *
     * @param string $title
     * @param string $link
     */
    public function addLink( $title='', $link='' )
    {
        $this->_bread[] = array(
            'link'  => $link,
            'title' => $title
            );
    }

    /**
     * Render Xnewsletter BreadCrumb
     *
     */
    public function render()
    {
        $ret = '';

        if (!isset($GLOBALS['xoTheme']) || !is_object($GLOBALS['xoTheme'])) {
            include_once $GLOBALS['xoops']->path('/class/theme.php');
            $GLOBALS['xoTheme'] = new xos_opal_Theme();
            }
        require_once $GLOBALS['xoops']->path('/class/template.php');
        $breadcrumbTpl = new XoopsTpl();
        $breadcrumbTpl->assign('breadcrumb', $this->_bread);
// IN PROGRESS
// IN PROGRESS
// IN PROGRESS
        $ret .= $breadcrumbTpl->fetch("db:{$this->xnewsletter->getModule()->dirname()}_co_breadcrumb.tpl");
        unset($breadcrumbTpl);

        return $ret;
    }
}
