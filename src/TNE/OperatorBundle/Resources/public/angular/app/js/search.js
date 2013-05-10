'use strict';

var SearchApp = angular.module('SearchApp', ['SearchApp.filters']);

SearchApp.config(function($interpolateProvider){
        $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
    }
);

SearchApp.directive('slider', function($timeout) {
    return {
        restrict: 'A',
        scope: { model: '=' },
        link: function(scope, element, attrs, model) {

            var appendVal = attrs.append || '';
            var prependVal = attrs.prepend || '';
            var updateTimeout;
            var $element = $(element);
            var slider = $element.slider({
                orientation: attrs.orientation || 'horizontal',
                min:   parseFloat(attrs.min || 0),
                max:   parseFloat(attrs.max || 200),
                step:  parseFloat(attrs.step || 10),
                range: false,
                value: scope.model,
                stop: function(event, ui) {
                    scope.$apply(function() {

                        // Callback so update is not called too quickly
                        $timeout.cancel(updateTimeout);
                        updateTimeout = $timeout(function() {
                            scope.model = ui.value;
                        }, 800);
                    });
                },
                slide: function(event, ui) {
                    $element.find('a').html(prependVal+ui.value+appendVal);
                },
                create: function(event, ui) {
                    $element.find('a').html(prependVal+scope.model+appendVal);
                }
            });

            scope.$watch('model', function(value) {
                slider.slider('value', value);
            });
        }
    };
});

SearchApp.directive('checkbox', function() {
    return {
        restrict: 'E',
        scope: { model: '=', value:'@', label:'@', checked:'@'},
        controller: function($scope){
            $scope.toggle = function(){
                $scope.checked = !$scope.checked;
                ($scope.checked) ? $scope.model = $scope.value : $scope.value = false;
            };
        },
        template: '<label class="checkbox">'+
                    '<div class="clearfix labelright blue ">' +
                        '<input type="checkbox" style="display: none;" ng-checked="checked" value="{[{value}]}">' +
                        '<a ng-class="{checked: checked == true}" ng-click="toggle()"></a>' +
                    '</div><a ng-click="toggle()">{[{label}]}</a>'+
                    '</label>',
        link: function(scope, element, attrs) {
            scope.$watch('model', function(newValue, oldValue) {
                scope.checked = (newValue == scope.value);
            });
        }
    };
});

SearchApp.directive('radio', function() {
    return {
        restrict: 'E',
        scope: { model: '=', value:'@', label:'@', checked:'@'},
        controller: function($scope){
            $scope.toggle = function(){
                // Don't allow radio to be unchecked here.
                console.log('toggle:'+$scope.checked);
                if(!$scope.checked) {
                    $scope.checked = true;
                    $scope.model = $scope.value;
                }
            };
        },
        template: '<label class="radio" for="radio-{[{value}]}">'+
            '<div class="clearfix prettyradio labelright blue ">' +
                '<input id="radio-{[{value}]}" type="radio" style="display: none;" ng-checked="checked" value="{[{value}]}">' +
                '<a ng-class="{checked: checked == true}" ng-click="toggle()"></a>' +
            '</div><a ng-click="toggle()">{[{label}]}</a>'+
            '</label>',
        link: function(scope, element, attrs) {
            scope.$watch('model', function(newValue, oldValue) {
                scope.checked = (newValue == scope.value);
            });
        }
    };
});

SearchApp.directive('tabs', function() {
    return {
        restrict: 'E',
        scope: { model: '=', options:'=', classes:'@', count:'@'},
        controller: function($scope){
            $scope.activate = function(option){
                $scope.model = option;
            };
        },
        template: '<a class="btn {[{classes}]}" '+
                    'ng-class="{active: option == model}"'+
                    'ng-repeat="option in options" '+
                    'ng-click="activate(option.name)">{[{option.name}]} <span class="inner hidden-tablet">{[{option.count}]}</span>'+
                  '</a>'
    };
});

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
                    "ng-click='activate(option)'><i class='icon-{[{option|lowercase}]} icon-large'></i>"+
                  "</button>"
    };
});


SearchApp.directive('accommodationTypeFilter', function(){
    return {
        restrict: 'E',
        template: '<li class="nav-row">'+
                    '<checkbox label="Hotel" checked="{[{ false }]}" ></checkbox>' +
                   '</li>' +
                   '<li class="nav-row">' +
                    '<checkbox label="Motel" checked="{[{ true }]}" ></checkbox>' +
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
        template: '<li class="nav-header">'+
                    '<div slider min="0" max="200" step="5" model="distance" class="slider" append="km"></div>'+
                    '</li>' +
                    '<li class="nav-row" ng-repeat="dest in destinations">' +
                        '<radio label="{[{ dest.name }]}" value="{[{ dest.id }]}" checked="{[{ true }]}" model="$parent.destination"></radio>' +
                    '</li>'
    };
});


SearchApp.directive('priceFilter', function(){
    return {
        restrict: 'E',
        scope: {
            price: '=',
            symbol: '='
        },
        template: '<li class="nav-header">'+
            '<div slider min="0" max="1000" model="price" step="20" prepend="$"></div>' +
            '</li>'
    };
});

SearchApp.directive('resultsList', function(){
    //Showing {[{ operators.length }]} listings<br/>
    return {
        restrict: 'E',
        scope: {
            operators: '='
        },
        template: ''+
            '<ul class="cards">' +
                '<li ng-repeat="operator in operators">' +
                    '<div class="card">' +
                        '<div class="title">{[{operator.name}]}</div>' +
                        '<div class="thumbnail">' +
                            '<div class="info-bar">' +
                                '<div class="info-content clearfix">' +
                                '<div class="pull-left"><img src="/bundles/tneoperator/img/design-tripadvisor.png" width="99" height="17" /></div>' +
                                '<div score="{[{ operator.rating }]}" class="star pull-right" rating></div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="tag tag-special"><i class="icon-heart"></i> Special</div>' +
                        '<div class="thumbnail-inner"><img src="http://regional.tne.applicationstaging.com/uploads/media/default/0001/01/thumb_91_default_big.jpeg" /></div>' +
                        '</div>' +
                        '<div class="content">' +
                            '<div class="content-group"><span class="label-important">1</span> Night from <span class="price pull-right label-important">${[{ operator.min_rate }]}</span></div>' +
                            '<div class="divider"></div>' +
                            '<div class="content-group clearfix">' +
                                '<span>{[{operator.description | truncate:50}]}</span> <span class="pull-right"><i class="icon-shock"></i></span>' +
                                '<span class="price pull-right">{[{ operator.distance | number:2 | distance }]}</span>' +
                            '</div>' +
                            '<div>' +
                                '<a href="#" class="btn btn-wishlist"><i class="icon-star icon-white"></i></a>' +
                                '<a href="#" class="btn btn-primary">More</a>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</li>' +
            '</ul>' +
            '<div class="visible-phone">' +
            '<a class="btn btn-full" href="#">Show me more</a>' +
            '</div>',
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
                        path: '/bundles/tneoperator/img',
                        score: function() {
                            return scope.score;
                        }
                    });
                }
            });

        }
    }
});

SearchApp.directive('resultsMap', function($filter){
    return {
        restrict: 'A',
        scope: {
            center: '=',
            zoom: '=',
            operators: '=',
            map: '='
        },
        template:'<div style="display: none;">Showing {[{ operators.length }]} listings</div><div id="mapdiv" class="map-canvas"></div><div id="markerdetail"></div>',
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
                    markers.push(createMarker(operator, $filter));
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
                else distance =text+'km';
                return distance;
            }
        })    
    ;

function SearchController($scope, $http, $q, $filter, $timeout)
{
    $scope.operatorUrl = '/app_dev.php/operators';
    $scope.UIViewOptions = ['List','Calendar','Map'];
    $scope.UIView = 'List';

    $scope.map = null;

    $scope.currency = '$';

    $scope.OperatorViewOptions = [{name:'Accommodation',count:0},{name:'Events',count:0},{name:'Attractions',count:0}];

    $scope.filters = {
        distance: 10,
        destination: 1,
        price: 300,
        OperatorView: 'Accommodation'
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

            var view;
            var viewCount = $scope.OperatorViewOptions.length;
            for (var i = 0; i < length; i++) {
                if($scope.OperatorViewOptions[i].name == $scope.filters.OperatorView) {
                    view = $scope.OperatorViewOptions[i];
                    view.count = data.length;
                    break;
                }
            }

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

function createMarker(operator, $filter) {
    var markerIcon = new google.maps.MarkerImage('/bundles/tneoperator/img/map/marker.png',
        new google.maps.Size(34, 46),
        new google.maps.Point(0,0),
        new google.maps.Point(16, 46));
    var marker = new google.maps.Marker({position: new google.maps.LatLng(operator.latitude, operator.longitude), icon:markerIcon});
    google.maps.event.addListener(marker, "click", function() {

        var operatorRate = operator.min_rate || '0';
        var operatorDesc = $filter('truncate')((operator.description || ''), 50);
        var operatorDistance = $filter('distance')($filter('number')(operator.distance, 2));
        $('#markerdetail').show();
        $('#markerdetail').html(
            '<div class="card">' +
                '<button type="button" class="close" data-dismiss="alert">×</button>' +
                '<div class="title">' + operator.name + '</div>' +
                '<div class="content-top">' +
                    '<div class="content-group"><span class="label-important">2</span> Nights from <span class="price pull-right label-important">$'+ operatorRate +'</span></div>' +
                '</div>' +
                '<div class="thumbnail">' +
                    '<div class="info-bar">' +
                        '<div class="info-content clearfix">' +
                        '<div class="pull-left"><img src="/bundles/tneoperator/img/design-tripadvisor.png" width="99" height="17" /></div>' +
                        '<div data-score="' + operator.rating + '" class="star pull-right"></div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="tag tag-special"><i class="icon-heart"></i> Special</div>' +
                    '<div class="thumbnail-inner"><img ng-src="http://regional.tne.applicationstaging.com/uploads/media/default/0001/01/thumb_91_default_big.jpeg" src="http://regional.tne.applicationstaging.com/uploads/media/default/0001/01/thumb_91_default_big.jpeg"></div>' +
                '</div>' +
                '<div class="content">' +
                    '<div class="content-group clearfix">' +
                        '<span>'+ operatorDesc +'</span> <span class="pull-right"><i class="icon-shock"></i></span>' +
                        '<span class="price pull-right">'+ operatorDistance +'</span>' +
                    '</div>' +
                    '<div>' +
                        '<a href="#" class="btn btn-wishlist"><i class="icon-star icon-white"></i></a>' +
                        '<a href="#" class="btn btn-primary">More</a>' +
                    '</div>' +
                 '</div>' +
            '</div>'
        );
        $(".star").raty({
            path: '/bundles/tneoperator/img',
            score: function() {
                return $(this).attr('data-score');
            }
        });
    });
    return marker;
}