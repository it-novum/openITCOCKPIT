angular.module('openITCOCKPIT')
    .controller('DocumentationsWikiController', function($scope, $sce, $http, QueryStringService, BBParserService, $stateParams, $state, NotyService){

        $scope.urlDocumentation = $stateParams.documentation;

        $scope.load = function(){
            $http.get("/documentations/wiki.json", {
                params: {
                    'angular': true
                }
            }).then(function(result){
                $scope.documentations = result.data.documentations;

            }, function errorCallback(result){
                if(result.status === 403){
                    $state.go('403');
                }

                if(result.status === 404){
                    $state.go('404');
                }
            });
        };

        $scope.showDocumentation = function(categoryName, documentationKey){
            $http.post("/documentations/wiki.json?angular=true",
                {
                    category: categoryName,
                    documentation: documentationKey
                }
            ).then(function(result){
                $scope.currentDocumentation = result.data.documentation;
                $scope.currentDocumentationHtml = result.data.html;
                $('#angularDocumentationContentModal').modal('show');


            }, function errorCallback(result){

                NotyService.genericError({
                    message: 'Error while loading documentation'
                });
                $('#angularDocumentationContentModal').modal('hide');
            });
        };

        //Load all docs
        $scope.load();

        //Open a doc via URL?
        if($scope.urlDocumentation){
            var urlDocumentation = $scope.urlDocumentation.split(":");
            if(urlDocumentation.length === 2){
                $scope.showDocumentation(urlDocumentation[0], urlDocumentation[1]);
            }
        }


    });