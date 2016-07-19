[//]: # (Links)
[nodes]: /containers "Nodes"

[//]: # (Pictures)

[//]: # (Content)

## What are nodes?

Nodes gives you the possibility to create a unlimited number of containers. 

This can help you to manage a custom container structure like you may know from LDAP.

This structure is used to set the granular user permissions for each object 

------ 
##### <span class="text-info">Example of container structure</span>

<div markdown="1">![Example](/img/docs/nodes.png)</div>


###Object to container allocation table 
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Config Items</th>
			<th style="text-align:center" colspan="9">Container</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td></td>
			<td style="text-align:center"><i class="fa fa-globe"></i> ROOT</td>
			<td style="text-align:center"><i class="fa fa-home"></i> TENANT</td>
			<td style="text-align:center"><i class="fa fa-location-arrow"></i> LOCATION</td>
			<td style="text-align:center"><i class="fa fa-cloud"></i> DEVICEGROUP</td>
			<td style="text-align:center"><i class="fa fa-link"></i> NODE</td>
			<td style="text-align:center"><i class="fa fa-users"></i> CONTACTGROUP</td>
			<td style="text-align:center"><i class="fa fa-sitemap"></i> HOSTGROUP</td>
			<td style="text-align:center"><i class="fa fa-cogs"></i> SERVICEGROUP</td>
			<td style="text-align:center"><i class="fa fa-pencil-square-o"></i> SERVICETEMPLATEGROUP</td>
		</tr>
		<tr>
			<td>Users</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Tenants</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Devicegroup</td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Nodes</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Locations</td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Contacts</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Contactgroups</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Timeperiods</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Hosts</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Hosttemplates</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Hostgroups</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Services</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Servicetemplates</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
		</tr>
		<tr>
			<td>Servicegroups</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Satellites</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Servicetemplategroups</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Hostescalations</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Serviceescalations</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Hostdependencies</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
		<tr>
			<td>Servicedependencies</td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="green">✓</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
			<td style="text-align:center"><font color="red">✗</font></td>
		</tr>
	</tbody>
</table>