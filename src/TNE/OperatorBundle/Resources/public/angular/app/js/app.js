'use strict';

var OperatorListApp = angular.module('OperatorListApp', ['OperatorListApp.filters', 'google-maps']);

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

OperatorListApp.directive('distanceSlider', function($parse) {
    return {
        restrict:'A',
        link:function(scope,element,attrs){
           var ngModel = $parse(attrs.ngModel);
           element.slider({
                range: false,
                min: 0,
                max: 300,
                step: 10,
                value: 10,
                slide: function( event, ui ) {                                   
                    scope.$apply(function(scope){
                        ngModel.assign(scope, ui.value + 'km');
                    });
                }
            });
        }
    };
});


//OperatorListApp.directive('googleMap', function(){
//    return {
//      restrict:'A',      
//      template: '',
//      link: function(scope, element, attrs){
//            
//            scope.$watch("map", function (newValue, oldValue) {
//                if(newValue.center != null){ 
//                    scopeEl = new google.maps.Map(element.get(0), scope);
//                    //scopeEl.setCenter(newValue.center);
//            }}, true);  
//        
//            scope.$watch("markers", function(newValue, oldValue){
//                angular.forEach(newValue, function (v, i) {
//            });
//            
//            }, true);
//        
//        
//            scope.$watch("UIView", function(newValue, oldValue){
//                if(newValue == 'Map'){                    
//                    google.maps.event.trigger(scopeEl, "resize");                    
//                }
//            });
//      }
//    };
//});


OperatorListApp.directive('priceSlider', function($parse) {
    return {
        restrict:'A',
        link:function(scope,element,attrs){
           var ngModel = $parse(attrs.ngModel);
           element.slider({
                range: false,
                min: 0,
                max: 1000,
                step: 25,
                value: 300,
                slide: function( event, ui ) {                                   
                    scope.$apply(function(scope){
                        ngModel.assign(scope, '$'+ui.value);
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
    .filter('distance', function () {
            return function (text, length, end) {
                var distance;
                if (text < 1)
                {
                    text = text*1000;
                    distance = text+'m';
                }
                else distance = text+'km';
                return distance;
            }
        })    
    ;

    
function AppController($scope, OperatorService, DestinationService, $filter)
{
    $scope.filters = {
        destination: 1,
        distance: '10km',
        price: '$300',
        hotel: true,
        motel: false,
        bnb: false,
        camp: false,
        hostel: false
    }    
    
    $scope.isMapElementHidden = true;
        


    $scope.center = {lat: "-36.3592910", lng: "146.6872660"};

    $scope.zoom = 13;

    $scope.markers = [];
        
    $scope.UIViewOptions = ['List','Map'];
    $scope.UIView = 'List';
    
    $scope.operatorViewOptions = ['Accommodation','Events', 'Attractions'];
    $scope.operatorView = 'Accommodation';    
        
    DestinationService.get().then(function(data){
        $scope.destinations = data;
        var defaultDest = $filter('filter')(data, function(dest){
            if(dest.id == $scope.filters.destination){
             return dest;   
            }
        });
        $scope.center = {lat:defaultDest[0].latitude,lng:defaultDest[0].longitude};        
    });
        
    
    $scope.update = function(element, type){
        
        OperatorService.getAccommodation($scope.filters).then(function(data){                        
            $scope.accommodation = data;
        });            
        OperatorService.getEvents($scope.filters).then(function(data){                        
            $scope.events = data;
        });
        OperatorService.getAttractions($scope.filters).then(function(data){                        
            $scope.attractions = data;
        });
        
        if(type=='destination')
        {
            $scope.center = {lat:element.destination.latitude,lng:element.destination.longitude};
            
            console.log($scope.center);
        }
    }
    
    $scope.$watch("UIView", function(newValue, oldValue){
        if(newValue == 'Map'){                    
            $scope.isMapElementHidden = false;                    
        }
    });
    
    $scope.$watch('filters', function(){
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