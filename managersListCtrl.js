(function () {
    'use strict';

    angular.module('pages.managersList')
        .controller('managersListCtrl', managersListCtrl);

    /** @ngInject */
    function managersListCtrl(managersListService, $state, $localStorage, toastr) {
        var ctrl = this;

        ctrl.getManagersList = getManagersList;
        ctrl.goToEditUser = goToEditUser;
        ctrl.ManagerSearch = ManagerSearch;
        ctrl.deleteCard = deleteCard;

        $localStorage.userData.role != 'super-admin' ? ctrl.showEditBtn = false : ctrl.showEditBtn = true;

        ctrl.ManagerSearchData = '';
        ctrl.pageData = {
            'page' : 1,
            'total' : '',
            'per-page' : 20,
            'limit' : 20,
            'maxPaginLength' : 2
        };

        function getManagersList() {
            managersListService.getList(ctrl.pageData).then(function(response){
                ctrl.smartTableData = response.data.items;

                ctrl.smartTableData.length > 0 ? ctrl.UserPresence = true : ctrl.companyPresence = false;

                ctrl.smartTableData.forEach(function(obj){
                    var date = new Date(obj.createdAt*1000);
                    var formatted = new Date(date.toISOString());
                    obj.reformatDate = (formatted.getMonth() + 1) + '.' + ('0' + formatted.getDate()).slice(-2) + '.' + formatted.getFullYear();
                });
                ctrl.pageData.total = response.data._meta.totalCount;
            });
        }

        getManagersList();

        function goToEditUser(id) {
            $state.go('managersEdit', { id: id });
        }

        function ManagerSearch() {
            ctrl.pageData.username = ctrl.ManagerSearchData;
            getManagersList();
        }
        
        function deleteCard(id) {
            managersListService.deleteCompany(id).then(function (response) {
                if(response.status === 204) {
                    toastr.success('Deleted');
                    getManagersList();
                } else {
                    var messages = '';
                    angular.forEach(response.data, function(item) {
                        messages += item.message + '<br>';
                    });

                    toastr.error(messages , response.statusText, {
                        allowHtml: true,
                        timeOut: 10000
                    });
                }
            })
        }
    }

})();