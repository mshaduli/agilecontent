'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('OperatorListApp.services', [])
.service('operatorService', function (Accommodation, Attraction, Event) {
    var viewScope;

    return {
        setViewScope:function(viewScopeRef){
            viewScope = viewScopeRef;
        },
        filter:function (params) {
            console.log('filtering');
            viewScope.accommodation = Accommodation.query(params);
            viewScope.attractions = Attraction.query(params);
            viewScope.events = Event.query(params);
        }
    };
});
