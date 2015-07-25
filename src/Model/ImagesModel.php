<?php
/**
 * Images model.
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
class ImagesModel
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
     * Gets all images.
     *
     * @access public
     * @return array Result
     */
    public function getAllImages()
    {
        $query = '
        SELECT
            images.id, images.name, images.title
        FROM
            images
        ORDER BY
           images.date DESC;
        ';
        $result = $this->db->fetchAll($query);
        return !$result ? array() : $result;
    }
    /**
     * Gets single image info.
     *
     * @access public
     * @return array Result
     */
    public function getAllImageInfo($id)
    {
        if (($id != '') && ctype_digit((string)$id)) {
            $query = '
        SELECT
            images.id, images.name, images.title, images.description, images.date,
            images.license, images.comments_count, images.scores, users.login
        FROM
            images
        INNER JOIN
            users
        ON
            images.id_user = users.id
        WHERE
            images.id = :id
        ORDER BY
            images.data DESC;
        ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('id', $id, \PDO::PARAM_INT);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return !$result ? array() : current($result);
        } else {
            return array();
        }
    }
    /**
     * Save image.
     *
     * @access public
     * @param array $image Image data from request
     * @param string $mediaPath Path to media folder on disk
     * @throws \PDOException
     * @return mixed Result
     */
    public function saveImage($image, $mediaPath, $data)
    {
        try {
            $originalFilename = $image['image']->getClientOriginalName();
            $newFilename = $this->createName($originalFilename);
            $image['image']->move($mediaPath, $newFilename);
            $this->saveFilename($newFilename, $data);
            return true;
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    /**
     * Save filename in database.
     *
     * @access protected
     * @param string $name Filename
     * @return mixed Result
     */
    protected function saveFilename($name, $data)
    {
        return $this->db
            ->insert(
                'images',
                array(
                    'name' => $name,
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'license' => $data['license'],
                    'date' => $data['date'],
                    'user' => $data['user']
                )
            );
    }
//    /**
//     * Save filename in database.
//     *
//     * @access protected
//     * @param string $name Filename
//     * @return mixed Result
//     */
//    protected function imageInfo()
//    {
//        return $this->db->insert('images', array('name' => $name));
//        return $imageInfo;
//    }

    /**
     * Creates random filename.
     *
     * @access protected
     * @param string $name Source filename
     *
     * @return string Result
     */
    protected function createName($name)
    {
        $newName = '';
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $newName = $this->createRandomString(32) . '.' . $ext;

        while (!$this->isUniqueName($newName)) {
            $newName = $this->createRandomString(32) . '.' . $ext;
        }

        return $newName;
    }

    /**
     * Creates random string.
     *
     * @acces protected
     * @param integer $length String length
     *
     * @return string Result
     */
    protected function createRandomString($length)
    {
        $string = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $string .= $keys[array_rand($keys)];
        }
        return $string;
    }

    /**
     * Checks if filename is unique.
     *
     * @access protected
     * @param string $name Name
     * @return bool Result
     */
    protected function isUniqueName($name)
    {
        try {
            $query = '
              SELECT
                COUNT(*) AS files_count
              FROM
                images
              WHERE
                name = :name
              ';
            $statement = $this->db->prepare($query);
            $statement->bindValue('name', $name, \PDO::PARAM_STR);
            $statement->execute();
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            $result = current($result);
            return !$result['files_count'];
        } catch (\PDOException $e) {
            throw $e;
        }
    }

}
///* Save album.
//     *
//     * @access public
//     * @param array $album Album data
//* @retun mixed Result
//*/
//    public function saveImage($image)
//{
//    if (isset($image['id'])
//        && ($image['id'] != '')
//        && ctype_digit((string)$image['id'])) {
//        // update record
//        $id = $image['id'];
//        unset($image['id']);
//        return $this->db->update('images', $image, array('id' => $id));
//    } else {
//        // add new record
//        return $this->db->insert('images', $image);
//    }
//}