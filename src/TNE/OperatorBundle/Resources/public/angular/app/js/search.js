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

            // UI-slider-container used as offset to fix jquery ui slider handle pos
            var $element = $(element);
            $element.wrap('<div class="ui-slider-container" />');
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
        scope: { model: '=', options:'=', classes:'@', count:'@', operators:'=', toggleCalendar:'&'},
        controller: function($scope){
            $scope.activate = function(option){
                $scope.model = option;
                $scope.toggleCalendar({selectedOption:option.name});
            };
        },
        template: '<a class="btn {[{classes}]}" '+
                    'ng-class="{active: option == model}"'+
                    'ng-repeat="option in options" '+
                    'ng-click="activate(option)">{[{option.name}]} <span class="inner hidden-tablet">{[{ $parent.operators.length }]}</span>'+
                  '</a>',
        link: function(scope, element, attrs) {

        }
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

SearchApp.directive('distanceFilter', function(){
    return {
        restrict: 'E',
        scope: {
            distance: '=',
            destination: '=',
            destinations: '=',
            limit: '@'
        },
        controller: function($scope){
            $scope.more = function(){
                $scope.limit = $scope.destinations.length;
            };
        },
        template: '<li class="nav-item-slider">'+
                    '<div slider min="0" max="200" step="5" model="distance" class="slider" append="km"></div>'+
                    '</li>' +
                    '<li class="nav-row" ng-repeat="dest in destinations | limitTo: limit">' +
                        '<radio label="{[{ dest.name }]}" value="{[{ dest.id }]}" checked="{[{ true }]}" model="$parent.destination"></radio>' +
                    '</li>' +
                    '<li class="clearfix" ng-show="limit < destinations.length"><a class="btn btn-link muted pull-right" ng-click="more()">See more</a></li>'
    };
});

SearchApp.directive('switch', function() {
    return {
        restrict: 'A',
        scope: {
            model: '=', value:'@', checked:'@', toggleHandler: '&'
        },
        controller: function($scope){
            $scope.toggle = function(){
                $scope.checked = !$scope.checked;
                $scope.toggleHandler({value: $scope.value, checked: $scope.checked});
            };
        },
        template:'<div class="clearfix blue">' +
            '<input type="checkbox" style="display: none;" ng-checked="checked" value="{[{value}]}">' +
            '<a ng-class="{checked: checked == true}" ng-click="toggle()"></a>' +
            '</div>',
        link: function(scope, element, attrs) {
            scope.$watch('model', function(newValue, oldValue) {
                scope.checked = (newValue == scope.value);
            });
        }
    };
});

SearchApp.directive('checkbox', function() {
    return {
        restrict: 'E',
        scope: {
            model: '=', value:'@', label:'@', checked:'@', toggleHandler: '&'
        },
        controller: function($scope){
            $scope.toggle = function(){
                $scope.checked = !$scope.checked;
                $scope.toggleHandler({value: $scope.value, checked: $scope.checked});
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

SearchApp.directive('classificationsFilter', function(){
    return {
        restrict: 'E',
        scope: {
            classifications: '=',
            limit: '@',
            toggle: '&'
        },
        controller: function($scope){
            $scope.more = function(){
                $scope.limit = $scope.classifications.length;
            };
        },
        template:
            '<li class="nav-row" ng-repeat="classification in classifications | limitTo: limit">'+
                '<checkbox label="{[{ classification.name }]}" checked="{[{ true }]}" value="{[{ classification.keyStr }]}" model="classification.keyStr" toggle-handler="toggle({value:value, checked:checked})"></checkbox>' +
            '</li>' +
            '<li class="clearfix" ng-show="limit < classifications.length"><a class="btn btn-link muted pull-right" ng-click="more()">See more</a></li>',
        link: function(scope, el, attrs)
        {

        }
    };
});

SearchApp.directive('priceFilter', function(){
    return {
        restrict: 'E',
        scope: {
            price: '=',
            symbol: '='
        },
        template: '<li class="nav-item-slider">'+
            '<div slider min="0" max="1000" model="price" step="20" prepend="$"></div>' +
            '</li>'
    };
});

SearchApp.directive('resultsList', function(){
    //
    return {
        restrict: 'E',
        scope: {
            loading: '=',
            operators: '=',
            sort: '='
        },
        controller: function($scope){
            var pagesShown = 1;
            var pageSize = 9;
            $scope.itemsLimit = function(){
                return pageSize * pagesShown;
            };
            $scope.showMoreItems = function() {
                pagesShown = pagesShown + 1;
            };
        },
        template: '' +
            '<div id="loaderG" ng-show="loading">' +
            '<div id="blockG_1" class="loader_blockG"></div>' +
            '<div id="blockG_2" class="loader_blockG"></div>' +
            '<div id="blockG_3" class="loader_blockG"></div>' +
            '</div>'+
            '<ul class="cards">' +
            '<li ng-repeat="operator in operators | limitTo: itemsLimit()">' +
            '<div class="card">' +
            
            '<div class="thumbnail">' +
            '<div class="thumbnail-inner"><img ng-src="{[{operator.image}]}" /></div>' +
            '<div class="title">{[{operator.name}]}</div>' +
            '<div class="info-bar">' +
            '<div class="info-content clearfix">' +
            '<div class="pull-left"><img src="/bundles/tneoperator/img/design-tripadvisor.png" width="99" height="17" /></div>' +
            '<div score="{[{ operator.rating }]}" class="star pull-right" rating></div>' +
            '</div>' +
            '</div>' +
//                            '<div class="tag tag-special"><i class="icon-heart"></i> Special</div>' +
            '</div>' +
            '<div class="content">' +
            '<div class="divider"></div>' +
            '<div class="content-group"> From <span class="price pull-right label-important">${[{ operator.min_rate }]}</span></div>' +
            '<div class="divider"></div>' +
            '<div class="content-group clearfix">' +
            '<div class="pull-right distance"> <div>{[{ operator.distance | number:2 | distance }]}</div></div>' +
            '<span>{[{operator.destination}]}<br/> {[{operator.type}]}</span>' +
            '</div>' +
            '<div>' +
            '<a href="#" class="btn btn-wishlist"><i class="icon-star icon-white"></i></a>' +
            '<a href="/app_dev.php/operators/{[{operator.id}]}" class="btn btn-primary">ADD TO PLANNER</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</li>' +
            '</ul>' +
            '<div>' +
            '<br/><button class="btn-full" ng-click="showMoreItems()">Show me more</button>' +
            '</div>'
    };
});

SearchApp.directive('resultsGrid', function(){
    return {
        restrict: 'E',
        scope: {
            loading: '=',
            operators: '=',
            currentView: '=',
            sort: '=',
            dates: '='
        },
        controller: function($scope, $http){
            var pagesShown = 1;
            var pageSize = 9;
            $scope.itemsLimit = function(){
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

            $scope.isAccommodation = function(){
                return $scope.currentView.name == 'Accommodation';
            }

        },
        template: '' +
            '<div id="loaderG" ng-show="loading">' +
                '<div id="blockG_1" class="loader_blockG"></div>' +
                '<div id="blockG_2" class="loader_blockG"></div>' +
                '<div id="blockG_3" class="loader_blockG"></div>' +
            '</div>'+
            '<table class="table table-bordered table-hover" ng-show="!loading && isAccommodation()">' +
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
//                        '<td><a class="btn btn-link btn-off" href="#"><i class="icon-star"></i></a></td>' +
                        '<td colspan="2"><a class="btn btn-link {[{ cartIcon(operator) }]}" href="#" ng-click="addToCart(operator)"><i class="icon-shopping-cart"></i> ADD</a></td>' +
                    '</tr>' +
                '</tbody>' +

            '</table>' +
            '<div style="text-align: center; margin-top: 100px" ng-show="!loading && !isAccommodation()">No Results</div>' +
            '<div>' +
                '<br/><button class="btn btn-full" ng-click="showMoreItems()" ng-show="!loading && isAccommodation()">Show me more</button>' +
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

SearchApp.directive('rating', function(){
    return {
        restrict:'A',
        scope: {
            score: '@',
            self: '@'
        },
        link: function(scope,element,attrs)
        {
            scope.$watch("score", function () {
                if (scope.score != 'undefined')
                {
                    var imgPath = ($(element).attr('self') == 'true') ? '/bundles/tneoperator/img/self' : '/bundles/tneoperator/img';
                    element.raty({
                        path: imgPath,
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
        template: '<li class="nav-item-rating">Min. rating <div id="rating-filter"></div></li>',
        link: function(scope,element,attrs)
        {
            scope.$watch("score", function () {
                if (scope.score != 'undefined')
                {
                    $(element).find('#rating-filter').raty({
                        path: '/bundles/tneoperator/img/filter',
                        cancel: true,
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

SearchApp.directive('topFilters', function($timeout){
    return {
        restrict: 'E',
        scope: {
            filterByDates: '&',
            dates: '='
        },
        template:
            '<div id="search-tag" class="pull-left visible-desktop">I\'m looking for</div>' +
                '<div id="search-option" class="pull-right">' +
                    '<ul id="search-filter" class="nav nav-collapse-phone collapse">' +
                        '<li>' +
                            '<select id="filterAccomm">' +
                                '<option value="A">Accommodation</option>' +
                            '</select>' +
                        '</li>' +
                        '<li>' +
                            '<select id="filterOccupants">' +
                                '<option value="A">2 Adults</option>' +
                            '</select>' +
                        '</li>' +
                        '<li>' +
                            '<div class="input-append">' +
                                '<input class="span2" id="filterDate" type="text" ng-model="dates">' +
                                '<button id="filterDateBtn" class="btn" type="button"><i class="icon-reorder"></i></button>' +
                            '</div>' +
                        '</li>' +
                    '</ul>' +
                    '<a href="#" class="btn btn-link btn-search pull-right hidden-phone" ng-click="filterByDates({dates:dates})"><i class="icon-repeat"></i></a>' +
                '</div>' +
            '</div>',
        controller: function($scope){
            $scope.filterByDates = function(){
                $timeout(function(){
                    $scope.$apply(function(){
                        $scope.dates = $scope.datesStr;
                    });
                });
            }
        },
        link: function(scope, element, attrs)
        {
            $(element.find('#filterDate')).daterangepicker(
                {
                    opens: 'left',
                    format: 'DD/MM/YYYY',
                    separator: ' to ',
                    startDate: new Date(),
                    endDate: moment().add('days', 7)
                }, function(start, end) {
                   scope.datesStr = moment(start).format('DD/MM/YYYY') + ' to ' + moment(end).format('DD/MM/YYYY');
                }
            );
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
        template:'<div style="display:none">Showing {[{ operators.length }]} listings</div><div id="mapdiv" class="map-canvas"></div><div id="markerdetail"></div>',
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
        OperatorView: $scope.OperatorViewOptions[0],
        rating: 0,
        classifications: [],
        dates: moment().format('DD/MM/YYYY') + ' to ' + moment().add('days', 5).format('DD/MM/YYYY'),
        gridView: 'false'
    };

    $scope.mapOptions = {
        zoom: 13,
        center: {lat: "-36.3592910",
                 lng: "146.6872660"}
    };

    $scope.destinations = [];
    $scope.classifications = [];
    $scope.operators = [];

    $scope.isMapElementHidden = false;

    $http.get('/app_dev.php/operators/destinations').success(function(data) {
        $scope.destinations = data;
    }).error(function(){
        console.log('destinations not loaded');
    });

    $http.get('/app_dev.php/operators/classifications').success(function(data) {
        var tempCls = [];
        $scope.classifications = data;
        angular.forEach(data, function(cls){
            tempCls.push(cls.keyStr);
        });
        $scope.filters.classifications = tempCls;
    }).error(function(){
            console.log('destinations not loaded');
        });

    $scope.$watch('UIView', function(newValue, oldValue){
        if(newValue == 'Map'){

            $timeout(function(){
                $scope.$apply(function(){
                    google.maps.event.trigger($scope.map, 'resize');
                    $scope.map.panTo(new google.maps.LatLng($scope.mapOptions.center.lat, $scope.mapOptions.center.lng));
                });
            });
        }
        if(newValue == "Calendar")
        {
                $timeout(function(){
                $scope.$apply(function(){
                    $scope.filters.gridView = 'true';
                });
            });
        }else{
            $timeout(function(){
                $scope.$apply(function(){
                    $scope.filters.gridView = 'false';
                });
            });
        }
    });

    $scope.$watch('filters', function(){

        $scope.operators = [];
        $scope.loading = true;

        if($scope.filters.OperatorView.name == 'Accommodation')
        {
            $scope.operatorUrl = '/app_dev.php/operators';
        }
        else if ($scope.filters.OperatorView.name == 'Events')
        {
            $scope.operatorUrl = '/app_dev.php/operators/events';
        }
        else if ($scope.filters.OperatorView.name == 'Attractions')
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
        $timeout(function(){
            $http.get($scope.operatorUrl+queryString($scope.filters)).success(function(data) {
                $scope.loading = false;
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
        }, 500);


    }, true);

    $scope.toggleClassification = function (value, checked)
    {

        var cIndex = jQuery.inArray(value, $scope.filters.classifications);

        if(checked)
        {
            if(cIndex == -1)
            {
                $scope.filters.classifications.push(value);
            }
        }
        else
        {
            $scope.filters.classifications.splice(cIndex, 1);
        }
    }

    $scope.toggleCalendar = function(selectedOption){
        console.log(selectedOption);
        if(selectedOption != 'Accommodation'){
            $timeout(function(){
                $scope.$apply(function(){
                    $scope.UIViewOptions = ['List','Map'];
                });
            });
        }
        else {
            $timeout(function(){
                $scope.$apply(function(){
                    $scope.UIViewOptions = ['List','Calendar','Map'];
                });
            });
        }
    }

}


function queryString(filters)
{
    var str = '?';
    for(var key in filters)
    {
        if(jQuery.isArray(filters[key]))
        {
            for(var i=0;i<filters[key].length;i++){
                str += key+'[]='+filters[key][i]+'&';
            }
        }
        else
        {
            str += key+'='+filters[key]+'&';
        }
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
                '<button type="button" class="close" data-dismiss="alert"><i class="icon-large icon-remove"></i></button>' +
                
                '<div class="thumbnail">' +
                    '<div class="thumbnail-inner"><img src="'+operator.image+'"></div>' +
                    '<div class="title">' + operator.name + '</div>' +
                    '<div class="info-bar">' +
                        '<div class="info-content clearfix">' +
                        '<div class="pull-left"><img src="/bundles/tneoperator/img/design-tripadvisor.png" width="99" height="17" /></div>' +
                        '<div data-score="' + operator.rating + '" class="star pull-right"></div>' +
                        '</div>' +
                    '</div>' +
//                    '<div class="tag tag-special"><i class="icon-heart"></i> Special</div>' +
                '</div>' +
                '<div class="content">' +
                    '<div class="divider"></div>' +
                    '<div class="content-top">' +
                    '<div class="content-group"><span class="label-important">2</span> Nights from <span class="price pull-right label-important">$'+ operatorRate +'</span></div>' +
                    '</div>' +                    
                    '<div class="divider"></div>' +
                    '<div class="content-group clearfix">' +
                        '<div class="pull-right distance"><i class="icon-bolt"></i> <div>'+ operatorDistance +'</div></div>' +
                        '<span>'+ operator.destination + '<br/>' + operator.type +'</span>' +
                    '</div>' +
                    '<div>' +
                        '<a href="#" class="btn btn-wishlist"><i class="icon-star"></i></a>' +
                        '<a href="/app_dev.php/operators/'+operator.id+'" class="btn btn-primary">ADD TO PLANNER</a>' +
                    '</div>' +
                 '</div>' +
            '</div>'
        );
        $(".star").raty({
            path: '/bundles/tneoperator/img',
            readOnly: true,
            score: function() {
                return $(this).attr('data-score');
            }
        });
    });
    return marker;
}