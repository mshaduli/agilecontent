'use strict';


function ListCtrl($scope, operatorService, $rootScope)
{    
    operatorService.filter({});
    
    $scope.$on('accommodationLoaded', function(){ 
        console.log(operatorService.get('accommodation'));
        $scope.accommodation = operatorService.get('accommodation'); 
    });
    $scope.$on('attractionsLoaded', function(){ 
        console.log(operatorService.get('attractions'));
        $scope.attractions = operatorService.get('attractions'); 
    });
    $scope.$on('eventsLoaded', function(){ 
        console.log(operatorService.get('events'));
        $scope.events = operatorService.get('events'); 
    });
       
}

function MapCtrl($scope, operatorService)
{
    $operatorService.filter({});
    $scope.accommodation = operatorService.get('accommodation');
    $scope.attractions = operatorService.get('attractions');
    $scope.events = operatorService.get('events');    
}

function FilterCtrl($scope, operatorService, Destination, $templateCache)
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
        $templateCache.removeAll();
        
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
