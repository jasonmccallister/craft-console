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
use craft\web\Controller;
use InvalidArgumentException;
use mccallister\console\Console;
use yii\web\BadRequestHttpException;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
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
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['index'];

    /**
     * @var array Determines the commands that endpoint can run.
     * @access protected
     */
    protected $allowedCommands = [
        // 'backup/db',
        // 'cache/flush',
        // 'cache/flush-all',
        // 'cache/flush-schema',
        // 'cache/index',
        // 'clear-caches/all',
        // 'clear-caches/asset',
        // 'clear-caches/asset-indexing-data',
        // 'clear-caches/compiled-templates',
        // 'clear-caches/cp-resources',
        // 'clear-caches/data',
        // 'clear-caches/index',
        // 'clear-caches/temp-files',
        // 'clear-caches/template-caches',
        // 'clear-caches/transform-indexes',
        'gc/run' => 'theclass',
        // 'graphql/dump-schema',
        // 'graphql/print-schema',
        // 'resave/assets',
        // 'resave/categories',
        // 'resave/entries',
        // 'resave/matrix-blocks',
        // 'resave/tags',
        // 'resave/users',
    ];

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
            $token = $matches[1];
            try {
                $valid = $service->find($token);
            } catch (InvalidArgumentException $e) {
                $response->setStatusCode(400, 'Invalid console authorization token.');

                return $this->asErrorJson('Invalid console authorization token.');
            }
        }

        // verify the command is allowed, this should be permissions based on the token eventually
        $command = $request->getRequiredBodyParam("command");
        if (!array_key_exists($command, $this->allowedCommands)) {
            $response->setStatusCode(400, 'Console command is not authorized');

            return $this->asErrorJson('Console command is not authorized');
        }

        // call the command

        // return the output of the command as the message, if an option
        return $this->asJson([
            'message' => 'ok',
        ]);
    }
}
