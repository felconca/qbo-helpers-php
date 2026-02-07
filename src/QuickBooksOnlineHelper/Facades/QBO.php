<?php

namespace QuickBooksOnlineHelper\Facades;

use QuickBooksOnlineHelper\QuickBooksOnline;
use Exception;

class QBO
{
    protected static $service;
    protected static $companyId;
    protected static $accessToken;
    protected static $sandbox;

    /**
     * Initialize QBO API with credentials
     */
    public static function setAuth($companyId, $accessToken, $sandbox = false)
    {
        self::$companyId = $companyId;
        self::$accessToken = $accessToken;
        self::$sandbox = $sandbox;
        self::$service = new QuickBooksOnline();
    }

    // CRUD entry points
    public static function create()
    {
        return new static('create');
    }
    public static function update()
    {
        return new static('update');
    }
    public static function get()
    {
        return new static('findById');
    }
    public static function all()
    {
        return new static('findAll');
    }
    public static function delete()
    {
        return new static('delete');
    }
    public static function query(string $query)
    {
        if (!self::$service) {
            throw new Exception("QBO not initialized. Call QBO::setAuth() first.");
        }

        return self::$service->query(
            $query,
            self::$companyId,
            self::$accessToken,
            75,
            self::$sandbox
        );
    }


    private $mode;

    public function __construct($mode = null)
    {
        $this->mode = $mode;
    }

    public function __call($entity, $args)
    {
        if (!self::$service) {
            throw new Exception("QBO not initialized. Call QBO::setAuth() first.");
        }

        $entity = ucfirst($entity);
        $service = self::$service;
        $companyId = self::$companyId;
        $accessToken = self::$accessToken;
        $sandbox = self::$sandbox;

        switch ($this->mode) {
            case 'create':
                $data = isset($args[0]) ? $args[0] : [];
                return $service->create($entity, $companyId, $accessToken, $data, '', 75, $sandbox);

            case 'update':
                $data = isset($args[0]) ? $args[0] : [];
                return $service->update($entity, $companyId, $accessToken, $data, '', 75, $sandbox);

            case 'findById':
                $id = isset($args[0]) ? $args[0] : null;
                return $service->findById($entity, $id, $companyId, $accessToken, 75, $sandbox);

            case 'findAll':
                $filter = isset($args[0]) ? $args[0] : '';
                return $service->findAll($entity, $companyId, $accessToken, $filter, 75, $sandbox);

            case 'delete':
                $id = isset($args[0]) ? $args[0] : null;
                $token = isset($args[1]) ? $args[1] : null;
                return $service->delete($entity, $id, $token, $companyId, $accessToken, 75, $sandbox);

                // case 'query':
                //     $where = isset($args[0]) ? $args[0] : '';
                //     // Example: QBO::query()->Invoice("WHERE DocNumber = '101'")
                //     $queryString = "SELECT * FROM $entity " . $where;
                //     return $service->query($queryString, $companyId, $accessToken, '', 75, $sandbox);
                // case 'query':
                //     $query = isset($args[0]) ? $args[0] : '';
                //     // Example: QBO::query()->Invoice("select * from Invoice where DocNumber = '101'")
                //     // Now expects a full query string, not just a where clause
                //     return $service->query($query, $companyId, $accessToken, '', 75, $sandbox);

            default:
                throw new Exception("Invalid operation mode: {$this->mode}");
        }
    }
}
