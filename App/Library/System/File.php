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
namespace App\Library\System;

/**
 * File manager
 *
 * @author Romain L.
 */
class File
{
    /**
     * File path
     *
     * @var string
     *
     * @access private
     */
    private $path;

    /**
     * Constructor
     *
     * @param string $path
     *
     * @access public
     */
    public function __construct($path)
    {
        $this->path = $path;
    }


    // ------
    // Input
    // ------


    /**
     * Get json data
     *
     * @return array
     * @throws \Exception if file isn't a valid json file
     * @access public
     */
    public function getJson()
    {
        $content = json_decode($this->getAsString(), true);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception('"' . $this->path . '" file is not a valid json file : ' . json_last_error_msg());
        }
        return $content;
    }

    /**
     * Return content file
     *
     * @return string
     * @access public
     */
    public function getAsString()
    {
        return is_file($this->path) ? file_get_contents($this->path) : '';
    }


    // --------
    // Output
    // --------


    public function storeData(array $data)
    {
        $dataToStore = [];
        $oldData     = $this->getJson();
        $dataToStore = $oldData + $data;
        $this->setJson($dataToStore);

    }

    /**
     * Set json data
     *
     * @return void
     * @throws \Exception if an error occurs during encoding
     * @access public
     */
    public function setJson(array $data)
    {
        $content = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \Exception('Data can\'t be encoded to json format : ' . json_last_error_msg());
        }
        return $this->setAsString($content);
    }

    /**
     * Set content into file
     *
     * @return void
     * @access public
     */
    public function setAsString($string)
    {
        return file_put_contents($this->path, $string);
    }
}


