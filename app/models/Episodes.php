<?php
/**
 * Episodes.php
 * Episodes
 *
 * The model for the episodes table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Episodes extends \NDN\Model
{
    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $this->addBehavior('Timestamp');
        $this->hasMany('id', 'Awards', 'episode_id');
    }
}