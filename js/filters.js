var joustDash = angular.module('joustDash')

joustDash.filter('dateFilter', ['$filter', function ($filter) {
  var filterFilter = $filter('filter');
  var standardComparator = function standardComparator(obj, text) {
    text = ('' + text).toLowerCase();
    return ('' + obj).toLowerCase().indexOf(text) > -1;
  }

  return function dateFilter(array, expression) {
    function dateComparator(actual, expected) {

      var isBeforeActivated = expected.before;
      var isAfterActivated = expected.after;
      var isLower = expected.lower;
      var isHigher = expected.higher;
      var higherLimit;
      var lowerLimit;
      var itemDate;
      var queryDate;


      if (ng.isObject(expected)) {

        //date range
        if (expected.before || expected.after) {
          try {
            if (isBeforeActivated) {
              higherLimit = expected.before;

              itemDate = new Date(actual);
              queryDate = new Date(higherLimit);

              if (itemDate > queryDate) {
                return false;
              }
            }

            if (isAfterActivated) {
              lowerLimit = expected.after;


              itemDate = new Date(actual);
              queryDate = new Date(lowerLimit);

              if (itemDate < queryDate) {
                return false;
              }
            }

            return true;
          } catch (e) {
            return false;
          }

        } else if (isLower || isHigher) {
          //number range
          if (isLower) {
            higherLimit = expected.lower;

            if (actual > higherLimit) {
              return false;
            }
          }

          if (isHigher) {
            lowerLimit = expected.higher;
            if (actual < lowerLimit) {
              return false;
            }
          }

          return true;
        }
        //etc

        return true;

      }
      return standardComparator(actual, expected);
    }

    var output = filterFilter(array, expression, dateComparator);
    return output;
  }
}])

joustDash.filter('specificColFilter', ['$filter', function($filter) {

  // function that's invoked each time Angular runs $digest()
  return function(input, predicate) {
    searchValue = predicate['$'];
    //console.log(searchValue);
    var customPredicate = function(value, index, array) {
      //console.log(value);

      // if filter has no value, return true for each element of the input array
      if (typeof searchValue === 'undefined') {
        return true;
      }

      var p0 = value['data2'].toLowerCase().indexOf(searchValue.toLowerCase());
      var p1 = value['data3'].toLowerCase().indexOf(searchValue.toLowerCase());
      if (p0 > -1 || p1 > -1) {
        return true;
      } else {
        return false;
      }
    }

    //console.log(customPredicate);
    return $filter('filter')(input, customPredicate, false);
  }
}])
