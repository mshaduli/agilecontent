'use strict';

/* Controllers */

//angular.module('OperatorListApp.controllers', []).
//  controller('MyCtrl1', ['$scope', 'operators', function($scope, $http, operators) {      
//    $scope.accomms = operators();
//  }])
//  .controller('Filters',['$scope', 'operators', function($scope, operators){
//    $scope.change = function () {
//        $scope.accomms = operators();
//        console.log('change');
//    }
//  }])
//  .controller('MyCtrl2', [function() {
//
//  }]);


function ListCtrl($scope, Operator)
{
    $scope.accomms = Operator.query({});
}

function MapCtrl($scope, Operator)
{
    $scope.accomms = Operator.query({});
}

function FilterCtrl($scope, Operator)
{    
    $scope.type_hotel = true;
    $scope.type_motel = true;
    $scope.type_bnb = true;
    $scope.type_camp = true;
    $scope.type_hostel = true;
    
    //Implement filters
    $scope.distance = '50km';
    
    //Implement filters
    $scope.price = [ 120, 350 ];
    
    $scope.change = function()
    {
        $scope.accomms = Operator.query({
            hotel:$scope.type_hotel,
            motel:$scope.type_motel,
            bnb:$scope.type_bnb,
            camp:$scope.type_camp,
            hostel:$scope.type_hostel,
            distance:$scope.distance,
            price:$scope.price,
        });
    }
}
