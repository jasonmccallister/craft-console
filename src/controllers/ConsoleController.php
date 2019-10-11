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
class ConsoleController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected $allowAnonymous = ['api'];

    /**
     * @var array Determines the commands that endpoint can run.
     * @access protected
     */
    protected $allowedCommands = [
        'backup/db',
        'cache/flush',
        'cache/flush-all',
        'cache/flush-schema',
        'cache/index',
        'clear-caches/all',
        'clear-caches/asset',
        'clear-caches/asset-indexing-data',
        'clear-caches/compiled-templates',
        'clear-caches/cp-resources',
        'clear-caches/data',
        'clear-caches/index',
        'clear-caches/temp-files',
        'clear-caches/template-caches',
        'clear-caches/transform-indexes',
        'gc/run',
        'graphql/dump-schema',
        'graphql/print-schema',
        'resave/assets',
        'resave/categories',
        'resave/entries',
        'resave/matrix-blocks',
        'resave/tags',
        'resave/users',
    ];

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/console/api
     *
     * @return mixed
     */
    public function actionApi()
    {
        // TODO check the token for authentication
        // TODO add a list of console commands that are available

        return $this->asErrorJson('could not run the console command');
    }
}
