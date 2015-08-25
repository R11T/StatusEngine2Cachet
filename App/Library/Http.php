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
* Http message manager, sent / received
*
* @author Romain L.
*/
class Http
{
    public function ping()
    {
        echo 'Pong', "\n";
        $configPath = CONFIG_DIR . 'config.json';
        $config = (new System\File($configPath))->getJson();
        $url = $config['urlAPI'] . 'ping';
        $this->request($url);
    }

    private function request($url)
    {
        $ch  = curl_init($url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);
        echo $data, "\n";
        $info = curl_getinfo($ch);
        print_r($info);
        echo "\n";
        curl_close($ch);
    }
}

