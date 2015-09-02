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
namespace App\Library\Specification;

use \App\Library\Model;

class ProbeAliveSpecification extends Composite
{
    /**
     * Check if a probe is alive
     *
     * @param Model $candidate
     *
     * @return bool
     * @access public
     */
    public function isSatisfiedBy(Model $candidate)
    {
        $results = $candidate->getAssociatedResults();
        $max     = max(array_keys($results));
        return 0 === $results[$max]['code'];
    }
}
