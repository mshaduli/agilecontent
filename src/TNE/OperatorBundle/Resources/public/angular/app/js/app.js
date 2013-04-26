'use strict';

var OperatorListApp = angular.module('OperatorListApp', ['OperatorListApp.filters']);

OperatorListApp.config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }
);

OperatorListApp.factory('DestinationService', function($http, $q){
   return {
     get: function() {
       //create our deferred object.
       var deferred = $q.defer();

       $http.get('/app_dev.php/operators/destinations').success(function(data) {
          deferred.resolve(data);
       }).error(function(){
          deferred.reject();
       });

       return deferred.promise;
     }
   }
});


OperatorListApp.factory('OperatorService', function($http, $q){
   return {
     getAccommodation: function(filters) {
       //create our deferred object.
       var deferred = $q.defer();

       $http.get('/app_dev.php/operators'+queryString(filters)).success(function(data) {
          deferred.resolve(data);                     
       }).error(function(){
          deferred.reject();
       });

       return deferred.promise;
     },
     getEvents: function(filters) {
       //create our deferred object.
       var deferred = $q.defer();

       $http.get('/app_dev.php/operators/events'+queryString(filters)).success(function(data) {
          deferred.resolve(data);                     
       }).error(function(){
          deferred.reject();
       });

       return deferred.promise;
     },
     getAttractions: function(filters) {
       //create our deferred object.
       var deferred = $q.defer();

       $http.get('/app_dev.php/operators/attractions'+queryString(filters)).success(function(data) {
          deferred.resolve(data);                     
       }).error(function(){
          deferred.reject();
       });

       return deferred.promise;
     }     
   }
});

OperatorListApp.directive('slider', function() {
    return {
        restrict:'A',
        link:function(scope,element,attrs){
           element.slider({
                range: false,
                min: 0,
                max: 300,
                step: 10,
                value: 10,
                slide: function( event, ui ) {                                   
                    scope.$apply(function(){
                        scope.filters.distance = ui.value + 'km';
                    });
                }
            });
        }
    };
});

OperatorListApp.directive('rating', function(){
    return {
        restrict:'A',
        scope: {
            score: '@'
        },
        link: function(scope,element,attrs)
        {
            scope.$watch("score", function () {
                if (scope.score != 'undefined')
                {
                    element.raty({
                            path: '/bundles/applicationsonataadmin/img',        
                            score: function() {
                                console.log(scope.score);
                                return scope.score;
                            }
                          });
                }
            });            

        }
    }
});

OperatorListApp.directive('buttonsRadio', function() {
        return {
            restrict: 'E',
            scope: { model: '=', options:'=', classes:'@'},
            controller: function($scope){
                $scope.activate = function(option){
                    $scope.model = option;
                };      
            },
            template: "<button type='button' class='btn {[{classes}]}' "+
                        "ng-class='{active: option == model}'"+
                        "ng-repeat='option in options' "+
                        "ng-click='activate(option)'>{[{option}]} "+
                      "</button>"
        };
    });


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
    ;

    
function AppController($scope, OperatorService, DestinationService)
{
    $scope.filters = {
        destination: 1,
        distance: '10km',
        hotel: true,
        motel: false,
        bnb: false,
        camp: false,
        hostel: false
    }    
    
    $scope.UIViewOptions = ['List','Map'];
    $scope.UIView = 'List';
    
    $scope.operatorViewOptions = ['Accommodation','Events', 'Attractions'];
    $scope.operatorView = 'Accommodation';    
        
    DestinationService.get().then(function(data){
        $scope.destinations = data;
    });
    
    $scope.update = function(){
        console.log('updating');
        OperatorService.getAccommodation($scope.filters).then(function(data){                        
            $scope.accommodation = data;
        });            
        OperatorService.getEvents($scope.filters).then(function(data){                        
            $scope.events = data;
        });
        OperatorService.getAttractions($scope.filters).then(function(data){                        
            $scope.attractions = data;
        });        
    }
    
    
    $scope.$watch('filters', function(){
        console.log('filtering');
        $scope.update();
    }, true);
    
}

function queryString(filters)
{
    var str = '?';
    for(var key in filters)
    {
        str += key+'='+filters[key]+'&';
    }
    return str;
}