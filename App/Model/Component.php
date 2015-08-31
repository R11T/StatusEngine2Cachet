<?php
/**
 * @licence GPL-v2
 * Summary :
 * « You may copy, distribute and modify the software as long as
 * you track changes/dates of in source files and keep all modifications under GPL.
 * You can distribute your application using a GPL library commercially,
 * but you must also disclose the source code. »
 *
 * @link https://www.tldrlegal.com/l/gpl2
 * @see LICENSE file
 */
namespace App\Model;

/**
 * Component
 *
 * @author Romain L.
 */
class Component
{
    /**
     * Statuses constants
     *
     * @var int
     * @access public
     */
    const OPERATIONAL = 1;
    const PERFORMANCE = 2;
    const PARTIAL_OUT = 3;
    const MAJOR_OUT   = 4;
}
