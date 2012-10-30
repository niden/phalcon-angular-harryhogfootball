<?php
/**
 * Users.php
 * Users
 *
 * The model for the users table
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-21
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

class Users extends \NDN\Model
{
    /**
     * Initializes the class and sets any relationships with other models
     */
    public function initialize()
    {
        $this->hasMany('id', 'Awards', 'user_id');
    }
}