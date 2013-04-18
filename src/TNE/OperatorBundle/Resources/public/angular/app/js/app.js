'use strict';

var OperatorListApp = angular.module('OperatorListApp', ['OperatorListApp.filters']);

angular.module('OperatorListApp.filters', []).
    filter('truncate', function () {
        return function (text, length, end) {
            if (isNaN(length))
                length = 10;

            if (end === undefined)
                end = "...";

            if (text.length <= length || text.length - end.length <= length) {
                return text;
            }
            else {
                return String(text).substring(0, length-end.length) + end;
            }

        }
    })
    .filter('distance', function(){})
    .filter('price', function(){})
    ;

    
function ListController($scope, $http, $templateCache)
{
    $scope.filters = {
        destination: '1',
        distance: '10km'
    };
    
    $http({
       method : 'GET',
       url : '/app_dev.php/operators/destinations'
    }).
    success(function(data, status, headers, config) {
      $scope.destinations = data;
    }).
    error(function(data, status, headers, config) {
      console.log(data);
      return false;
    });    

    $scope.listViewUrl     = '/bundles/tneoperator/angular/app/partials/listview.html';
    $scope.destinationsUrl = '/bundles/tneoperator/angular/app/partials/destinations.html';
   
    
    $scope.filter = function(){
        
        console.log('filter');
        
        $templateCache.removeAll();                
        
        $http({
           method : 'GET',
           url : '/app_dev.php/operators?town='+$scope.filters.destination+'&distance='+$scope.filters.distance
        }).
        success(function(data, status, headers, config) {
          $scope.accommodation = data;
          $scope.$$phase || $scope.$apply();
        }).
        error(function(data, status, headers, config) {
          console.log(data);
          return false;
        });  

        $http({
           method : 'GET',
           url : '/app_dev.php/operators/events?town='+$scope.filters.destination+'&distance='+$scope.filters.distance
        }).
        success(function(data, status, headers, config) {
          $scope.events = data;
          $scope.$$phase || $scope.$apply();
        }).
        error(function(data, status, headers, config) {
          console.log(data);
          return false;
        });  

        $http({
           method : 'GET',
           url : '/app_dev.php/operators/attractions?town='+$scope.filters.destination+'&distance='+$scope.filters.distance
        }).
        success(function(data, status, headers, config) {
          $scope.attractions = data;
        }).
        error(function(data, status, headers, config) {
          console.log(data);
          $scope.$$phase || $scope.$apply();
          return false;
        });    
        
    }
    
    
    $scope.filter();    
}