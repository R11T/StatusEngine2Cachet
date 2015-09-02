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

use \App\Library\Specification;

/**
 * Component
 *
 * @author Romain L.
 */
class Component extends \App\Library\Model
{
    /**
     * Statuses constants
     *
     * @var int
     * @access public
     */
    const OPERATIONAL    = 1;
    const PERFORMANCE    = 2;
    const PARTIAL_OUTAGE = 3;
    const MAJOR_OUTAGE   = 4;

    /**
     * Data inherited from cachet
     *
     * @var array
     * @access private
     */
    private $properties       = [];

    /**
     * Data updated by the probes
     *
     * @var array
     * @access private
     */
    private $updProperties    = [];

    /**
     * Linked probes
     *
     * @var array
     * @access private
     */
    private $associatedProbes = [];

    /**
     * Fill object
     *
     * @param $data
     *
     * @return void
     * @access public
     */
    public function hydrate(array $data)
    {
        $this->properties = $data;
    }

    /**
     * Magic method for calling method
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     * @access public
     */
    public function __call($name, array $arguments = [])
    {
        $method    =  substr($name, 0, 3);
        $attribute = strtolower(substr($name, 3, strlen($name) - 3));
        if ('set' === $method) {
        } else if ('get' === $method) {
            if (isset($this->properties[$attribute])) {
                return $this->properties[$attribute];
            } else {
                throw new \Exception('Trying to access to an unknown variable'. $attribute);
            }
        } else {
            throw new \Exception('Action « ' . $name  . ' » unknown');
        }
    }

    /**
     * Check all associated probes to find component's status
     *
     * @return void
     * @access public
     */
    public function evaluateLevel()
    {
        $statuses = ['alive' => 0, 'total' => 0];
        $alive    = new Specification\ProbeAliveSpecification();
        foreach ($this->associatedProbes as $probeName => $probe) {
            if ($alive->isSatisfiedBy($probe)) {
                ++$statuses['alive'];
            }
            ++$statuses['total'];
        }
        if ($statuses['alive'] === $statuses['total']) {
           $newStatus = static::OPERATIONAL;
        } elseif (0 === $statuses['alive'] && 0 !== $statuses['total']) {
           $newStatus = static::MAJOR_OUTAGE;
        } else {
           $newStatus = static::PARTIAL_OUTAGE;
        }
        $this->updProperties['status'] = $newStatus;
    }

    /**
     * Link probes to this component, as defined in config file
     *
     * @param array $associations List of all association component / probe
     * @param array $dataResult   Probes' results
     *
     * @return void
     * @access public
     */
    public function associateProbes(array $associations, array $dataResult)
    {
        foreach ($associations as $probeName) {
            foreach ($dataResult as $analyzable) {
                if (isset($analyzable[$probeName])) {
                    $probe = new Probe();
                    $probe->setResults($analyzable[$probeName]);
                    $this->associatedProbes[$probeName] = $probe;
                }
            }
        }
    }

    /**
     * Getter
     *
     * @return array
     * @access public
     */
    public function getUpdProperties()
    {
        return $this->updProperties;
    }
}
