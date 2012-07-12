// AngularJS code goes here
// HTTP Interceptor for the Ajax spinner - by Adam Webber - angularjs groups
var ngModule = angular.module('HHF', ['ngResource', 'ui'])
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

// Dots by Andy Joslin - angularjs groups
ngModule.directive('dots', function() {
    return function(scope, elm, attrs) {
        var delay = +attrs.delay || 300;
        var count = +attrs.count || 3;
        var chars = attrs.char || '.';
        //put dots at the end of given element
        var $dots=$("<span>").appendTo(elm);
        setInterval(function() {
            if ($dots.text().length < count) {
                $dots.append(chars);
            } else {
                $dots.html('');
            }
        }, delay);
    };
});

ngModule.controller('HoFCtrl', function ($scope, $resource) {
    $scope.hof = $resource('/awards/hof');
    $scope.hofData = $scope.hof.get();
});

ngModule.controller('AwardsCtrl', function($scope, $resource) {

    $scope.getFiltered = function getFiltered(area) {
        $scope.hof = $resource('/awards/get/'+area);
        $scope.hofData = $scope.hof.get();
        $scope.area = area;
    };
    $scope.getFiltered(0);
});

ngModule.controller('EpisodesCtrl', function ($scope, $resource) {
    $scope.episodes = $resource('/episodes/get');
    $scope.episodesData = $scope.episodes.get();
    $scope.predicate = '-airDate';
});

ngModule.controller('addEditEpisodeCtrl', function ($scope) {
    $scope.name = 'episodeDate';
    $scope.dateOptions = {
        dateFormat: 'yy-mm-dd'
    }
});

ngModule.controller('PlayersCtrl', function ($scope, $resource) {
    $scope.players = $resource('/players/get');
    $scope.playersData = $scope.players.get();
    $scope.predicate = 'name';
});

