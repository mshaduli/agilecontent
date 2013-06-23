'use strict';

var OperatorApp = angular.module('OperatorApp', ['OperatorApp.filters']);

OperatorApp.config(function($interpolateProvider) {
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}');
}
);


OperatorApp.directive('resultsGrid', function() {
    return {
        restrict: 'E',
        scope: {
            loading: '=',
            operators: '=',
            sort: '='
        },
        controller: function($scope) {
            var pagesShown = 1;
            var pageSize = 9;
            $scope.itemsLimit = function() {
                return pageSize * pagesShown;
            };
            $scope.showMoreItems = function() {
                pagesShown = pagesShown + 1;
            };

            $scope.getRoomRateForDate = function(room, date) {

                if (typeof room[date] != "undefined")
                {
                    if (room[date].available == "true")
                        return '$' + room[date].rate;
                    else
                        return "N/A";
                } else {
                    return '$295';
                }
            }
        },
        template: '' +
                '<div id="loaderG" ng-show="loading">' +
                '<div id="blockG_1" class="loader_blockG"></div>' +
                '<div id="blockG_2" class="loader_blockG"></div>' +
                '<div id="blockG_3" class="loader_blockG"></div>' +
                '</div>' +
                '<table class="table table-bordered table-hover">' +
                '<thead>' +
                '<tr>' +
                '<th></th>' +
                '<th ng-repeat="day in days">{[{day.name}]}</th>' +
                '<th class="control"><i class="icon-angle-left icon-2x"></i></th>' +
                '<th class="control"><i class="icon-angle-right icon-2x"></i></th>' +
                '</tr>' +
                '</thead>' +
                '<tbody>' +
                '<tr ng-repeat="operator in operators | limitTo: itemsLimit()">' +
                '<td>' +
                '<span class="title">{[{operator.room_name}]}</span>' +
                '<div>{[{operator.name}]} ({[{operator.destination}]})<div data-score="4" class="star pull-right"></div></div>' +
                '</td>' +
                '<td ng-repeat="day in days"><span class="price">{[{ getRoomRateForDate(operator, day.date) }]}</span></td>' +
                '<td><a class="btn btn-link btn-off" href="#"><i class="icon-star"></i></a></td>' +
                '<td><a class="btn btn-link btn-success" href="#"><i class="icon-ok"></i></a></td>' +
                '</tr>' +
                '</tbody>' +
                '</table>' +
                '<div>' +
                
                '</div>',
        link: function(scope, element, attrs) {
            var days = [];
            for (var i = 0; i <= attrs.days; i++)
            {
                days.push({name: moment().add(i, 'days').format('ddd D MMM'), date: moment().add(i, 'days').format('YYYY-MM-DD')});
            }
            scope.days = days;
        }
    };
});







angular.module('OperatorApp.filters', []).
        filter('truncate', function() {
    return function(text, length, end) {
        if (isNaN(length))
            length = 10;

        if (end === undefined)
            end = "...";

        if (text.length <= length || text.length - end.length <= length) {
            return text;
        }
        else {
            return String(text).substring(0, length - end.length) + end;
        }

    }
})
        .filter('distance', function() {
    return function(text, length, end) {
        var distance;
        if (text < 1)
        {
            text = text * 1000;
            distance = text + 'm';
        }
        else
            distance = text + 'km';
        return distance;
    }
})
        ;

function OperatorController($scope, $http, $q, $filter, $timeout)
{
    $scope.operatorUrl = '/app_dev.php/operator/rooms';

    $http.get($scope.operatorUrl + "?hashir=kettavan").success(function(data) {
        $scope.loading = false;
        $scope.operators = data;

        var view;
        var viewCount = $scope.OperatorViewOptions.length;
        for (var i = 0; i < length; i++) {
            if ($scope.OperatorViewOptions[i].name == $scope.filters.OperatorView) {
                view = $scope.OperatorViewOptions[i];
                view.count = data.length;
                break;
            }
        }

    }).error(function() {
        console.log('operators not loaded');
    });

}