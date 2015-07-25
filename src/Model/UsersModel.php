<?php
/**
 * Users model.
 *
 * @author EPI <epi@uj.edu.pl>
 * @link http://epi.uj.edu.pl
 * @copyright 2015 EPI
 */

namespace Model;

use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

/**
 * Class Users.
 *
 * @category Epi
 * @package Model
 * @use Silex\Application
 */
class UsersModel
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
     * Loads user by login.
     *
     * @access public
     * @param string $login User login
     * @throws UsernameNotFoundException
     * @return array Result
     */
    public function loadUserByLogin($login)
    {
        $user = $this->getUserByLogin($login);

        if (!$user || !count($user)) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $login)
            );
        }

        $roles = $this->getUserRoles($user['id']);

        if (!$roles || !count($roles)) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $login)
            );
        }

        return array(
            'login' => $user['login'],
            'password' => $user['password'],
            'roles' => $roles
        );

    }

    /**
     * Gets user data by login.
     *
     * @access public
     * @param string $login User login
     *
     * @return array Result
     */
    public function getUserByLogin($login)
    {
        try {
            $query = '
              SELECT
              users.login, users.id, users.password, users.id_role
              FROM
                users
              WHERE
                users.login = :login
            ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('login', $login, \PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : current($result);
        } catch (\PDOException $e) {
            return array();
        }
    }
    /**
     * Gets user id by login.
     *
     * @access public
     * @param string $name User name
     *
     * @return array Result
     */
    public function getUserId($login)
    {
        try {
            $query = '
              SELECT
                users.id
              FROM
                users
              WHERE
                users.login = :login
            ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('login', $login, \PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : current($result);
        } catch (\PDOException $e) {
            return array();
        }
    }

    /**
     * Gets user roles by User ID.
     *
     * @access public
     * @param integer $userId User ID
     *
     * @return array Result
     */
    public function getUserRoles($userId)
    {
        $roles = array();
        try {
            $query = '
                SELECT
                    roles.name as role
                FROM
                    users
                INNER JOIN
                    roles
                ON
                    users.id_role = roles.id
                WHERE
                    users.id = :user_id
                ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('user_id', $userId, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if ($result && count($result)) {
                $result = current($result);
                $roles[] = $result['role'];
            }
            return $roles;
        } catch (\PDOException $e) {
            return $roles;
        }
    }
}