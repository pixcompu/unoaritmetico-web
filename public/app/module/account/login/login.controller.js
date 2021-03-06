(function () {
    'use strict';

    angular
        .module('app')
        .controller('LoginController', LoginController);

    LoginController.$inject =
        [
            '$auth',
            'ToastService',
            '$state'
        ];

    function LoginController($auth,
                             ToastService,
                             $state) {

        let vm = this;
        vm.email = '';
        vm.password = '';
        vm.isLoginIn = false;
        vm.login = login;

        if ($auth.isAuthenticated()) {
            $state.go('app.landing');
        }

        function login() {
            vm.isLoginIn = true;

            $auth.login({
                email: vm.email,
                password: vm.password
            })
                .then(function (response) {
                    $auth.setToken(response.data.meta.token);
                    vm.isLoginIn = false;
                    $state.go('app.landing', {}, {reload: true});
                })
                .catch(failedLogin);
        }

        function failedLogin(response) {
            vm.isLoginIn = false;
        }


    }
})();