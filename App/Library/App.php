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
namespace App\Library;

use \App\Model;

/**
 * Main application
 *
 * @author Romain L.
 */
class App
{
    /**
     * For testing purpose
     *
     * @return void
     * @access public
     */
    public function ping()
    {
        $http = new Http();
        print_r($http->ping());
    }

    /**
     * Fetch cachet's components and add them in config for associating with StatusEngine probes
     *
     * @return void
     * @access public
     */
    public function synchronize()
    {
        $components   = [];
        $associations = [];
        $toStore      = [];
        $http         = new Http();
        $configPath   = CONFIG_DIR . 'config.json';
        $configFile   = new System\File($configPath);
        $result       = $http->getComponentsList();
        $data         = json_decode($result['data'], true);
        foreach ($data['data'] as $found) {
            $components[] = ['id' => $found['id'], 'label' => $found['name']];
            $associations[$found['id']] = [];
        }
        $toStore = [
            'components'   => $components,
            'associations' => $associations
        ];
        $configFile->storeData($toStore);
    }

    /**
     * Update a cachet's component according to associated probes
     *
     * @return void
     * @access public
     */
    public function update()
    {
        $configPath = CONFIG_DIR . 'config.json';
        $configFile = new System\File($configPath);
        $config     = $configFile->getJson();
        $resultFile = new System\File($config['resultFilePath']);
        $dataResult = $resultFile->getJson();
        $http       = new Http();

        $components = json_decode($http->getComponentsList()['data'], true);
        $toUpdate   = new Specification\ComponentToBeUpdatedSpecification();

        foreach ($components['data'] as $dataComponent) {
            $comp   = new Model\Component();
            $comp->hydrate($dataComponent);
            $compId = $comp->getId();

            if (!empty($config['associations'][$compId])) {
                $comp->associateProbes($config['associations'][$compId], $dataResult);
                $comp->evaluateLevel();
                if ($toUpdate->isSatisfiedBy($comp)) {
                    print_r($http->putComponent($comp->getId(), $comp->getUpdProperties()));
                }
            }
        }
    }
}
