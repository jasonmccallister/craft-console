<?php

/**
 * Console plugin for Craft CMS 3.x
 *
 * Add health checks to your Craft website, useful when running inside of Docker
 *
 * @link      https://mccallister.io
 * @copyright Copyright (c) 2019 Jason McCallister
 */

namespace mccallister\console\controllers;

use Craft;
use Exception;
use craft\web\Controller;
use InvalidArgumentException;
use mccallister\console\Console;

/**
 * Api Controller
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Jason McCallister
 * @package   HealthCheck
 * @since     1.0.0
 */
class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = ['index'];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/console/api
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();
        $response = Craft::$app->getResponse();
        $request = Craft::$app->getRequest();
        $service = Console::getInstance()->tokens;

        // find the token from the authorization header
        if (preg_match('/^Bearer\s+(.+)$/i', $request->headers->get('authorization'), $matches)) {
            try {
                $valid = $service->find($matches[1]);
            } catch (InvalidArgumentException $e) {
                $response->setStatusCode(400, 'Invalid console authorization token.');

                return $this->asErrorJson('Invalid console authorization token.');
            }
        }

        // verify the command is allowed, this should be permissions based on the token eventually
        $allowedCommands = Console::$plugin->getInstance()->commands->all();
        $command = $request->getRequiredBodyParam('command');
        if (!array_key_exists($command, $allowedCommands)) {
            $response->setStatusCode(400, 'Console command is not authorized');

            return $this->asErrorJson('Console command is not authorized');
        }

        try {
            // definitely not ideal
            if (!defined('STDOUT')) define('STDOUT', fopen('php://stdout', 'wb'));
            if (!defined('STDIN')) define('STDIN', fopen('php://stdin', 'wb'));
            if (!defined('STDERR')) define('STDERR', fopen('php://stderr', 'wb'));

            Console::$plugin->getInstance()->controllerNamespace = 'craft\console\controllers';

            $exitCode = Console::$plugin->getInstance()->runAction($command);
        } catch (Exception $e) {
            $response->setStatusCode(400, $e->getMessage());

            return $this->asErrorJson($e->getMessage());
        }

        if ($exitCode !== 0) {
            $response->setStatusCode(400, 'console command could not be run.');

            return $this->asErrorJson('console command could not be run.');
        }

        return $this->asJson([
            'message' => 'ok',
        ]);
    }
}
