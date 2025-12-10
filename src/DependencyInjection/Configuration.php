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

        $allowedKeys = [
            'main_toolbar' => 'Konfiguration für Einträge in der Haupt-Toolbar.',
            'extras_menu' => 'Konfiguration für Einträge im Extras-Menü.',
            'settings_menu' => 'Konfiguration für Einträge im Tools-Menü.',
            'file_menu' => 'Konfiguration für Einträge im Marketing-Menü.',
        ];

        $children = $rootNode->children();

        foreach ($allowedKeys as $key => $info) {
            $this->addConfigGroupNode($children, $key, $info);
        }

        return $treeBuilder;
    }

    /**
     * Fügt einen Array-Knoten der obersten Ebene (z.B. main_toolbar) hinzu.
     */
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
