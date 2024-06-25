<?php
use System\Core\ConfigRouter;
$configRouter = new ConfigRouter();
$configRouter->add([
    'web' => 'web',
    'api' => 'api'
])->work();