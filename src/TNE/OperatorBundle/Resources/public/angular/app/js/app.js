'use strict';

function floatEqual (f1, f2) {
  return (Math.abs(f1 - f2) < 0.000001);
}

var OperatorListApp = angular.module('SearchApp', ['SearchApp.filters']);

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

function MarkerModel(){

    var that = this;
    
    that.markers = [];
    that.garbage = [];
    
    that.findMarker = function (lat, lng) {
        for (var i = 0; i < that.markers.length; i++) {
          var pos = that.markers[i].getPosition();
          
          if (floatEqual(pos.lat(), lat) && floatEqual(pos.lng(), lng)) {
            return that.markers[i];
          }
        }        
        return null;
      };  
      
    that.findMarkerIndex = function (lat, lng) {
        for (var i = 0; i < that.markers.length; i++) {
          var pos = that.markers[i].getPosition();
          
          if (floatEqual(pos.lat(), lat) && floatEqual(pos.lng(), lng)) {
            return i;
          }
        }
        
        return -1;
      };      
      
    that.reset = function(){
        for (var i = 0; i < that.markers.length; i++) {
            that.markers[i].setMap(null);
        }
        that.markers = [];
    }
    
    that.removeMarkers = function (markerInstances) {
        
        var s = this;
        
        angular.forEach(markerInstances, function (v, i) {
          var pos = v.getPosition(),
            lat = pos.lat(),
            lng = pos.lng(),
            index = s.findMarkerIndex(lat, lng);
          
          that.markers.splice(index, 1);
          s.markers.splice(index, 1);
          
          v.setMap(null);
        });
      };
    
    that.cleanUp = function(cleanMarkers){        
        angular.forEach(that.markers, function (v, i) {

        var pos = v.getPosition(),
          lat = pos.lat(),
          lng = pos.lng(),
          found = false;

        for (var si = 0; si < cleanMarkers.length; si++) {

          var sm = cleanMarkers[si];

          if (floatEqual(sm.latitude, lat) && floatEqual(sm.longitude, lng)) {
            found = true;
          }
        }

        if (!found) {
          that.garbage.push(v);
        }
      });        
       that.garbage.length && that.removeMarkers(that.garbage);
       //Remove duplicates
    }
};

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

OperatorListApp.directive('googleMap', function($timeout){
     return {
        restrict:'A',
        template: "",
        scope: {
            center: '=',
            zoom: '=',
            view: '=',
            optype: '=',
            markers: '='
        },
        link: function(scope,element,attrs)
        {   
            var myMarkers = new MarkerModel();
            scope.$watch("center", function(){
                if(scope.center != '')
                {
                    myMarkers.reset();
                    if(!angular.isDefined(scope.map))
                    {
                        console.log('creating map');
                        scope.map = new google.maps.Map(element.get(0), {
                                                zoom: scope.zoom,
                                                center: scope.center,
                                                mapTypeControl: false,
                                                scaleControl: false,
                                                streetViewControl: false,
                                                zoomControl: true,
                                                zoomControlOptions: {
                                                  style: google.maps.ZoomControlStyle.SMALL
                                                },        
                                                mapTypeId: google.maps.MapTypeId.ROADMAP                        
                                            });  
                        console.log(scope.center);                                            
                        scope.map.panTo(scope.center); 
                        google.maps.event.trigger(scope.map, "resize");
                    }
                    else{
                        console.log('centering map');
                        $timeout(function(){
                            scope.$apply(function(){
                                console.log(scope.center);
                                scope.map.panTo(scope.center);
                                google.maps.event.trigger(scope.map, "resize");
                            });    
                        });
                    }
                }

            }, true);    
            scope.$watch('markers', function(newValue, oldValue){
                   
                   angular.forEach(scope.markers, function(op){
                       console.log(myMarkers.findMarker(op.latitude,op.longitude));
                       if(myMarkers.findMarker(op.latitude,op.longitude) == null)
                       {
                        var marker = new google.maps.Marker({
                           position: new google.maps.LatLng(op.latitude,op.longitude),
                           map: scope.map,
                           title: op.name,
                           desc: op.description
                         });
                          google.maps.event.addListener(marker, 'click', function() {
                            $('.copt').html(marker.title);
                            $('.copd').html(marker.desc);
                          });
                          myMarkers.markers.push(marker);
                       }
                   });
                   myMarkers.cleanUp(scope.markers);
            }, true);
            scope.$watch('view', function(newValue, oldValue){
                if(scope.center != '') {                                            
                    
                    $timeout(function(){
                        scope.$apply(function(){
                            scope.map.panTo(scope.center);
                            google.maps.event.trigger(scope.map, "resize");
                        });    
                    });
                }
            });
            scope.$watch('optype', function(newValue, oldValue){
                if(scope.center != '') {     
                    $timeout(function(){
                        scope.$apply(function(){
                            scope.map.panTo(scope.center);
                            google.maps.event.trigger(scope.map, "resize");
                        });    
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


angular.module('SearchApp.filters', []).
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
    
    $scope.mapOptions = {
        zoom: 14,
        center: ''
    };
        
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
        $scope.mapOptions.center = new google.maps.LatLng(defaultDest[0].latitude, defaultDest[0].longitude);
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
            $scope.mapOptions.center = new google.maps.LatLng(element.destination.latitude, element.destination.longitude);
        }
    }
    
    
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