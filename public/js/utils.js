// AngularJS code goes here
var ngModule = angular.module('HHF', ['ngResource']);

ngModule.controller('HoFCtrl', function ($scope, $resource) {
    $scope.hof = $resource('/awards/hof');
    $scope.hofData = $scope.hof.get();
    $scope.gameballs = $scope.hofData.gameballs;
    $scope.kicks = $scope.hofData.kicks;
});

ngModule.controller('EpisodesCtrl', function ($scope, $resource) {
    $scope.episodes = $resource('/episodes/get');
    $scope.episodesData = $scope.episodes.get();
});

ngModule.controller('PlayersCtrl', function ($scope, $resource) {
    $scope.players = $resource('/players/get');
    $scope.playersData = $scope.players.get();
});

// Utility directives

// ng-visible directive
ngModule.directive('ngVisible', function() {
    return function(scope, element, attr) {
        scope.$watch(attr.ngVisible, function(visible) {
            element.css('display', visible ? '' : 'none');
        });
    };
});


var Profile = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (SignUp.check("name") == false) {
            return false;
        }
        if (SignUp.check("email") == false) {
            return false;
        }
        $("#profileForm")[0].submit();
    }
};

var SignUp = {
    check: function (id) {
        if ($.trim($("#" + id)[0].value) == '') {
            $("#" + id)[0].focus();
            $("#" + id + "_alert").show();

            return false;
        };

        return true;
    },
    validate: function () {
        if (SignUp.check("name") == false) {
            return false;
        }
        if (SignUp.check("username") == false) {
            return false;
        }
        if (SignUp.check("email") == false) {
            return false;
        }
        if (SignUp.check("password") == false) {
            return false;
        }
        if ($("#password")[0].value != $("#repeatPassword")[0].value) {
            $("#repeatPassword")[0].focus();
            $("#repeatPassword_alert").show();

            return false;
        }
        $("#registerForm")[0].submit();
    }
}

$(document).ready(function () {
    $("#registerForm .alert").hide();
    $("div.profile .alert").hide();
});
