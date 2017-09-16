<?php
defined('MCMS_ACCESS') or die('No direct script access.');

\Monstercms\Core\User::logOut();

header("Location: " . $this->config['redirectAfterLogOut']);

exit();