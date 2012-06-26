<?php
/**
 * AwardsController.php
 * AwardsController
 *
 * The awards controller and its actions
 *
 * @author      Nikos Dimopoulos <nikos@niden.net>
 * @since       2012-06-24
 * @category    Controllers
 * @license     MIT - https://github.com/niden/phalcon-angular-harryhogfootball/blob/master/LICENSE
 *
 */

use Phalcon_Tag as Tag;

class AwardsController extends ControllerBase
{
    /**
     * Initializes the controller
     */
    public function initialize()
    {
        $this->view->setTemplateAfter('main');
        Tag::setTitle('Awards Entry');
        parent::initialize();

        $this->_bc->add('Awards', 'awards');
    }

    /**
     * The index action
     */
    public function indexAction()
    {

    }

    /**
     * The add action
     */
    public function addAction()
    {
        $this->_bc->add('Add', 'awards/add');

        $players    = new Players();
        $allPlayers = $players->find();

        $this->view->setVar('players', $allPlayers);

        if (!$this->request->isPost()) {

        }
    }

    /**
     * Gets the Hall of Fame
     */
    public function hofAction()
    {
        $this->view->setRenderLevel(Phalcon_View::LEVEL_LAYOUT);
        $request = $this->request;

//        if ($request->isGet() == true && $request->isAjax() == true) {

            $results = $this->_getHof(5);

            echo $results;
    }

    /**
     * Private function getting results for the HOF
     *
     * @param $limit
     * @return string
     */
    private function _getHof($limit)
    {

        $connection = Phalcon_Db_Pool::getConnection();
        $sql = 'SELECT COUNT(s.id) AS total, p.name AS playerName, p.team, s.award '
             . 'FROM scoring s '
             . 'INNER JOIN players p ON s.playerId = p.id '
             . 'GROUP BY s.award, s.playerId '
             . 'ORDER BY s.award ASC, total DESC, p.name ';

        $result = $connection->query($sql);
        $result->setFetchMode(Phalcon_Db::DB_ASSOC);

        $kicks     = array();
        $gameballs = array();

        $gameballsCount = 0;
        $kicksCount     = 0;
        $gameballsMax   = 0;
        $kicksMax       = 0;
        print_r(empty($limit));
        while ($item = $result->fetchArray()) {
            if (0 == $item['award']) {
                if (!empty($limit) && $limit > $kicksCount) {
                    $kicksMax = (0 == $kicksMax) ?
                                $item['total']   :
                                $kicksMax;
                    $name    = $item['playerName'];
                    if ($item['team']) {
                        $name .= ' (' . $item['team'] . ')';
                    }

                    $kicks[] = array(
                        'total'   => $item['total'],
                        'name'    => $name,
                        'percent' => (int) ($item['total'] * 100 / $kicksMax),
                    );
                    $kicksCount++;
                }
            } else {
                if (!empty($limit) && $limit > $gameballsCount) {
                    $gameballsMax = (0 == $gameballsMax) ?
                                    $item['total']       :
                                    $gameballsMax;
                    $name    = $item['playerName'];
                    if ($item['team']) {
                        $name .= ' (' . $item['team'] . ')';
                    }
                    $gameballs[] = array(
                        'total'   => $item['total'],
                        'name'    => $name,
                        'percent' => (int) ($item['total'] * 100 / $gameballsMax),
                    );
                    $gameballsCount++;
                }
            }
        }

        $result = array('gameballs' => $gameballs, 'kicks' => $kicks);
        return json_encode($result);
    }
}
