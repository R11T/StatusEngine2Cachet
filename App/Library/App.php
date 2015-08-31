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

        $components = $config['components'];
        foreach ($config['components'] as $component) {
            if (isset($config['associations'][$component['id']]) && !empty($config['associations'][$component['id']])) {
                $result = $this->checkComponentAssociations($config['associations'][$component['id']], $config['resultFilePath']);
                var_dump('result check', $component['label']);
                print_r($result);
                if ($result['alive'] === $result['total'] && 0 !== $result['alive']) {
                    $data = [
                        'status' => Model\Component::OPERATIONAL,
                    ];
                } elseif (0 === $result['alive'] && 0 !== $result['total']) {
                    $data = [
                        'status' => Model\Component::MAJOR_OUT,
                    ];
                } else {
                    $data = [
                        'status' => Model\Component::MAJOR_OUT,
                    ];
                }
                $http = new Http();
                print_r($http->putComponent($component['id'], $data));
            }
        }
    }

    /**
     * Check all associations probes - components in order to guess component's general status
     *
     * @param array  $associations All associations of a given component
     * @param string $resultFilePath
     *
     * @return array
     * @access private
     */
    private function checkComponentAssociations(array $associations, $resultFilePath)
    {
        $probes2Components = ['alive' => 0, 'total' => 0];
        foreach ($associations as $name) {
            $probe = $this->findProbe($name, $resultFilePath);
            if (!empty($probe)) {
                if ($this->isProbeAlive($probe)) {
                    ++$probes2Components['alive'];
                }
                ++$probes2Components['total'];
            }
        }
        return $probes2Components;
    }

    /**
     * Find a probe, given its name
     *
     * @param string $name
     * @param string $resultFilePath
     *
     * @return array
     * @access private
     */
    private function findProbe($name, $resultFilePath)
    {
        $resultFile = new System\File($resultFilePath);
        $result     = $resultFile->getJson();
        foreach ($result as $analyzable) {
            foreach ($analyzable as $probeName => $probeData) {
                if ($name === $probeName) {
                    return $probeData;
                }
            }
        }
        return [];
    }

    /**
     * Check if a probe is alive
     *
     * @return bool
     * @access private
     */
    private function isProbeAlive(array $probe)
    {
        $max = max(array_keys($probe));
        //return false;
        return 0 === $probe[$max]['code'];

    }
}
