<?php

namespace mccallister\console;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\Cp;
use craft\web\twig\variables\CraftVariable;
use mccallister\console\records\Job;
use mccallister\console\services\Commands;
use mccallister\console\services\Tokens;
use yii\base\Event;
use yii\queue\PushEvent;
use yii\queue\Queue;

/**
 * @author    Jason McCallister
 * @package   Console
 * @since     1.0.0
 */
class Console extends Plugin
{
    /**
     * @var bool Whether the plugin has its own section in the CP
     */
    public $hasCpSection = true;

    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * Console::$plugin
     *
     * @var Console
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * Console::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // setup our services
        $this->setComponents([
            'tokens' => Tokens::class,
            'commands' => Commands::class,
        ]);

        // Register cp routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['console/tokens'] = 'console/tokens';
                $event->rules['console/tokens/new'] = 'console/tokens/edit';
            }
        );

        // Register our site routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['console/api'] = 'console/api';
            }
        );

        // list to the queue
        Event::on(Queue::class, Queue::EVENT_AFTER_PUSH, function(PushEvent $event) {
            $queuedJob = $event->job;
            $job = new Job();
            $job->delay = $queuedJob->delay ?? 0;
            $job->priority = $queuedJob->priority ?? null;
            $job->class = get_class($queuedJob);
            $job->elementType = $queuedJob->elementType ?? null;
            $job->elementId = $queuedJob->elementId;
            $job->siteId = $queuedJob->siteId ?? 0;
            $job->description = $queuedJob->description ?? null;
            $job->progress = 0;
            $job->progressLabel = null;
            $job->event = json_encode($event);
            $job->payload = json_encode($event->job);
            $job->save();
        });

        Event::on(Queue::class, Queue::EVENT_BEFORE_EXEC, function(PushEvent $event) {
            // Craft::dd($event);
        });

        Event::on(CraftVariable::class, CraftVariable::EVENT_INIT, function(Event $e) {
            /** @var CraftVariable $variable */
            $variable = $e->sender;

            // Attach a service:
            $variable->set('commands', Commands::class);
        });

        // Register the sidebar icons
        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function(RegisterCpNavItemsEvent $event) {
                $event->navItems[] = [
                    'url' => 'console',
                    'label' => 'Console',
                    'icon' => '@mccallister/console/icon-mask.svg',
                ];
            }
        );

        Craft::info(
            Craft::t(
                'console',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }
}
