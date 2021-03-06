(function () {
    'use strict';

    angular
        .module('app')
        .controller('StudentDetailsController', StudentDetailsController);

    StudentDetailsController.$inject =
        [
            'entity',
            'ToastService',
            '$window'
        ];

    function StudentDetailsController(entity,
                                      ToastService,
                                      $window) {

        let vm = this;
        vm.student = entity;
        vm.exportGame = exportGame;

        let operationData = vm.student.stadistics.operation;
        vm.operationsGraph = {
            data: [
                operationData.additionCount,
                operationData.multiplicationCount,
                operationData.divisionCount,
                operationData.substractionCount],
            labels: ["Suma", "Multiplicacion", "División", "Resta"],
            options: {
                legend: {
                    display: true,
                }
            }
        };
        let gameData = vm.student.stadistics.game;
        vm.gamesGraph = {
            data: [gameData.winnedCount, gameData.lostCount],
            labels: ["Ganadas", "Perdidas"],
            options: {
                legend: {
                    display: true,
                }
            }
        };

        function exportGame(game){
            ToastService.show('Generando reporte, esta operación tomará unos segundos');
            $window.location = '/api/games/' + game.id + '/export';
        }
    }
})();