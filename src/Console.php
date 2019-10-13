<?php

namespace mccallister\console;

use Craft;
use craft\base\Plugin;
use craft\events\RegisterCpNavItemsEvent;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;
use craft\web\twig\variables\Cp;
use mccallister\console\services\Tokens;
use yii\base\Event;

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
