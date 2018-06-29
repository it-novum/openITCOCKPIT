# AngularJS - VisJS

Development and documentation is in progress.

**NOTE:** This library is currently being refactored. The intention is make the directives simpler, removing the additional
'non-vis.js' related directives (such as time-board and time-navigation), and bring the DataSet factory in-line with the
vis.DataSet such that the documentation for vis is fully (hopefully) applicable and consistent.

Stay tuned, but expect changes!

**UPDATE** The initial refactoring is complete, but documentation is slightly inconsistent. Generally, the standard
visjs documentation should be used.  Take a look at the <a href="http://visjs.github.io/angular-visjs">example</a> where there is also the beginning of the updated documentation.



## Usage
**Note that this is out of date and will be removed shortly** It's just retained for information while the directive is updated.

```
  <time-line data="data" options="options" events="events"></time-line>
```

### Data:
```
  $scope.data = vis.DataSet({
     "1": {
       "id": 1,
       "content": "<i class=\"fi-flag\"></i> item 1",
       "start": "2014-09-01T17:59:13.706Z",
       "className": "magenta",
       "type": "box"
     },
     "2": {
       "id": 2,
       "content": "<a href=\"http://visjs.org\" target=\"_blank\">visjs.org</a>",
       "start": "2014-09-02T17:59:13.706Z",
       "type": "box"
     },
     "3": {
       "id": 3,
       "content": "item 3",
       "start": "2014-08-29T17:59:13.706Z",
       "type": "box"
     },
     "4": {
       "id": 4,
       "content": "item 4",
       "start": "2014-09-01T17:59:13.706Z",
       "end": "2014-09-03T17:59:13.706Z",
       "type": "range"
     },
     "5": {
       "id": 5,
       "content": "item 5",
       "start": "2014-08-30T17:59:13.706Z",
       "type": "point"
     },
     "6": {
       "id": 6,
       "content": "item 6",
       "start": "2014-09-04T17:59:13.706Z",
       "type": "point"
     },
     "7": {
       "id": 7,
       "content": "<i class=\"fi-anchor\"></i> item 7",
       "start": "2014-08-28T17:59:13.706Z",
       "end": "2014-08-29T17:59:13.706Z",
       "type": "range",
       "className": "orange"
     }
  });
```  

### Options:
```
  $scope.options = {
   "align": "center",
   "autoResize": true,
   "editable": true,
   "selectable": true,
   "orientation": "bottom",
   "showCurrentTime": true,
   "showCustomTime": true,
   "showMajorLabels": true,
   "showMinorLabels": true
  };
```  
                         
