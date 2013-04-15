'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('OperatorListApp.services', [])
.service('operatorService', function (Operator) {
    var viewScope;

    return {
        setViewScope:function(viewScopeRef){
            viewScope = viewScopeRef;
        },
        filter:function (params) {
            console.log('filtering');
            viewScope.accomms = Operator.query(params);
        }
    };
});
