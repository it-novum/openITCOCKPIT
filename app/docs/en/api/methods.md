## Available data formats:
- json (/hosttemplates.json)
- xml (/hosttemplates.xml)

**This documentation will only contain json based examples.**

## HTTP Request Methods

#### Query all objects:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" value="/hosttemplates.json">
</div>


#### Query a single object by id:
<div class="input-group">
	<span class="input-group-addon bg-color-green txt-color-white">GET</span>
	<input type="text" class="form-control" value="/hosttemplates/1.json">
</div>


#### Create a new object:
<div class="input-group">
	<span class="input-group-addon bg-color-blue txt-color-white">POST</span>
	<input type="text" class="form-control" value="/hosttemplates.json">
</div>


#### Update an existing object by id
<div class="input-group">
	<span class="input-group-addon bg-color-blue txt-color-white">POST</span>
	<input type="text" class="form-control" value="/hosttemplates/1.json">
</div>

<div class="input-group">
	<span class="input-group-addon bg-color-blueDark txt-color-white">PUT</span>
	<input type="text" class="form-control" value="/hosttemplates/1.json">
</div>


#### Delete an existing object by id:
<div class="input-group">
	<span class="input-group-addon bg-color-red txt-color-white">DELETE</span>
	<input type="text" class="form-control" value="/hosttemplates/1.json">
</div>

## HTTP status codes
- 200 OK
- 301 Moved Permanently
- 302 Found
- 403 Forbidden
- 404 Not Found
- 405 Method Not Allowed

