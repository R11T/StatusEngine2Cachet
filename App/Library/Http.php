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
        $this->get($url);
    }

    /**
     * Get all cachet components
     *
     * @access public
     */
    public function getComponentsList()
    {
        $configPath = CONFIG_DIR . 'config.json';
        $config = (new System\File($configPath))->getJson();
        $url    = $config['urlAPI'] . 'components';
        $get    = $this->get($url);
        return $get['data'];
    }

    /**
     * Execute a GET curl request
     *
     * @return array
     * @access private
     */
    private function get($url)
    {
        $response       = [];
        $handler        = curl_init($url);
        $defaultOptions = [
            CURLOPT_URL            => $url,
            CURLOPT_HEADER         => false,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
        ];
        curl_setopt_array($handler, $defaultOptions);
        $response['data']   = curl_exec($handler);

        $response['info']   = curl_getinfo($handler);
        $response['errNo']  = curl_errno($handler);
        $response['errMes'] = curl_error($handler);
        curl_close($handler);
        return $response;
    }
}

