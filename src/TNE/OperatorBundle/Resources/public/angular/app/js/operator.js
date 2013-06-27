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
        controller: function($scope, $http) {
            var pagesShown = 1;
            var pageSize = 9;
            $scope.itemsLimit = function() {
                return pageSize * pagesShown;
            };
            $scope.showMoreItems = function() {
                pagesShown = pagesShown + 1;
            };

            $scope.getRoomRateForDate = function(room, date){

                if(typeof room[date] != "undefined")
                {
                    if(room[date].available == "true")
                        return '$'+room[date].rate;
                    else
                        return "N/A";
                }else{
                    return room.min_rate;
                }
            };

            $scope.classForDate = function(date){
                var dates = $scope.dates.split(' to ');
                var range = moment().range(moment(dates[0], 'DD/MM/YYYY'), moment(dates[1], 'DD/MM/YYYY'));
                var inputDate = moment(date, 'DD/MM/YYYY');
                var dateClass = inputDate.within(range)?'selected':'none';
                return dateClass;
            };

            $scope.cartIcon = function(room){

                var available = true;
                var key = "";
                for(key in room){
                    if(typeof room[key] == "object" && room[key] != 'tags' )
                    {
                        if(room[key].available == "false")
                        {
                            available = false;
                            break;
                        }
                    }
                }
                if(available)
                    return '';
                else
                    return 'hidden';

            };
            $scope.addToCart = function(room){

                var dates = $scope.dates.split(' to ');
                $http.get('/app_dev.php/booking/addToCart?room_id='+room.room_id+"&start_date="+dates[0]+"&end_date="+dates[1])
                    .success(function(data) {
                        alert(data.status);
                        $('div#header-top ul.nav li a').filter(':contains(Cart)').html('Cart ('+data.count+')');
                    }).error(function(){

                    });
            };

        },
        template: '' +
            '<div id="loaderG" ng-show="loading">' +
                '<div id="blockG_1" class="loader_blockG"></div>' +
                '<div id="blockG_2" class="loader_blockG"></div>' +
                '<div id="blockG_3" class="loader_blockG"></div>' +
            '</div>'+
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
                        '<td ng-repeat="day in days" ng-class="day.class"><span class="price">{[{ getRoomRateForDate(operator, day.date) }]}</span></td>' +
                        '<td colspan="2"><a class="btn btn-link {[{ cartIcon(operator) }]}" href="#" ng-click="addToCart(operator)"><i class="icon-shopping-cart"></i> ADD</a></td>' +
                    '</tr>' +
                '</tbody>' +
            '</table>' +
            '<div>' +
            '</div>',
        link: function(scope, element, attrs){
            scope.$watch('dates',function(){
                var days = [];
                for(var i=0;i<=attrs.days;i++)
                {
                    days.push({
                        name:moment().add(i, 'days').format('ddd D MMM'),
                        date:moment().add(i,'days').format('YYYY-MM-DD'),
                        class: scope.classForDate(moment().add(i,'days').format('DD/MM/YYYY')),
                        add_to_cart: scope.cartIcon(moment().add(i,'days').format('DD/MM/YYYY'))
                    });
                }
                scope.days = days;
            }, true);

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
    var idAry = location.href.split('/');
    var id = idAry[idAry.length-1];
    $http.get($scope.operatorUrl + "?id="+id).success(function(data) {
        $scope.loading = false;
        $scope.operators = data;

//        var view;
//        var viewCount = $scope.OperatorViewOptions.length;
//        for (var i = 0; i < length; i++) {
//            if ($scope.OperatorViewOptions[i].name == $scope.filters.OperatorView) {
//                view = $scope.OperatorViewOptions[i];
//                view.count = data.length;
//                break;
//            }
//        }

    }).error(function() {
        console.log('operators not loaded');
    });

}



$(document).ready(function(){
    /**
     * Add to cart functionality for each room in Operator details page
     */
    $("a[id^=a_]").click(function(event){
        var room_id = $(event.currentTarget).attr('id').replace('a_room_','');
        var max_capacity = $('#max_capacity_'+room_id).val();
        var start_date =  $('#start_date_'+room_id).val();
        var end_date =  $('#end_date_'+room_id).val();
        if(new Date(formatDate(start_date)) == 'Invalid Date')
        {
            alert('Invalid Date');
            return;
        }
        if(new Date(formatDate(end_date)) == 'Invalid Date' || new Date(formatDate(end_date)) < new Date(formatDate(start_date)) )
        {
            alert('Invalid Date');
            return;
        }
        $.ajax({
            url: "/app_dev.php/booking/addToCart",
            data: {room_id:room_id,start_date:start_date,end_date:end_date,max_capacity:max_capacity},
            success:function(data){
                alert(data.status);
                $('div#header-top ul.nav li a').filter(':contains(Cart)').html('Cart ('+data.count+')');
            }
        });
    });

    function formatDate(date)
    {
        var dateAry = date.split('/');
        return dateAry[1]+'/'+dateAry[0]+'/'+dateAry[2];
    }
});

