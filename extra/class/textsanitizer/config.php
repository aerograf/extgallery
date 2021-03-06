<?php
/**
 * TextSanitizer extension
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: config.php 8088 2011-11-06 09:38:12Z beckmi $
 */
defined('XOOPS_ROOT_PATH') || die('Restricted access');

return $config = [
    'extensions' => [
        'iframe'  => 0,
        'image'   => 1,
        'flash'   => 1,
        'youtube' => 1,
        'mp3'     => 0,
        'wmp'     => 0,
        // If other module is used, please modify the following detection and 'link' in /wiki/config.php
        'wiki'    => is_dir(XOOPS_ROOT_PATH . '/modules/mediawiki/'),
        'mms'     => 0,
        'rtsp'    => 0,
        'ul'      => 1,
        'li'      => 1,
        'gallery' => 1,
    ],

    'truncate_length'      => 60,

    // Stop request processing if malicious words found
    'censor_stop'          => false,

    // Filters XSS scripts on display of text
    // There is considerable trade-off between security and performance
    'filterxss_on_display' => false,
];
