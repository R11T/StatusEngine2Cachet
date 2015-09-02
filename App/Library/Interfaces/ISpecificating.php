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
namespace App\Library\Interfaces;

use \App\Library\Model;

/**
 * Define a specificating public contract
 *
 * @author Romain L.
 */
interface ISpecificating
{
    /**
     * Check if a candidate satisfies some criteria
     *
     * @return bool
     * @access public
     */
    public function isSatisfiedBy(Model $candidate);
}
