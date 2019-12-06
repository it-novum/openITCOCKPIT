angular.module('openITCOCKPIT')
    .controller('SupportsIssueController', function($scope, $http){
        jQuery.ajax({
            url: "/legacy/js/lib/jquery-migrate-1.4.1.min.js",
            type: "get",
            cache: true,
            dataType: "script"
        });
        jQuery.ajax({
            url: "https://project.it-novum.com/s/706c3049afadd0ca1fb33e95554a86b1-T/-xdky2a/800010/76ebf73be4dd92dad1b7a8c846dfbc44/3.0.7/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector-embededjs.js?locale=en-US&collectorId=5350ef3b",
            type: "get",
            cache: true,
            dataType: "script"
        });

        window.ATL_JQ_PAGE_PROPS = {
            "triggerFunction": function(showCollectorDialog){
                //Requires that jQuery is available!
                jQuery("#JIRAIssue").click(function(e){
                    e.preventDefault();
                    showCollectorDialog();
                });
            }
        };
    });
