<?php
namespace Work\Core\DB\Parts\enum;

use Work\Core\Enum\Interface\EnumInterface;
use Work\Core\Enum\Traits\EnumTrait;

enum DnsEnum:string implements EnumInterface
{
    use EnumTrait;

    case MYSQL = 'mysql';
    case POSTGRESS = 'pgsql';

    public static function get(string $dbType): string {
        return match($dbType) {
            self::MYSQL->value => 'mysql:host=%s;dbname=%s;port=%s;charset=%s',
        };
    }
}