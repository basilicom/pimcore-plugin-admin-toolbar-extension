<?php

namespace Basilicom\ToolbarExtension;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\PimcoreBundleAdminClassicInterface;
use Pimcore\Extension\Bundle\Traits\BundleAdminClassicTrait;

class PimcorePluginAdminToolbarExtensionBundle extends AbstractPimcoreBundle implements PimcoreBundleAdminClassicInterface
{
    use BundleAdminClassicTrait;

    /**
     * @return string
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * @return string[]
     */
    public function getJsPaths(): array
    {
        return [
            '/admin/basilicom-toolbar-extension/js/pimcore/config.js',
            '/bundles/pimcorepluginadmintoolbarextension/js/pimcore/startup.js'
        ];
    }

    /**
     * @return string[]
     */
    public function getCssPaths(): array
    {
        return [
            '/admin/basilicom-toolbar-extension/css/pimcore/custom_css.css'
        ];
    }
}
