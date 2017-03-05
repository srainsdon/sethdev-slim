<?php

if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

use JeremyKendall\Password\PasswordValidator;
use JeremyKendall\Slim\Auth\Adapter\Db\PdoAdapter;
use JeremyKendall\Slim\Auth\Bootstrap;
use JeremyKendall\Slim\Auth\Exception\HttpForbiddenException;
use JeremyKendall\Slim\Auth\Exception\HttpUnauthorizedException;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;

spl_autoload_register(function ($classname) {
    require (__DIR__ . '/../src/classes/' . $classname . '.php');
});

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
//$settings['settings']['urlSettings'] = require_once __DIR__ . '/../src/db_settings.php';
$app = new \Slim\App($settings);

$container = $app->getContainer();


// Logger config
//$container['logger'] = function($c) {
//    $logger = new \Monolog\Logger('my_logger');
//    $file_handler = new \Monolog\Handler\StreamHandler("../logs/app.log");
//    $logger->pushHandler($file_handler);
//    return $logger;
//};

// DB config
$container['db'] = function ($c) {
    $db_creds = unserialize(getenv('sethdev_DB'));
    $pdo = new PDO("mysql:host=" . $db_creds['host'] . ";dbname=" . $db_creds['database'],
            $db_creds['username'], $db_creds['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
// route config
$container['routeSettings'] = function ($c) {
    $urlSettings = array();
    $stmt = $c['db']->query('SELECT * FROM setting');
    foreach ($stmt as $result) {
        $urlSettings[$result['SettingName']] = $result['SettingValue'];
    }
    return $urlSettings;
};

// Configure Slim Auth components
$validator = new PasswordValidator();
$adapter = new PdoAdapter($container['db'], 'members', 'username', 'password', $validator);
$acl = new \Example\Acl();
$sessionConfig = new SessionConfig();
$sessionConfig->setOptions(array(
    'remember_me_seconds' => 60 * 60 * 24 * 7,
    'name' => 'slim-auth-impl',
));
$sessionManager = new SessionManager($sessionConfig);
$sessionManager->rememberMe();
$storage = new SessionStorage(null, null, $sessionManager);
$authBootstrap = new Bootstrap($app, $adapter, $acl);
$authBootstrap->setStorage($storage);
$authBootstrap->bootstrap();

// Handle the possible 403 the middleware can throw
$app->error(function (\Exception $e) use ($app) {
    if ($e instanceof HttpForbiddenException) {
        return $app->render('403.twig', array('e' => $e), 403);
    }
    if ($e instanceof HttpUnauthorizedException) {
        return $app->redirectTo('login');
    }
    // You should handle other exceptions here, not throw them
    throw $e;
});

// Grabbing a few things I want in each view
$app->hook('slim.before.dispatch', function () use ($app) {
    $hasIdentity = $app->auth->hasIdentity();
    $identity = $app->auth->getIdentity();
    $role = ($hasIdentity) ? $identity['role'] : 'guest';
    $memberClass = ($role == 'guest') ? 'danger' : 'success';
    $adminClass = ($role != 'admin') ? 'danger' : 'success';
    $data = array(
        'hasIdentity' => $hasIdentity,
        'role' =>  $role,
        'identity' => $identity,
        'memberClass' => $memberClass,
        'adminClass' => $adminClass,
    );
    $app->view->appendData($data);
});
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('slim-skeleton');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true,
    'debug' => true,
);
$app->view->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new \Twig_Extension_Debug(),
);

// Define routes
$app->get('/', function () use ($app) {
    $readme = Parsedown::instance()->parse(
        file_get_contents(dirname(__DIR__) . '/README.md')
    );
    $app->render('index.twig', array('readme' => $readme));
});

$app->get('/member', function () use ($app) {
    $app->render('member.twig');
});

$app->get('/admin', function () use ($app) {
    $app->render('admin.twig');
});

// Login route MUST be named 'login'
$app->map('/login', function () use ($app) {
    $username = null;

    if ($app->request()->isPost()) {
        $username = $app->request->post('username');
        $password = $app->request->post('password');

        $result = $app->authenticator->authenticate($username, $password);

        if ($result->isValid()) {
            $app->redirect('/');
        } else {
            $messages = $result->getMessages();
            $app->flashNow('error', $messages[0]);
        }
    }

    $app->render('login.twig', array('username' => $username));
})->via('GET', 'POST')->name('login');

$app->get('/logout', function () use ($app) {
    if ($app->auth->hasIdentity()) {
        $app->auth->clearIdentity();
    }

    $app->redirect('/');
});

$app->run();

/**
 * Creates database table, users and database connection.
 *
 * @return \PDO
 */
function getDb()
{
    $dsn = 'sqlite::memory:';
    $options = array(
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    );

    try {
        $db = new \PDO($dsn, null, null, $options);
    } catch (\PDOException $e) {
        die(sprintf('DB connection error: %s', $e->getMessage()));
    }

    $create = 'CREATE TABLE IF NOT EXISTS [users] ( '
        . '[id] INTEGER  NOT NULL PRIMARY KEY, '
        . '[username] VARCHAR(50) NOT NULL, '
        . '[role] VARCHAR(50) NOT NULL, '
        . '[password] VARCHAR(255) NULL)';

    $delete = 'DELETE FROM users';

    $member = 'INSERT INTO users (username, role, password) '
        . "VALUES ('member', 'member', :pass)";

    $admin = 'INSERT INTO users (username, role, password) '
        . "VALUES ('admin', 'admin', :pass)";

    try {
        $db->exec($create);
        $db->exec($delete);

        $member = $db->prepare($member);
        $member->execute(array('pass' => password_hash('member', PASSWORD_DEFAULT)));

        $admin = $db->prepare($admin);
        $admin->execute(array('pass' => password_hash('admin', PASSWORD_DEFAULT)));
    } catch (\PDOException $e) {
        die(sprintf('DB setup error: %s', $e->getMessage()));
    }

    return $db;
}

// Set up dependencies
//require __DIR__ . '/../src/dependencies.php';

// Register middleware
//require __DIR__ . '/../src/middleware.php';

// Register routes
//require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
