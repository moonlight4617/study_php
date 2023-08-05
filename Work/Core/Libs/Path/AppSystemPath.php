<?php

namespace Work\Core\Libs\Path;

use Work\Core\Libs\Traits\Property\PropertyTrait;

class AppSystemPath
{
    use PropertyTrait;

    private string $_app = '';

    private string $_https = '';

    private string $_appControllers = '';

    public function setAppPath(string $_systemPath): void
    {
        $this->setApp(sprintf('%s%s/', $_systemPath, 'app'))
            ->setHttp(sprintf('%s%s/', $this->getApp(), 'Http'))
            ->setAppControllers(sprintf('%s%s/', $this->getHttp(), 'Controllers'));
    }
}
