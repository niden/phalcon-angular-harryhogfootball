<?php
/**
 * Players.php
 * Players
 *
 * The model for the players table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Players extends \NDN\Model
{
    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $this->setBehavior('Timestamp');
        $this->hasMany('id', 'Awards', 'player_id');
    }
}