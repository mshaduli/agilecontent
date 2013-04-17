'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('OperatorListApp.services', [])
.service('operatorService', function ($rootScope, Accommodation, Attraction, Event) {
    var viewScope;
    var accommodation;
    var attractions;
    var events;

    return {
        setViewScope:function(viewScopeRef){
            viewScope = viewScopeRef;
        },
        get: function (type){
            if (type == 'accommodation') return accommodation;
            else if (type == 'attractions') return attractions;
            else if (type == 'events') return events;
            return false;
        },
        filter:function (params) {
            console.log('filtering');
            accommodation = Accommodation.query(params, function(){
                $rootScope.$broadcast('accommodationLoaded');
            });
            attractions = Attraction.query(params, function(){
                $rootScope.$broadcast('attractionsLoaded');
            });
            events = Event.query(params, function(){
                $rootScope.$broadcast('eventsLoaded');                
            });
        }
    };
});
