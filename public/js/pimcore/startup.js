pimcore.registerNS("pimcore.plugin.BasilicomServicesDashboards");

pimcore.plugin.BasilicomServicesDashboards = Class.create({

    initialize: function () {
        document.addEventListener(pimcore.events.preMenuBuild, this.preMenuBuild.bind(this));
    },

    preMenuBuild: function (e) {
        let pimcoreMenu = e.detail.menu;
        const perspectiveCfg = pimcore.globalmanager.get("perspective");
        const config = pimcore.basilicomToolbarExtensionConfig;

        if (Object.keys(config.main_toolbar).length > 0) {
            this.attachToToolbar(pimcoreMenu, config);
        }

        if (pimcoreMenu.extras && perspectiveCfg.inToolbar("extras")) {
            if (Object.keys(config.extras_menu).length > 0) {
                this.attachToExistingNode(pimcoreMenu.extras, config.extras_menu);
            }
        }

        if (pimcoreMenu.settings && perspectiveCfg.inToolbar("settings")) {
            if (Object.keys(config.settings_menu).length > 0) {
                this.attachToExistingNode(pimcoreMenu.settings, config.settings_menu);
            }
        }

        if (pimcoreMenu.file && perspectiveCfg.inToolbar("file")) {
            if (Object.keys(config.file_menu).length > 0) {
                this.attachToExistingNode(pimcoreMenu.file, config.file_menu);
            }
        }
    },

    attachToToolbar: function(pimcoreMenu, config) {

        for (let menuEntry in config.main_toolbar) {
            const firstLevelNav = config.main_toolbar[menuEntry];

            let menuItems = this.processNestedMenuItems(firstLevelNav.menu);
            let handler = this.showMenu;
            if (menuItems.length <= 0) {
                handler = function () {
                    this.openService({
                        handlerData: Ext.apply({}, firstLevelNav, {
                            key: menuEntry
                        })
                    })
                }.bind(this);
            }

            pimcoreMenu['basilicom_navigation_extension_' + menuEntry] = {
                label: t('myBundleLabel'),
                iconCls: firstLevelNav.iconCls,
                items: menuItems,
                shadow: false,
                noSubmenus: !menuItems.length > 0,
                handler: handler,
                cls: "pimcore_navigation_flyout",
            };
        }
    },

    attachToExistingNode: function (node, config) {
        let menuItems = this.processNestedMenuItems(config);
        for( let i = 0; i < menuItems.length; i++ ) {
            node.items.push(menuItems[i]);
        }
    },

    processNestedMenuItems: function (menu, depth = 0) {
        let items = [];
        if (menu.length !== 0) {

            for (let currentNavKey in menu) {
                let currentNavItem = menu[currentNavKey];
                let subMenu = null;

                if (currentNavItem.menu !== undefined && currentNavItem.menu.length !== 0) {
                    subMenu = {
                        cls: "pimcore_navigation_flyout",
                        shadow: false,
                        items: this.processNestedMenuItems(currentNavItem.menu, depth + 1)
                    }
                }

                items.push({
                    text: currentNavItem.label,
                    iconCls: currentNavItem.iconCls,
                    itemId: 'basilicom_navigation_extension_' + depth + '_' + currentNavKey,
                    handlerData: Ext.apply({}, currentNavItem, {
                        key: currentNavKey
                    }),
                    handler: subMenu ? this.showSubMenu : this.openService,
                    menu: subMenu
                });
            }
        }

        return items;
    },

    openService: function (e) {
        let data = e.handlerData;
        if (data.new_window) {
            window.open(data.url);
        } else {
            pimcore.helpers.openGenericIframeWindow('BasilicomServicesDashboards_' + data.key, data.url, data.icon, data.label);
        }
    }
});

new pimcore.plugin.BasilicomServicesDashboards();
