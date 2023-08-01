<?php

namespace Work\Core\Enum\Interface;

Interface EnumInterface
{
    public static function findByValue(int $_value):?static;

    public static function findByName(string $_name):?static;
}