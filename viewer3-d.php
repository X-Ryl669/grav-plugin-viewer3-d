<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class 3DViewerPlugin
 * @package Grav\Plugin
 */
class Viewer3dPlugin extends Plugin
{
    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                // Uncomment following line when plugin requires Grav < 1.7
                // ['autoload', 100000],
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
    * Composer autoload.
    * @return ClassLoader
    */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onMarkdownInitialized' => ['onMarkdownInitialized', 0],
            'onTwigSiteVariables'   => ['onTwigSiteVariables', 0],
        ]);
    }

    public function onMarkdownInitialized(Event $event)
    {
        $markdown = $event['markdown'];
        $config = $this->config;
        $ourConfig = $config->get('plugins.viewer3-d');
        $slug = $event['page']->slug();
        $this->used = false;

        // This feature depends on the page being provided in onMarkdownInitialized event.
        // See PR https://github.com/getgrav/grav/pull/2418
        if (isset($event['page'])) {
            $config = $this->mergeConfig($event['page']);
        }
        $markdown->addBlockType('`', 'Viewer3D', true, false, 0);
        $markdown->blockViewer3D = function($Line) {
            if (preg_match('/^```3dv$/', $Line['text'], $matches))
            {
                $Block = [ 'element' => ['name' => 'div', 'attribute' => [ 'class' => 'online_3d_viewer' ], 'text' => '' ] ];
                return $Block;
                $name = $matches[2];
                $text = $matches[3];
            }
	    return;
        };
        $markdown->blockViewer3DContinue = function($Line, array $Block) use ($slug) {
            if ( isset( $Block['complete'] ) )
            {
                return;
            }

            if (preg_match('/^```$/', $Line['text'], $matches))
            {
                $Block['markup'] = "<div class='online_3d_viewer' model='".$slug.'/'.$Block['src']."' camera='-1.5,-3.0,2.0,0,0,0,0,0,1' edge='#000000' color='#A0B0A0'><div class='toolbar'></div><div class='details'></div></div>";
                $Block['complete'] = true;
                $this->used = true;
                return $Block;
            }

            $Block['src'] = $Line['text'];
            return $Block;
        };
    }

    public function onTwigSiteVariables()
    {
        if (isset($this->used) && $this->used)
        {
            $this->grav['assets']->add('plugin://viewer3-d/assets/css/3dviewer.css');
            $this->grav['assets']->add('plugin://viewer3-d/assets/js/three.min.js');
            $this->grav['assets']->add('plugin://viewer3-d/assets/js/o3dv.min.js');
            $this->grav['assets']->add('plugin://viewer3-d/assets/js/3dviewer.js');
        }
    }

}
