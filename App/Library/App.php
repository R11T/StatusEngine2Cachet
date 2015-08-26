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
        $http->ping();
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
        $data         = $http->getComponentsList();
        $configPath   = CONFIG_DIR . 'config.json';
        $configFile   = new System\File($configPath);
        $config       = $configFile->getJson();
        $data         = json_decode($data, true);
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
}
