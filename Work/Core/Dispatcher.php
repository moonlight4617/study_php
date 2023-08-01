<?php

namespace Work\Core;
require_once __DIR__ . '/Base.php';

use Work\Core\DB\Connection;
use Work\Core\Injenction\Injector;
use Work\Core\Libs\Path\NamespaceManager;
use Work\Core\Support\Controllers\ControllerBase;
use Work\Core\Support\Exceptions\NotFoundException;

class Dispatcher extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->_set();
    }

    private function _set() {
        $_GET['aaa'] = ['bbb' => [
            'ccc' => 1111
        ]
        ];
        $_POST['abc'] = 7777;
    }

    public function dispatch():void
    {
        echo '<pre>';

        $_dbn = Connection::setType()::get();
        $sql = 'SELECT id, name from user';
        $stmt = $_dbn->prepare($sql);
        var_dump($stmt);


        list($_controller, $_action) = WebRouter::createUri();
        $_controllerInstance = $this->_getControllerInstance($_controller);

        if(!method_exists($_controllerInstance, $_action))
            throw new NotFoundException();

        $_controllerInstance->setAction($_action)->run();
    }

    private function _getControllerInstance(string $_controller) :ControllerBase
    {
        $_className = sprintf('%s%sController', NamespaceManager::getAppController(), $_controller);
        list($_isReader, $_filePath) = $this->doCheckReaderbleFile($_classNAme);
        if(!$_isReader)
            throw new NotFoundException(sprintf('Not Found Class : %s', $_filePath));

        return Injector::callClass($_className);
    }
}