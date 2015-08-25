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
}


