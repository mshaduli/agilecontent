'use strict';


// Declare app level module which depends on filters, and services
angular.module('OperatorListApp', ['OperatorListApp.filters', 'OperatorListApp.services', 'OperatorListApp.directives', 'ngResource']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/list', {templateUrl: '/bundles/tneoperator/angular/app/partials/listview.html', controller: ListCtrl});
    $routeProvider.when('/map', {templateUrl: '/bundles/tneoperator/angular/app/partials/mapview.html', controller: MapCtrl});
    $routeProvider.otherwise({redirectTo: '/list'});
  }]).factory('Operator', function($resource){
    return $resource('/app_dev.php/operators/:id', {id: '@id'}, {});
  });
