<?php namespace Monstercms\Modules\Users;

use Monstercms\Core\User;

class Users extends \Monstercms\Core\ModelAbstract{

    private $dbTbale;

    public function __construct($config)
    {
        $this->dbTbale = DB_TABLE_USERS;
        parent::__construct($config);
    }

    public function getAll()
    {
        $sql = "SELECT * FROM `{$this->dbTbale}`";

        $result = $this->db->query($sql);

        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById($userId)
    {
        $userId = (int) $userId;
        $sql = "SELECT `login`, `role` FROM `{$this->dbTbale}` WHERE id = {$userId}";

        $result = $this->db->query($sql);

        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    public function update($data, $userId)
    {
        $userId = (int) $userId;

        $list = array();

        if (isset($data['login'])) {
            $list['login'] = $data['login'];
        }

        if (isset($data['role'])) {
            $list['role'] = $data['role'];
        }

        if (isset($data['password']) && !empty($data['password'])) {
            $list['password'] = User::generateHash($data['password']);
        }

        $this->db->update($list, $this->dbTbale, $userId);

    }

    public function add($data)
    {

        $list = array();

        if (isset($data['login'])) {
            $list['login'] = $data['login'];
        }

        if (isset($data['role'])) {
            $list['role'] = $data['role'];
        }

        if (isset($data['role'])) {
            $list['password'] = User::generateHash($data['password']);
        }

        $this->db->insert($list, $this->dbTbale);
        return $this->db->lastInsertId();

    }

    public function delete($userId) {
        $this->db->delete($this->dbTbale, $userId);

    }
}