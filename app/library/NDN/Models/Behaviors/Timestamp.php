<?php
/**
 * Timestamp.php
 * NDN\Models\Behaviors\Timestamp
 *
 * Adds timestamp behavior in a model
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-10-26
 * @category    Models
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

namespace NDN\Models\Behaviors;

use \Phalcon\Mvc\Model\MetaData;
use \Phalcon\Db\Column;

class Timestamp
{
    protected $di;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
     * beforeSave hook - called prior to any Save (insert/update)
     */
    public function beforeSave($record)
    {
        $auth     = $this->di->getShared('session')->get('auth');
        $userId   = (isset($auth['id'])) ? (int) $auth['id'] : 0;
        $datetime = date('Y-m-d H:i:s');
        if (empty($record->createdAtUserId)) {
            $record->createdAt        = $datetime;
            $record->createdAtUserId  = $userId;
        }
        $record->lastUpdate       = $datetime;
        $record->lastUpdateUserId = $userId;
    }
}