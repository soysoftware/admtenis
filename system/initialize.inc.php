<?php

require_once(dirname(__FILE__) . '/../conf/config.conf.php');
require_once(dirname(__FILE__) . '/../include/autoload.inc.php');
Core_DBI::getInstance()->connect();
Core_DBI::getInstance()->selectDB(DB_NAME);
Core_DBI::getInstance()->autocommit(false);
Core_DBI::getInstance()->setCharset('utf8');

?>
