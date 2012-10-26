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

class Awards extends \NDN\Model
{
    /**
    * @var integer
    */
    public $id;

    /**
     * @var integer
     */
    public $episodeId;

    /**
     * @var integer
     */
    public $userId;

    /**
     * @var integer
     */
    public $playerId;

    /**
     * @var integer
     */
    public $award;

    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $this->belongsTo('playerId', 'Players', 'id');
    }
}