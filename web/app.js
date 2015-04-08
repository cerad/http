(function(angular) { 'use strict';

var appModule = angular.module('CeradHttpApp', ['angularFileUpload']);

appModule.controller('CeradJsonFormController', ['$http',
function($http) 
{
  var vm = this;
  
  vm.user = 'Art User';

  vm.onSubmit = function()
  {
    var url = 'index.php';
    
    var payload = { user: vm.user };
    
    $http.post(url,payload).success(function(data)
    {
      var item = angular.fromJson(data);
      vm.user = item.user + ' xxx';
    });
  };
}]);

})(angular);
