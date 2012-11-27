// AngularJS code goes here
// HTTP Interceptor for the Ajax spinner - by Adam Webber - angularjs groups
var ngModule = angular.module('HHF', ['ngResource', '$strap.directives'])

    .config(function($interpolateProvider) {
        $interpolateProvider.startSymbol('[[');
        $interpolateProvider.endSymbol(']]');
    })
    .config(function ($httpProvider) {
        $httpProvider.responseInterceptors.push('myHttpInterceptor');
        var spinnerFunction = function (data, headersGetter) {
            $('#spinner').show();
            return data;
        };
        $httpProvider.defaults.transformRequest.push(spinnerFunction);
    })

    // register the interceptor as a service, intercepts ALL angular
    // ajax http calls
    .factory('myHttpInterceptor', function ($q, $window) {
        return function (promise) {
            return promise.then(function (response) {
                // do something on success
                $('#spinner').hide()
                return response;

            }, function (response) {
                // do something on error
                $('#spinner').hide()
                return $q.reject(response);
            });
        };
    })

    // Main controller. Used by many pages to make ajax calls and get the
    // data back in JSON format
    .controller('MainCtrl', function ($scope, $resource, $location) {

        // Check just in case
        $scope.getFiltered = function getFiltered(area) {

            var fullUrl = $location.absUrl();

            var baseUrl = $location.protocol() + '://' + $location.host();
            var path    = fullUrl.replace(baseUrl, '');
            var getPath = '';

            var getSort = '';

            switch (path) {
                case '/awards':
                    getPath = path + '/get/' + area;
                    break;
                case '/players':
                    getPath = path + '/get';
                    getSort = 'name';
                    break;
                case '/episodes':
                    getPath = path + '/get';
                    getSort = '-number';
                    break;
                case '/':
                    getPath = '/awards/get/0/5';
                    break;
           }

            if (path != '') {
                $scope.res       = $resource(getPath);
                $scope.data      = $scope.res.get();
                $scope.area      = area;
                $scope.predicate = getSort;
            }
        }

        $scope.getFiltered(0);
    })
