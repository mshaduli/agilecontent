'use strict';


// Declare app level module which depends on filters, and services
angular.module('OperatorListApp', ['OperatorListApp.filters', 'OperatorListApp.services', 'OperatorListApp.directives', 'ngResource']).
  config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/list', {templateUrl: '/bundles/tneoperator/angular/app/partials/listview.html', controller: ListCtrl});
    $routeProvider.when('/map', {templateUrl: '/bundles/tneoperator/angular/app/partials/mapview.html', controller: MapCtrl});
    $routeProvider.otherwise({redirectTo: '/list'});
  }])
  .factory('Accommodation', function($resource){
    return $resource('/app_dev.php/operators/:id', {id: '@id'}, {});
  })
  .factory('Attraction', function($resource){
    return $resource('/app_dev.php/operators/attractions/:id', {id: '@id'}, {});
  })    
  .factory('Event', function($resource){
    return $resource('/app_dev.php/operators/events/:id', {id: '@id'}, {});
  })
  .factory('Destination', function($resource){
    return $resource('/app_dev.php/operators/destinations/:id', {id: '@id'}, {});
  })
  ;
