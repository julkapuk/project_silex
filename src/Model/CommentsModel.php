<?php
/**
 * Comments model.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Model;

use Silex\Application;

/**
 * Class CommentsModel.
 *
 * @category Epi
 * @package Model
 * @use Silex\Application
 */
class CommentsModel
{
    /**
     * Db object.
     *
     * @access protected
     * @var Silex\Provider\DoctrineServiceProvider $db
     */
    protected $db;

    /**
     * Object constructor.
     *
     * @access public
     * @param Silex\Application $app Silex application
     */
    public function __construct(Application $app)
    {
        $this->db = $app['db'];
    }

    /**
     * Gets all albums.
     *
     * @access public
     * @return array Result
     */
    public function getAll()
    {
        $query = '
        SELECT
            comments.id, comments.comment, comments.user_id, users.login
        FROM
            comments
        INNER JOIN
          users
        ON
            comments.user_id=users.id
        ORDER BY
            comments.data DESC;
        ';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }

    /**
     * Gets single album data.
     *
     * @access public
     * @param integer $id Record Id
     * @return array Result
     */
//    public function getAlbum($id)
//    {
//        if (($id != '') && ctype_digit((string)$id)) {
//            $query = 'SELECT id, title, artist FROM albums WHERE id= :id';
//            $statement = $this->db->prepare($query);
//            $statement->bindValue('id', $id, \PDO::PARAM_INT);
//            $statement->execute();
//            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
//            return !$result ? array() : current($result);
//        } else {
//            return array();
//        }
//    }
}