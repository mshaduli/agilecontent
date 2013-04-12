'use strict';

/* Controllers */

angular.module('OperatorListApp.controllers', []).
  controller('MyCtrl1', ['$scope', '$http', function($scope, $http) {
    $http.get('operators/list').success(function(data) {
      $scope.accomms = data;
    });
  }])
  .controller('MyCtrl2', [function() {

  }]);