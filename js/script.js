var joustDash = angular.module('joustDash', ['ui.router', 'gridshore.c3js.chart', 'ui.bootstrap', 'ui.grid', 'ui.grid.edit', 'ui.grid.selection', 'ui.grid.infiniteScroll'])

joustDash.config(function($stateProvider){
    $stateProvider
      .state('home', {
        url: "/",
        views: {
          "main": {
            templateUrl: '../pages/home-main.html'
          }
        },
        controller: 'homeCtrl'
      })
      .state('aircraft', {
        url: "/aircraft",
        views: {
          "main": {
            templateUrl: '../pages/aircraft-main.html'
          }
        },
        controller: 'aircraftCtrl'
      })
      .state('craft', {
        url:"/aircraft/:tail_num",
        views: {
          "main": {
            templateUrl: '../pages/craft-main.html'
          }
        },
        controller: 'craftCtrl'
      })
      .state('flights', {
        url:"/flights",
        views: {
          "main": {
            templateUrl: '../pages/flights-main.html'
          }
        },
        controller: 'flightsCtrl'
      })
      .state('flight', {
        url:"/flights/:flight_id",
        views: {
          "main": {
            templateUrl: '../pages/flight-main.html'
          }
        },
        controller: 'flightCtrl'
      })
      .state('engines', {
        url:"/engines",
        views: {
          "main": {
            templateUrl: '../pages/engines-main.html'
          }
        },
        controller: 'enginesCtrl'
      })
      .state('engine', {
        url:"/engines/:esn",
        views: {
          "main": {
            templateUrl: '../pages/engine-main.html'
          }
        },
        controller: 'engineCtrl'
      })
})
