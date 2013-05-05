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
            operators: '='
        },
        template: ''+
            'Showing {[{ operators.length }]} listings' +
            '<ul class="thumbnails">' +
                '<li class="span3" ng-repeat="operator in operators">' +
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

function SearchController($scope, $http, $q)
{
    $scope.operatorUrl = '/app_dev.php/operators';
    $scope.UIViewOptions = ['List','Map'];
    $scope.UIView = 'List';

    $scope.OperatorViewOptions = ['Accommodation','Events','Attractions'];

    $scope.filters = {
        distance: 10,
        destination: 1,
        price: 300,
        OperatorView: 'Accommodation'
    };

    $scope.destinations = null;
    $scope.operators = [];

    $http.get('/app_dev.php/operators/destinations').success(function(data) {
        $scope.destinations = data;
    }).error(function(){
        console.log('destinations not loaded');
    });

    $scope.$watch('filters', function(){

        console.log($scope.filters.OperatorView);

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