(function () {
    'use strict';

    angular.module('pages.managersList', [])
        .config(routeConfig);

    /** @ngInject */
    function routeConfig($stateProvider) {
        $stateProvider
            .state('managersList', {
                url: '/managers-list',
                templateUrl: 'app/pages/managersList/managersList.html',
                title: 'Admin List',
                controller: 'managersListCtrl',
                controllerAs: 'ctrl',
                userRoles : ['admin', 'super-admin'],
                sidebarMeta: {
                    icon: 'ion-android-home',
                    order: 4
                }
            });
    }

})();
