<?php
/**
 * Scoring.php
 * Scoring
 *
 * The model for the scoring table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Scoring extends Phalcon_Model_Base
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
        $message = 'This record cannot be deleted because of referential '
                 . 'integrity rules';
        $fk = array('foreignKey' => array('message' => $message));

        $this->hasOne('userId', 'Users', 'id', $fk);
        $this->hasOne('episodeId', 'Episodes', 'id', $fk);
        $this->hasOne('playerId', 'Players', 'id', $fk);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return Phalcon_Model_Resultset Scoring[]
     */
    static public function find($parameters = array())
    {
        return parent::find($parameters);
    }

    /**
     * @param array $parameters
     *
     * @static
     * @return  Phalcon_Model_Base   Scoring
     */
    static public function findFirst($parameters = array())
    {
        return parent::findFirst($parameters);
    }
}