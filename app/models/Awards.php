<?php
/**
 * Awards.php
 * Scoring
 *
 * The model for the scoring table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use \Phalcon\Mvc\Model\MetaData;
use \Phalcon\Db\Column;

class Awards extends \NDN\Model
{
    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $this->setBehavior('Timestamp');
        $this->belongsTo('episode_id', 'Episodes', 'id');
        $this->belongsTo('player_id', 'Players', 'id');
        $this->belongsTo('user_id', 'Users', 'id');
    }
}