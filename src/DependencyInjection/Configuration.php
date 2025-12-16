<?php

namespace Basilicom\ToolbarExtension\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('pimcore_plugin_admin_toolbar_extension');
        $rootNode = $treeBuilder->getRootNode();
        $children = $rootNode->children();

        $children
            ->scalarNode('custom_css')
                ->info('add custom css file e.g. /build/static/css/custom.css')
                ->defaultValue(null)
            ->end()
        ->end();

        $allowedKeys = [
            'main_toolbar' => 'main toolbar configuration',
            'file_menu' => 'file menu configuration',
            'extras_menu' => 'extras menu configuration',
            'settings_menu' => 'tools menu configuration',
        ];

        foreach ($allowedKeys as $key => $info) {
            $this->addConfigGroupNode($children, $key, $info);
        }

        return $treeBuilder;
    }

    private function addConfigGroupNode(NodeBuilder $nodeBuilder, string $key, string $info): void
    {
        $nodeBuilder
            ->arrayNode($key)
                ->info($info)
                ->useAttributeAsKey('name')
                    // Definiert die Struktur für JEDEN dynamischen Key (z.B. 'services', 'docs')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('label')->isRequired()->end()
                            ->scalarNode('url')->defaultValue(null)->end()
                            ->scalarNode('iconCls')->defaultValue('pimcore_nav_icon_server_info')->end()
                            ->booleanNode('new_window')->defaultValue(false)->end()

                            ->arrayNode('menu')
                                ->useAttributeAsKey('name')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('label')->isRequired()->end()
                                        ->scalarNode('url')->end()
                                        ->scalarNode('iconCls')->defaultValue('pimcore_nav_icon_server_info')->end()
                                        ->booleanNode('new_window')->defaultValue(false)->end()

                                        ->arrayNode('menu')
                                            ->useAttributeAsKey('name')
                                            ->arrayPrototype()
                                                ->children()
                                                    ->scalarNode('label')->isRequired()->end()
                                                    ->scalarNode('url')->end()
                                                    ->scalarNode('iconCls')->defaultValue('pimcore_nav_icon_server_info')->end()
                                                    ->booleanNode('new_window')->defaultValue(false)->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
