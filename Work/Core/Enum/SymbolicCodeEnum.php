<?php
namespace Work\Core\Enum;

use Work\Core\Enum\Interface\EnumInterface;
use Work\Core\Enum\Traits\EnumTrait;

enum SymbolicCodeEnum :string implements EnumInterface
{
    use EnumTrait;

    case DOT        = '.';
    case COMMA      = ',';
    case PIPE       = '|';
    case COLON      = ':';
    case SEMICOLON  = ';';
    case AT_MARK    = '@';
    case UNDERBAR   = '_';
    case BACKSLASH  = '\\';
    case QUESTION   = '?';
    case SHARP      = '#';
    case EQUAL      = '=';
}