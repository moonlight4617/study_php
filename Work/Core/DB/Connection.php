<?php
namespace Work\Core\DB;

use Work\Core\DB\Parts\PDO;
use Work\Core\DB\Parts\Enum\DnsEnum;
use Work\Core\Enum\SymbolicCodeEnum;
use Work\Core\Libs\Config;

final class Connection {


    public function __clone() {
        throw new \ErrorException('Clone is not allowed against'. get_class($this));
    }

    private static string $_dbDriver = '';
    private static string $_instances = [];
    private static string $_dbType = '';

    private function __construct()
    {
        self::$_dbDriver = self::_getConfigDB('driver');
        $this->_init();
    }

    private function _init():void {
        try {
            $_dsn = vsprintf(DnsEnum::get(self::$_dbType),
                [ self::_getConfigDB('host')
                 ,self::_getConfigDB('database')
                 ,self::_getConfigDB('port')
                 ,self::_getConfigDB('charset')]);
            $_options = self::_setOption();

            self::$_instances[self::$_dbType] = new PDO(
                $_dsn
                ,self::_getConfigDB('username')
                ,self::_getConfigDB('password')
                ,$_options
            );

        } catch(\PDOException $_ex) {
            self::$_instances[self::$_dbType] = null;
            throw $_ex;
        }
    }

    private static function _getConfigDB(string $_target):string|array {
        return Config::get(sprintf('db.connections.%s.%s', self::$_dbType, $_target));
    }

    private static function _setOption() :array
    {
        $_replaceTarget = sprintf('%s%s', self::$_dbDriver, SymbolicCodeEnum::UNDERBAR->value);
        foreach(self::_getConfigDB('options') as $_attrKey => $_attrValue) {
            if (str_starts_with($_attrValue, self::$_dbDriver))
                $_attrValue = constant(sprintf("\%s::%s", self::$_dbDriver, str_replace($_replaceTarget, '', $_attrValue)));

            $_attrKey = constant(sprintf("\%s::%s", self::$_dbDriver, $_attrKey));
            $_ret[$_attrKey] = $_attrValue;
        }
        return $_ret;
    }

    public static function get() :PDO {
        if (!isset(self::$_instances[self::$_dbType]))
            new self();

        return self::$_instances[self::$_dbType];
    }

    public static function setType(string $_dbType = null) :string {
        self::$_dbType = (is_null($_dbType))? Config::get('db.default') : $_dbType;
        return self:class;
    }
}