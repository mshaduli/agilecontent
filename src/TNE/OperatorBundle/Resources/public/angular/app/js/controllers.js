'use strict';


function ListCtrl($scope, operatorService)
{
    operatorService.setViewScope($scope);
    operatorService.filter({});
}

function MapCtrl($scope, operatorService)
{
    operatorService.setViewScope($scope);
    $operatorService.filter({});
}

function FilterCtrl($scope, operatorService)
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
    
    $scope.town = 'Falls Creek';
    
    $scope.change = function()
    {
        operatorService.filter({
            hotel:$scope.type_hotel,
            motel:$scope.type_motel,
            bnb:$scope.type_bnb,
            camp:$scope.type_camp,
            hostel:$scope.type_hostel,
            distance:$scope.distance,
            price:$scope.price,
            town:$scope.town
        });
    }
}
