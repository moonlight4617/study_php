<?php

namespace Work\Core\Libs\Path;

use Work\Core\Enum\SymbolicCodeEnum;

class PathManager
{
    private static ?SystemPath $_systemPath = null;
    private static ?AppSystemPath $_appSystemPath = null;

    public function __construct(SystemPath $_systemPath, AppSystemPath $_appSystemPath, string $_rootPath)
    {
        self::$_systemPath = $_systemPath;
        self::$_appSystemPath = $_appSystemPath;
        $this->_init($_rootPath);
    }

    private function _init(string $_rootPath): void
    {
        self::$_systemPath->setSystemPath($_rootPath);
        self::$_appSystemPath->setAppPath(self::$_systemPath->getSystem());
    }

    public static function getPathTrimLast(string $_methodName)
    {
        return rtrim(self::{$_methodName}(), DIRECTORY_SEPARATOR);
    }

    public static function __callStatic(string $_methodName, array $_args)
    {
        if (!preg_match('~^(set|get)(A-Z)(.*)$~', $_methodName, $_matches))
            throw new \ErrorException(sprintf('Method Not Format : %s ', $_methodName));

        $_property = self::_createProperty($_matches);

        switch ($_matches[1]) {
            case 'set':
                return self::_setPath($_property, $_methodName, current($_args));
            case 'get':
                return self::_getPath($_property, $_methodName);
            default:
                throw new \ErrorException(sprintf('Method Not Exists : %s ', $_methodName));
        }
    }

    private static function _createProperty(array $_matches): string
    {
        $_property = sprintf('%s%s%s', SymbolicCodeEnum::UNDERBAR->value, strtolower($_matches[2]), $_matches[3]);

        if (!property_exists(self::$_systemPath, $_property) && !property_exists(self::$_appSystemPath, $_property))
            throw new \ErrorException(sprintf('Property Not Exists : %s ', $_property));

        if (property_exists(self::$_systemPath, $_property) && property_exists(self::$_appSystemPath, $_property))
            throw new \ErrorException(sprintf('Property Duplication : %s ', $_property));

        return $_property;
    }

    private static function _getPath(string $_property, string $_methodName): string
    {
        if (property_exists(self::$_systemPath, $_property))
            return self::$_systemPath->{$_methodName}();

        if (property_exists(self::$_appSystemPath, $_property))
            return self::$_appSystemPath->{$_methodName}();
    }

    private static function _setPath(string $_property, string $_methodName, string $_args): string
    {
        if (property_exists(self::$_systemPath, $_property))
            self::$_systemPath->{$_methodName}($_args);

        return self::class;
    }
}
