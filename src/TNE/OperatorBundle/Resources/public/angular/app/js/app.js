'use strict';


// Declare app level module which depends on filters, and services
angular.module('OperatorListApp', ['OperatorListApp.filters', 'OperatorListApp.services', 'OperatorListApp.directives', 'OperatorListApp.controllers']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/list', {templateUrl: '/bundles/tneoperator/angular/app/partials/listview.html', controller: 'MyCtrl1'});
    $routeProvider.when('/map', {templateUrl: '/bundles/tneoperator/angular/app/partials/mapview.html', controller: 'MyCtrl2'});
    $routeProvider.otherwise({redirectTo: '/list'});
  }]);
