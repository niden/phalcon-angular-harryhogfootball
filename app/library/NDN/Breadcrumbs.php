<?php
/**
 * Breadcrumbs.php
 * niden_Breadcrumbs
 *
 * Handles the breadcrumbs for the application
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       6/22/12
 * @category    Library
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN;

class Breadcrumbs
{
    /**
     * @var array
     */
    private $_elements = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->_elements[] = array(
            'active' => false,
            'link'   => '/',
            'text'   => 'Home',
        );
    }

    /**
     * Adds a new element in the stack
     *
     * @param string $caption
     * @param string $link
     */
    public function add($caption, $link)
    {
        $this->_elements[] = array(
            'active' => false,
            'link'   => '/' . $link,
            'text'   => $caption,
        );
    }

    /**
     * Resets the internal element array
     */
    public function reset()
    {
        $this->_elements = array();
    }

    /**
     * Generates the JSON string from the internal array
     *
     * @return string
     */
    public function generate()
    {
        $lastKey = key(array_slice($this->_elements, -1, 1, true));

        $this->_elements[$lastKey]['active'] = true;

        return $this->_elements;
    }
}
