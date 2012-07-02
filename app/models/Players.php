<?php
/**
 * Players.php
 * Players
 *
 * The model for the players table
 *
 * @author      Nikos Dimopoulos <nikos@NDN.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Players extends NDN_Model
{
    /**
     * @var integer
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $active;

    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $this->belongsTo('playerId', 'Scoring', 'id');
    }
}