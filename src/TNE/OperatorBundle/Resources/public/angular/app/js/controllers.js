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

function FilterCtrl($scope, operatorService, Destination)
{    
    $scope.type_hotel = true;
    $scope.type_motel = true;
    $scope.type_bnb = true;
    $scope.type_camp = true;
    $scope.type_hostel = true;
    
    //Implement filters
    $scope.distance = '10km';
    
    //Implement filters
    $scope.price = [ 120, 350 ];
        
    // Methods
    $scope.change = function(options)
    {        
        if(typeof options == 'object') $scope.town = options.town;        
        
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
    
    // Controls
    $scope.destinations = Destination.query(function(){
        $scope.town = $scope.destinations[0].id;
    }); 
    
    // Templates
    $scope.destinationsTemplate = '/bundles/tneoperator/angular/app/partials/destinations.html';
    
}
