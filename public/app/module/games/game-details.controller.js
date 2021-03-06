(function () {
    'use strict';

    angular
        .module('app')
        .controller('GameDetailsController', GameDetailsController);

    GameDetailsController.$inject =
        [
            'entity',
            'ToastService',
            '$window'
        ];

    function GameDetailsController(entity,
                                   ToastService,
                                   $window) {

        let vm = this;

        vm.game = entity;
        vm.getDescription = getDescription;
        vm.exportGame = exportGame;

        function getDescription(card) {
            let description = "";
            if (card.operation) {
                description += "Operacion: " + card.operation + " = " + card.result;
            } else if (card.power) {
                description += "Poder: " + card.power.data.name;
            }
            if (card.color) {
                description += (description.length ? " -" : "") + " Color :" + card.color.data.name;
            }
            return description;
        }

        function exportGame(){
            ToastService.show('Generando reporte, esta operación tomará unos segundos');
            $window.location = '/api/games/' + vm.game.id + '/export';
        }

    }
})();