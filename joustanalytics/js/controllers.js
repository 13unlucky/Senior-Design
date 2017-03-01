var joustDash = angular.module('joustDash')

joustDash.controller('mainCtrl', function($scope) {
})

joustDash.controller('aircraftCtrl', ['$scope','$http', '$filter', 'uiGridConstants', '$state', function ($scope, http, filter, uiGridConstants, $state) {

  function rowTemplate() {
    return '<div ng-dblclick="grid.appScope.onDblClickRow(row)" >' +
    '  <div ng-repeat="(colRenderIndex, col) in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ng-class="{ \'ui-grid-row-header-cell\': col.isRowHeader }"  ui-grid-cell></div>' +
    '</div>';
  }

  $scope.aircraftGrid = {
    enableRowSelection: true,
    enableRowHeaderSelection: false,
    enableCellEditOnFocus : false,
    enableFiltering: true,
    rowTemplate: rowTemplate(),
  };

  $scope.aircraftGrid.columnDefs = [
    { name: 'tail_num', headerCellClass: $scope.highlightFilteredHeader  },
    { name: 'airline'},
    { name: 'manufacturer'},
    { name: 'model'},
    { name: 'location'},
    { name: 'last_update'},
    { name: 'status'}
  ]

  $scope.aircraftGrid.multiSelect = false;

  $scope.onDblClickRow = function(rowItem){
    $state.go("craft", {tail_num: rowItem.entity.tail_num})
  }

  http.get("api/aircraft?detail=true").then(function(response){
    $scope.aircraftGrid.data = response.data
  })
}])

joustDash.controller('craftCtrl', ['$scope','$http', '$filter', '$stateParams', '$state', function ($scope, http, filter, $stateParams, $state) {
  var tail_num = $stateParams.tail_num;

  function rowTemplate(type) {
    if (type == "flight"){
      return '<div ng-dblclick="grid.appScope.onDblClickFlight(row)" >' +
      '  <div ng-repeat="(colRenderIndex, col) in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ng-class="{ \'ui-grid-row-header-cell\': col.isRowHeader }"  ui-grid-cell></div>' +
      '</div>';
    } else if (type == "engine"){
      return '<div ng-dblclick="grid.appScope.onDblClickEngine(row)" >' +
      '  <div ng-repeat="(colRenderIndex, col) in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ng-class="{ \'ui-grid-row-header-cell\': col.isRowHeader }"  ui-grid-cell></div>' +
      '</div>';
    }
  }

  $scope.flightGrid = {
    enableRowSelection: true,
    enableRowHeaderSelection: false,
    enableCellEditOnFocus : false,
    enableFiltering: true,
    rowTemplate: rowTemplate('flight'),
  };

  $scope.flightGrid.columnDefs = [
    { name: 'flight_id', headerCellClass: $scope.highlightFilteredHeader  },
    { name: 'start_airport'},
    { name: 'end_airport'},
    { name: 'start_dt'},
    { name: 'end_dt'},
    { name: 'duration'}
  ]

  $scope.engineGrid = {
    enableRowSelection: true,
    enableRowHeaderSelection: false,
    enableCellEditOnFocus : false,
    enableFiltering: false,
    rowTemplate: rowTemplate('engine')
  };

  $scope.engineGrid.columnDefs = [
    { name: 'serial_num', headerCellClass: $scope.highlightFilteredHeader  },
  ]

  $scope.flightGrid.multiSelect = false;

  $scope.onDblClickFlight = function(rowItem, type){
    $state.go("flight", {flight_id: rowItem.entity.flight_id})
  }

  $scope.onDblClickEngine = function(rowItem, type){
    $state.go("engine", {serial_num: rowItem.entity.serial_num})
  }

  http.get("api/aircraft/"+tail_num+"?detail=true").then(function(response){
    $scope.craftData = response.data;
  })
  http.get("api/aircraft/engines/"+tail_num+"?detail=true").then(function(response){
    $scope.engineGrid.data = response.data;
  })
  http.get("api/aircraft/flights/"+tail_num+"?detail=true").then(function(response){
    $scope.flightGrid.data = response.data;
  })
}])

joustDash.controller('flightsCtrl', ['$scope','$http', '$filter', '$state', function ($scope, http, filter, $state) {

  function rowTemplate() {
    return '<div ng-dblclick="grid.appScope.onDblClickRow(row)" >' +
    '  <div ng-repeat="(colRenderIndex, col) in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ng-class="{ \'ui-grid-row-header-cell\': col.isRowHeader }"  ui-grid-cell></div>' +
    '</div>';
  }

  $scope.flightsGrid = {
    enableRowSelection: true,
    enableRowHeaderSelection: false,
    enableCellEditOnFocus : false,
    enableFiltering: true,
    rowTemplate: rowTemplate(),
  };

  $scope.flightsGrid.columnDefs = [
    { name: 'flight_id', headerCellClass: $scope.highlightFilteredHeader  },
    { name: 'Aircraft_tail_num'},
    { name: 'start_airport'},
    { name: 'end_airport'},
    { name: 'start_dt'},
    { name: 'end_dt'}
  ]

  $scope.flightsGrid.multiSelect = false;

  $scope.onDblClickRow = function(rowItem){
    $state.go("flight", {flight_id: rowItem.entity.flight_id})
  }

  http.get("api/flight?detail=true").then(function(response){
    $scope.flightsGrid.data = response.data;
  })
}])

joustDash.controller('flightCtrl', ['$scope','$http', '$filter', '$stateParams', function (scope, http, filter, stateParams) {
  var flight_id = stateParams.flight_id;
  http.get("api/flight/detail/"+flight_id).then(function(response){
    scope.flightData = response.data;
  })
  http.get("api/flight/graph/"+flight_id).then(function(response){
    scope.flightGraph = response.data;
  })
}])

joustDash.controller('enginesCtrl', ['$scope','$http', '$filter', '$state', function ($scope, http, filter, $state) {

  function rowTemplate() {
    return '<div ng-dblclick="grid.appScope.onDblClickRow(row)" >' +
    '  <div ng-repeat="(colRenderIndex, col) in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ng-class="{ \'ui-grid-row-header-cell\': col.isRowHeader }"  ui-grid-cell></div>' +
    '</div>';
  }

  $scope.enginesGrid = {
    enableRowSelection: true,
    enableRowHeaderSelection: false,
    enableCellEditOnFocus : false,
    enableFiltering: true,
    rowTemplate: rowTemplate(),
  };

  $scope.enginesGrid.columnDefs = [
    { name: 'serial_num', headerCellClass: $scope.highlightFilteredHeader  },
    { name: 'Aircraft_tail_num'},
    { name: 'manufacturer'},
    { name: 'model'},
    { name: 'last_flight_id'},
    { name: 'fuel_spec'},
    { name: 'watchlist'}
  ]

  $scope.enginesGrid.multiSelect = false;

  $scope.onDblClickRow = function(rowItem){
    $state.go("engine", {serial_num: rowItem.entity.serial_num})
  }

  http.get("api/engine?detail=true").then(function(response){
    $scope.enginesGrid.data = response.data;
  })
}])

joustDash.controller('costCtrl', function($scope) {
})

joustDash.controller('GraphCtrl', function($scope) {
})

joustDash.controller('watchlistCtrl', ['$scope', function (scope) {
  scope.rowCollection = [
    {tail_num: 'N001KA', model: 'A380-841', issue: 'Engine 4 overheating', last_update: '2016-02-29'},
    {tail_num: 'N032KA', model: 'A380-841', issue: 'Engine 2 fuel inefficiency', last_update: '2016-03-25'},
    {tail_num: 'N083KA', model: 'A380-841', issue: 'Engine 1 approaching routine inspection', last_update: '2016-02-29'}
  ]
}])

joustDash.controller('maintenanceCtrl', ['$scope', function (scope) {
  scope.rowCollection = [
    {tail_num: 'N001KA', model: 'A380-841', remaining_days: '9 days'},
    {tail_num: 'N032KA', model: 'A380-841', remaining_days: '3 days'},
    {tail_num: 'N083KA', model: 'A380-841', remaining_days: '22 days'}
  ]
}])

joustDash.controller('flightGraphCtrl', function ($scope) {
  Highcharts.chart('flightGraphContainer', {
    chart: {
      type: 'areaspline'
    },
    title: {
      text: 'Fuel consumption during one flight'
    },
    legend: {
      layout: 'vertical',
      align: 'left',
      verticalAlign: 'top',
      x: 150,
      y: 100,
      floating: true,
      borderWidth: 1,
      backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
    },
    xAxis: {
      categories: [
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7'
      ],
      plotBands: [{ // visualize the weekend
        from: -0.5,
        to: 0.5,
        color: 'rgba(242, 12, 12, .2)',
        label: {
          text: 'Taxi out'
        }
      },
      {
        from: 0.5,
        to: 1.0,
        color: 'rgba(57, 26, 237, .2)',
        label: {
          text: 'Take off'
        }
      },
      {
        from: 1.0,
        to: 5.0,
        color: 'rgba(237, 16, 16, .3)',
        label: {
          text: 'Cruise'
        }
      },
      {
        from: 5.0,
        to: 5.5,
        color: 'rgba(234, 224, 39, .2)',
        label: {
          text: 'Landing'
        }
      },
      {
        from: 5.5,
        to: 6.5,
        color: 'rgba(51, 36, 36, .2)',
        label: {
          text: 'Taxi in'
        }
      }
    ]
  },

  yAxis: {
    title: {
      text: 'Fuel units'
    }
  },
  tooltip: {
    shared: true,
    valueSuffix: ' units'
  },
  credits: {
    enabled: false
  },
  plotOptions: {
    areaspline: {
      fillOpacity: 0.5
    }
  },
  series: [{
    name: 'Engine 1',
    data: [3, 8, 3, 5, 4, 10, 12]
  },
  {
    name: 'Engine 2',
    data: [1, 7, 4, 3, 3, 5, 4]
  },
  {
    name: 'Engine 3',
    data: [2, 8, 5, 5, 3, 4, 5]
  },
  {
    name: 'Engine 4',
    data: [3, 9, 4, 5, 6, 3, 3]
  }]
});
});

joustDash.controller('liveCostCtrl', function ($scope) {
  Highcharts.setOptions({
    global: {
      useUTC: false
    }
  });
  Highcharts.chart('liveCostContainer', {
    chart: {
      type: 'spline',
      animation: Highcharts.svg, // don't animate in old IE
      marginRight: 10,
      events: {
        load: function () {

          // set up the updating of the chart each second
          var series = this.series[0];
          setInterval(function () {
            var x = (new Date()).getTime(), // current time
            y = Math.random();
            series.addPoint([x, y], true, true);
          }, 1000);
        }
      }
    },
    xAxis: {
      type: 'datetime',
      tickPixelInterval: 150
    },
    yAxis: {
      title: {
        text: 'US Dollars'
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: '#808080'
      }]
    },
    tooltip: {
      formatter: function () {
        return '<b>' + this.series.name + '</b><br/>' +
        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
        Highcharts.numberFormat(this.y, 2);
      }
    },
    legend: {
      enabled: false
    },
    exporting: {
      enabled: false
    },
    series: [{
      name: 'Random data',
      data: (function () {
        // generate an array of random data
        var data = [],
        time = (new Date()).getTime(),
        i;

        for (i = -19; i <= 0; i += 1) {
          data.push({
            x: time + i * 1000,
            y: Math.random()
          });
        }
        return data;
      }())
    }]
  });

});

joustDash.controller('engineStatusPieCtrl', function ($scope) {
  Highcharts.setOptions({
    global: {
      useUTC: false
    }
  });
  Highcharts.chart('engineStatusPieContainer', {
    chart: {
      type: 'pie',
      options3d: {
        enabled: true;
        alpha: 55,
        beta: 0
      },
      title: {
        text:'Fleet availability'
      },
      tooltip: {
        pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          depth: 35,
          dataLabels: {
            enabled: true,
            format: '{point.name}'
          }
        }
      }
    },
    series: [{
      type: 'pie',
      name: 'Engine status',
      data: [
        ['Fuel efficient', 45.0],
        {
          name: 'Fuel inneficient',
          y: 12.8,
          sliced: true,
          selected: true
        }
      ]
    }]
  });
});
