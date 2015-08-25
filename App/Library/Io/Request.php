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
namespace App\Library\Io;

/**
 * Request constructor
 *
 * @author Romain L.
 */
class Request
{
    /**
     * Typical softwares' arg
     *
     * @var array
     *
     * @access private
     * @link https://en.wikipedia.org/wiki/Entry_point#C_and_C.2B.2B
     */
    private $argv;

    /**
     * Typical softwares's arg
     *
     * @var int
     *
     * @access private
     * @link https://en.wikipedia.org/wiki/Entry_point#C_and_C.2B.2B
     */
    private $argc;

    /**
     * In the argv, action arg's offset
     *
     * @var int
     *
     * @access public
     */
    const ACTION = 1;

    public function __construct(array $argv, $argc)
    {
        if (2 < $argc) {
            throw new \Exception('Too many arguments, try type help');
        }
        $this->argv = $argv;
        $this->argc = $argc;
    }

    /**
     * Returns action requested by the user
     *
     * @return string empty if action doesn't exist
     * @access public
     */
    public function getAction()
    {
        return isset($this->argv[self::ACTION]) ? $this->argv[self::ACTION] : '';
    }
}

