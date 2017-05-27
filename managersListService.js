(function () {
    'use strict';

    angular.module('pages.managersList')
        .service('managersListService', managersListService);

    /** @ngInject */
    function managersListService(apiService) {

        return {
            getList : getList,
            deleteCompany : deleteCompany
        };

        function getList(data) {
            return apiService.$get('admins', data).then(function (response) {
                return response
            }, function (error) {
                return error
            })
        }

        function deleteCompany (data) {
            return apiService.$delete('admins/' + data).then(function (response) {
                return response
            }, function (error) {
                return error
            })
        }
    }

})();