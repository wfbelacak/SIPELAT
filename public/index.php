<?php

// Start session
session_start();

// Require configuration files
require_once '../app/config/config.php';
require_once '../app/config/database.php';

// Require core files
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';

// Require Middleware
require_once '../app/middleware/AuthMiddleware.php';

// Require Helpers
require_once '../app/helpers/helper.php';

// Instantiate the App class
$app = new App();
