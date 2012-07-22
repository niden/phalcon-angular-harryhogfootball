<?php
/**
 * StatisticsController.php
 * StatisticsController
 *
 * The statistics controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-07-02
 * @category    Controllers
 * @license     MIT - https://github.com/NDN/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;
use NDN_Session as Session;

class StatisticsController extends NDN_Controller
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        Tag::setTitle('Statistics');
        parent::initialize();

        $this->_bc->add('Statistics', 'statistics');
        $this->view->setVar('top_menu', $this->_constructMenu($this));
    }

    /**
     * The index action
     */
    public function indexAction()
    {

        $data[] = array('id' => '-1', 'number' => 'All');

        $episodes = Episodes::find(array('order' => 'airDate DESC'));
        foreach ($episodes as $episode) {
            $data[] = array(
                        'id'     => $episode->id,
                        'number' => $episode->number,
                      );
        }
        $this->view->setVar('episodes', json_encode($data));
    }

    /**
     * Private function getting results for the HOF
     *
     * @param  null $action
     * @param  null $limit
     * @return string
     */
    private function _getHof($action = null, $limit = null)
    {
        $connection = Phalcon_Db_Pool::getConnection();
        $sql = 'SELECT COUNT(a.id) AS total, p.name AS playerName, a.award '
            . 'FROM awards a '
            . 'INNER JOIN players p ON a.playerId = p.id '
            . 'WHERE a.award = %s ';

        if (!empty($action)) {
            $sql .= 'AND a.userId = ' . (int) $action. ' ';
        }

        $sql .= 'GROUP BY a.award, a.playerId '
            . 'ORDER BY a.award ASC, total DESC, p.name ';

        if (!empty($limit)) {
            $sql .= 'LIMIT ' . (int) $limit;
        }

        // Kicks
        $query = sprintf($sql, -1);
        $result = $connection->query($query);
        $result->setFetchMode(Phalcon_Db::DB_ASSOC);

        $kicks    = array();
        $kicksMax = 0;

        while ($item = $result->fetchArray()) {
            $kicksMax = (0 == $kicksMax) ? $item['total'] : $kicksMax;
            $name     = $item['playerName'];
            $kicks[]  = array(
                'total'   => (int) $item['total'],
                'name'    => $name,
                'percent' => (int) ($item['total'] * 100 / $kicksMax),
            );
        }

        // Game balls
        $query = sprintf($sql, 1);
        $result = $connection->query($query);
        $result->setFetchMode(Phalcon_Db::DB_ASSOC);

        $gameballs    = array();
        $gameballsMax = 0;

        while ($item = $result->fetchArray()) {
            $gameballsMax = (0 == $gameballsMax) ?
                $item['total']       :
                $gameballsMax;
            $name         = $item['playerName'];
            $gameballs[]  = array(
                'total'   => (int) $item['total'],
                'name'    => $name,
                'percent' => (int) ($item['total'] * 100 / $gameballsMax),
            );
        }

        $result = array('gameballs' => $gameballs, 'kicks' => $kicks);
        return json_encode($result);
    }
}
