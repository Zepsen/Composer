<?php

require_once(ROOT . '/components/AutoLoader.php');

/*
 * Autoload namespace
 */

$auto = new \AutoLoader();

$auto->addNamespace('MyPDO\Components', ROOT.'/components/');
$auto->addNamespace('MyPDO\Config', ROOT.'/config/');
$auto->addNamespace('MyPDO\Classes', ROOT.'/classes/');
$auto->addNamespace('MyPDO\Interfaces', ROOT.'/interfaces/');
$auto->addNamespace('MyPDO\DB', ROOT.'/db/');

$auto->register();

/*
 * Init configuration file
 */

    //new \MyPDO\Config\Config();

/*
 * Init DB
 */

    //\MyPDO\Components\DB::Init();
