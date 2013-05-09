'use strict';

var SearchApp = angular.module('SearchApp', ['SearchApp.filters']);

SearchApp.config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }
);

SearchApp.directive('buttonsRadio', function() {
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

SearchApp.directive('accommodationTypeFilter', function(){
    return {
      restrict: 'E',
      template: '<li>'+
                 '<label class="checkbox">' +
                '<input type="checkbox" > Hotel ' +
                '</label>' +
                  '<label class="checkbox">' +
                  '<input type="checkbox" > Motel ' +
                  '</label>' +
                '</li>'
    };
});

SearchApp.directive('distanceFilter', function(){
    return {
        restrict: 'E',
        scope: {
            distance: '=',
            destination: '=',
            destinations: '='
        },
        template: '<li>'+
            '{[{ distance }]}km' +
            '<br/><input type="range" min="0" max="100" step="5" ng-model="distance" />' +
            '<br/><label class="radio" ng-repeat="dest in destinations"><input type="radio" ng-model="$parent.destination" value="{[{ dest.id }]}"> {[{ dest.name }]} </label>' +
            '</li>',
        link: function(scope, el, attrs)
        {
        }
    };
});

SearchApp.directive('priceFilter', function(){
    return {
        restrict: 'E',
        scope: {
            price: '='
        },
        template: '<li>'+
            '${[{ price }]}' +
            '<br/><input type="range" min="0" max="1000" step="20" ng-model="price" />' +
            '</li>',
        link: function(scope, el, attrs)
        {

        }
    };
});

SearchApp.directive('resultsList', function(){
    return {
        restrict: 'E',
        scope: {
            operators: '=',
            sort: '='
        },
        template: ''+
            'Showing {[{ operators.length }]} listings<br/>' +
            '<ul class="thumbnails">' +
                '<li class="span3" ng-repeat="operator in operators | orderBy:sort">' +
                    '<div class="thumbnail">' +
                        '<div class="pull-right pull_top" style="padding: 5px;font-weight: bold">{[{ operator.distance | number:2 | distance }]}</div>' +

                        '<img class="opimg" ng-src="{[{operator.image}]}"/>' +

                        '<b>{[{operator.name}]}</b>' +

                        '<div class="truncate">{[{operator.description | truncate:120 }]}</div>' +

                        '<div class="star pull-right" score="{[{ operator.rating }]}" rating></div>' +

                        '<hr/>' +
                        '<div style="padding: 5px">' +
                            '<div class="pull-left">1 NIGHT FROM</div>' +
                            '<div class="pull-right"><b>${[{ operator.min_rate }]}</b></div>' +
                        '</div>' +
                    '</div>' +
                '</li>' +
            '</ul>',
        link: function(scope, el, attrs)
        {
        }
    };
});

SearchApp.directive('rating', function(){
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
                        readOnly: true,
                        score: function() {
                            return scope.score;
                        }
                    });
                }
            });

        }
    }
});

SearchApp.directive('ratingFilter', function($timeout){
    return {
        restrict:'E',
        scope: {
            score: '@',
            opRating: '='
        },
        template: '<div id="rating-filter" class="star"></div>',
        link: function(scope,element,attrs)
        {
            scope.$watch("score", function () {
                if (scope.score != 'undefined')
                {
                    element.raty({
                        path: '/bundles/applicationsonataadmin/img',
                        score: function() {
                            return scope.score;
                        },
                        click: function(score, evt) {
                            //console.log('ID: ' + $(this).attr('id') + "\nscore: " + score + "\nevent: " + evt);
                            $timeout(function(){
                                scope.$apply(function (){
                                    scope.opRating = score;
                                });
                            });
                        }
                    });
                }
            });

        }
    }
});

SearchApp.directive('resultsMap', function(){
    return {
        restrict: 'A',
        scope: {
            center: '=',
            zoom: '=',
            operators: '=',
            map: '='
        },
        template:'<div>Showing {[{ operators.length }]} listings</div><div id="mapdiv" class="map-canvas"></div><div id="markerdetail"></div>',
        link: function(scope, el, attrs)
        {
            var markers = [];
            var centerPoint = new google.maps.LatLng(scope.center.lat, scope.center.lng);

            scope.map = new google.maps.Map(el.get(0).children[1], {
                zoom: scope.zoom,
                center: centerPoint,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                },
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });

            scope.mgr = new MarkerManager(scope.map);

            scope.$watch('center', function(){
                centerPoint = new google.maps.LatLng(scope.center.lat, scope.center.lng);
                scope.map.setCenter(centerPoint);
            });

            scope.$watch('operators', function(){
                scope.mgr.clearMarkers();
                markers = [];
                angular.forEach(scope.operators, function(operator){
                    markers.push(createMarker(operator));
                });
                if(markers.length > 0)
                {
                    scope.mgr.addMarkers(markers, 0, 17);
                    scope.mgr.refresh();
                }
            });
        }
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

function SearchController($scope, $http, $q, $filter, $timeout)
{
    $scope.operatorUrl = '/app_dev.php/operators';
    $scope.UIViewOptions = ['List','Map'];
    $scope.UIView = 'List';

    $scope.map = null;

    $scope.sort = 'distance';

    $scope.OperatorViewOptions = ['Accommodation','Events','Attractions'];

    $scope.filters = {
        distance: 10,
        destination: 1,
        price: 300,
        OperatorView: 'Accommodation',
        rating: 0
    };

    $scope.mapOptions = {
        zoom: 13,
        center: {lat: "-36.3592910",
                 lng: "146.6872660"}
    };

    $scope.destinations = [];
    $scope.operators = [];

    $scope.isMapElementHidden = false;

    $http.get('/app_dev.php/operators/destinations').success(function(data) {
        $scope.destinations = data;
    }).error(function(){
        console.log('destinations not loaded');
    });

    $scope.$watch('UIView', function(newValue, oldValue){
        if(newValue == 'Map'){
            //console.log($scope.map);

            $timeout(function(){
                $scope.$apply(function(){
                    google.maps.event.trigger($scope.map, 'resize');
                    $scope.map.panTo(new google.maps.LatLng($scope.mapOptions.center.lat, $scope.mapOptions.center.lng));
                });
            });
        }
    });

    $scope.$watch('filters', function(){

        if($scope.filters.OperatorView == 'Accommodation')
        {
            $scope.operatorUrl = '/app_dev.php/operators';
        }
        else if ($scope.filters.OperatorView == 'Events')
        {
            $scope.operatorUrl = '/app_dev.php/operators/events';
        }
        else if ($scope.filters.OperatorView == 'Attractions')
        {
            $scope.operatorUrl = '/app_dev.php/operators/attractions';
        }

        if($scope.destinations.length > 0)
        {
            var selDest = $filter('filter')($scope.destinations, function(dest){
                if(dest.id == $scope.filters.destination){
                    return dest;
                }
            });

            $scope.mapOptions.center = {lat:selDest[0].latitude, lng: selDest[0].longitude};
        }


        $http.get($scope.operatorUrl+queryString($scope.filters)).success(function(data) {
            $scope.operators = data;
        }).error(function(){
            console.log('operators not loaded');
        });
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

function createMarker(operator) {
    var marker = new google.maps.Marker({position: new google.maps.LatLng(operator.latitude, operator.longitude)});
    google.maps.event.addListener(marker, "click", function() {
        $('#markerdetail').show();
        $('#markerdetail').html(
            '<div style="float: left; clear: right; width:250px">' +
                '<img src="' + operator.image + '" class="opimg" />' +
            '</div>' +
            '<div style="float: left; padding: 10px; width:250px">' +
                '<h4>' + operator.name + '</h4>' +
                '<p>' + String(operator.description).substring(0, 60) + '...</p>' +
                '<div class="star" data-score="' + operator.rating + '"></div>' +
            '</div>'
        );
        $(".star").raty({
            path: '/bundles/applicationsonataadmin/img',
            score: function() {
                return $(this).attr('data-score');
            }
        });
    });
    return marker;
}