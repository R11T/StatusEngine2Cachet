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
        $configPath = CONFIG_DIR . 'config.json';
        $config     = (new System\File($configPath))->getJson();
        return $this->get($config['urlAPI'] . 'ping');
    }

    /**
     * Get all cachet components
     *
     * @access public
     */
    public function getComponentsList()
    {
        $configPath = CONFIG_DIR . 'config.json';
        $config     = (new System\File($configPath))->getJson();
        return $this->get($config['urlAPI'] . 'components');
    }

    /**
     * Execute a GET request
     *
     * @param string $url
     *
     * @return array
     * @access private
     */
    private function get($url)
    {
        $options = [
            CURLOPT_URL => $url,
        ];

        return $this->request($options);
    }

    /**
     * Update a cachet component
     *
     * @param int $id Component id
     * @param array $data Data to update
     *
     * @return array
     * @access public
     */
    public function putComponent($id, array $data)
    {
        $configPath = CONFIG_DIR . 'config.json';
        $config     = (new System\File($configPath))->getJson();
        $url        = $config['urlAPI'] . 'components/' . $id;

        return $this->put($url, $config['tokenAPI'], $data);
    }

    /**
     * Execute a PUT request
     *
     * @param string $url
     * @param string $token Authentificating token
     * @param array  $data  Data to update
     *
     * @return array
     * @access private
     */
    private function put($url, $token, array $data)
    {
        $dataString = json_encode($data);
        $options    = [
            CURLOPT_URL           => $url,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS    => $dataString,
            CURLOPT_HTTPHEADER    => [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($dataString),
                'X-Cachet-Token: ' . $token,
            ],
        ];
        return $this->request($options);
    }

    /**
     * Send a curl request
     *
     * @param array $options CURL options
     *
     * @return array
     * @access private
     */
    private function request(array $options)
    {
        $response       = [];
        $handler        = curl_init();
        $defaultOptions = [
            CURLOPT_HEADER         => false,
            CURLOPT_TIMEOUT        => 5,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
        ];
        curl_setopt_array($handler, $options + $defaultOptions);
        $response['data']   = curl_exec($handler);

        $response['info']   = curl_getinfo($handler);
        $response['errNo']  = curl_errno($handler);
        $response['errMes'] = curl_error($handler);
        curl_close($handler);
        return $response;
    }
}

