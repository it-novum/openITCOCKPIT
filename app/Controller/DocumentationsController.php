<?php
// Copyright (C) <2015>  <it-novum GmbH>
//
// This file is dual licensed
//
// 1.
//	This program is free software: you can redistribute it and/or modify
//	it under the terms of the GNU General Public License as published by
//	the Free Software Foundation, version 3 of the License.
//
//	This program is distributed in the hope that it will be useful,
//	but WITHOUT ANY WARRANTY; without even the implied warranty of
//	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//	GNU General Public License for more details.
//
//	You should have received a copy of the GNU General Public License
//	along with this program.  If not, see <http://www.gnu.org/licenses/>.
//

// 2.
//	If you purchased an openITCOCKPIT Enterprise Edition you can use this file
//	under the terms of the openITCOCKPIT Enterprise Edition license agreement.
//	License agreement and license key will be shipped with the order
//	confirmation.

class DocumentationsController extends AppController{
	public $layout = 'Admin.default';
	public $components = ['Bbcode'];
	public $helpers = ['Bbcode'];

	public $uses = ['Documentation', 'Host'];

	public function view($uuid = null){
		if($this->request->is('post') || $this->request->is('put')){
			if($this->Documentation->save($this->request->data)){
				$this->setFlash(__('Page successfully saved'));
				$this->redirect(['action' => 'view', $uuid]);
			}else{
				$this->setFlash(__('Could not save data'), false);
			}
		}

		$this->set('back_url', $this->referer());
		$post = $this->Documentation->findByUuid($uuid);

		$host = $this->Host->find('first', [
			'fields' => [
				'Host.id',
				'Host.uuid',
				'Host.name',
				'Host.address',
				'Host.host_url'
			],
			'conditions' => [
				'Host.uuid' => $uuid
			],
			'contain' => [
				'Container'
			]
		]);

		if(!$this->allowedByContainerId(Hash::extract($host, 'Container.{n}.id'))){
			$this->render403();
			return;
		}

		$hostDocuExists = !empty($post);

		$this->set(compact(['post', 'uuid', 'host', 'hostDocuExists']));
	}

	public function index(){
		$this->redirect(['action' => 'wiki']);
	}

	public function wiki($categoryUrl = null, $pageUrl = null, $language = 'en'){
		$wiki = [
			'dashboard' => [ // Category URL
				'name' => __('Dashboard'), // Display name
				'directory' => 'dashboard', // Directory name
				'children' => [
					'dashboard' => [ // Subject URL
						'name' => __('Dashboard'), // Display name
						'description' => 'The dashboard is your desktop in openITCOCKPIT, where you can add widigets that gather the information you need to see.',
						'file' => 'dashboard', // File name
						'icon' => 'fa fa-dashboard', // Icon class
					],
				],
			],

			'eventcorrelations' => [
				'name' => __('Eventcorrelations'),
				'directory' => 'eventcorrelations',
				'children' => [
					'eventcorrelations' => [
						'name' => __('Eventcorrelations'),
						'description' => 'You can associate different services from different host so you do not have to look your self for a specific correlation, such as if a host group has a ram usage over 90% you buy new hardware for this host group.',
						'file' => 'eventcorrelations',
						'icon' => 'fa fa-sitemap fa-rotate-90',
					],
					'evc_hosttemplates' => [
						'name' => __('EVC Hosttemplates'),
						'description' => 'You need event correlation host templates to create an event correlation.',
						'file' => 'evc_hosttemplates',
						'icon' => 'fa fa-pencil-square-o',
					],
					'evc_servicetemplates' => [
						'name' => __('EVC Servicetemplates'),
						'description' => 'You need event correlation service templates to create an event correlation.',
						'file' => 'evc_servicetemplates',
						'icon' => 'fa fa-pencil-square-o',
					],
				]
			],

			'maps' => [
				'name' => __('Maps'),
				'directory' => 'maps',
				'children' => [
					'statusmap' => [
						'name' => __('Statusmap'),
						'description' => 'The [statusmap] visualizes your hosts, ' .
							'with their connection with each other and their states.',
						'file' => 'statusmap',
						'icon' => 'fa fa-globe',
					],
					'automap' => [
						'name' => __('Automap'),
						'description' => 'You can quickly create a map of host and their services filtered by regular expressions.',
						'file' => 'automap',
						'icon' => 'fa fa-globe',
					],
					'maps' => [
						'name' => __('Maps'),
						'description' => 'Use the map to customize the visualization of host and services on a map.',
						'file' => 'maps',
						'icon' => 'fa fa-map-marker',
					],
					'rotations' => [
						'name' => __('Rotations'),
						'description' => 'A map rotation can rotate through a group of maps in an interval.',
						'file' => 'rotations',
						'icon' => 'fa fa-retweet',
					],
				],
			],

			'basic-monitoring' => [
				'name' => __('Basic Monitoring'),
				'directory' => 'basic_monitoring',
				'children' => [
					'hosts' => [
						'name' => __('Hosts'),
						'description' => 'A host is a device connected to a network.',
						'file' => 'hosts',
						'icon' => 'fa fa-desktop',
					],
					'services' => [
						'name' => __('Services'),
						'description' => 'A service is command with which you retrieve informations from a host.',
						'file' => 'services',
						'icon' => 'fa fa-cog',
					],
					'browser' => [
						'name' => __('Browser'),
						'description' => 'Here you can browse your nodes.' .
							'The browser will give you an overview over the current host ' .
							'and service status of all host and services in your current node.',
						'file' => 'browser',
						'icon' => 'fa fa-list',
					],
					'hosttemplates' => [
						'name' => __('Hosttemplates'),
						'description' => 'Use host templates for easier host creation.',
						'file' => 'hosttemplates',
						'icon' => 'fa fa-pencil-square-o',
					],
					'servicetemplates' => [
						'name' => __('Servicetemplates'),
						'description' => 'Use service templates for easier service creation.',
						'file' => 'servicetemplates',
						'icon' => 'fa fa-pencil-square-o',
					],
					'servicetemplategroups' => [
						'name' => __('Servicetemplategroups'),
						'description' => 'Use service template groups to append not present services ' .
							'to a host or a host group in an easy way.',
						'file' => 'servicetemplategroups',
						'icon' => 'fa fa-pencil-square-o',
					],
					'hostgroups' => [
						'name' => __('Hostgroups'),
						'description' => 'Host groups contain a group of hosts. ' .
							'You can assign all hosts to an action by only choosing the group.',
						'file' => 'hostgroups',
						'icon' => 'fa fa-sitemap',
					],
					'servicegroups' => [
						'name' => __('Servicegroups'),
						'description' => 'service groups contain a group of services. ' .
							'You can assign all services to an action by only choosing the group.',
						'file' => 'servicegroups',
						'icon' => 'fa fa-cogs',
					],
					'contacts' => [
						'name' => __('Contacts'),
						'description' => 'Contacts contain information whom to notify ' .
							'in case of a particular state of a host or service.',
						'file' => 'contacts',
						'icon' => 'fa fa-user',
					],
					'contactgroups' => [
						'name' => __('Contactgroups'),
						'description' => 'Contact groups contain a group of contacts. ' .
							'You can assign all contacts to a notification by only choosing the group.',
						'file' => 'contactgroups',
						'icon' => 'fa fa-users',
					],
					'calendar' => [
						'name' => __('Calendar'),
						'description' => 'You can use the calendar to configure your holidays ' .
							'where the monitoring software will not send any notifications.',
						'file' => 'calendar',
						'icon' => 'fa fa-calendar',
					],
					'timeperiods' => [
						'name' => __('Timeperiods'),
						'description' => 'Timeperiods contain a timespan for a regular week ' .
							'in which the monitoring of a service or host takes place.',
						'file' => 'timeperiods',
						'icon' => 'fa fa-clock-o',
					],
					'commands' => [
						'name' => __('Commands'),
						'description' => 'A command executes as a nagios terminal command ' .
							'from the user nagios on your server.',
						'file' => 'commands',
						'icon' => 'fa fa-terminal',
					],
					'tenants' => [
						'name' => __('Tenants'),
						'description' => 'Tenants contain information about user permissions to Nagios objects.',
						'file' => 'tenants',
						'icon' => 'fa fa-home',
					],
					'nodes' => [
						'name' => __('Nodes'),
						'description' => 'Organize your tenant structure.',
						'file' => 'nodes',
						'icon' => 'fa fa-link',
					],
					'locations' => [
						'name' => __('Locations'),
						'description' => 'Locations contain information about a specified location of a tenant node.',
						'file' => 'locations',
						'icon' => 'fa fa-location-arrow',
					],
					'graphgenerator' => [
						'name' => __('Graphgenerator'),
						'description' => 'You can generate graphs with data from diffrent services.',
						'file' => 'graphgenerator',
						'icon' => 'fa fa-area-chart',
					],
					'graph_collections' => [
						'name' => __('Graph Collections'),
						'description' => 'A graph collection will display you all graphs at once ' .
							'by overlaying them in a smart way.',
						'file' => 'graph_collections',
						'icon' => 'fa fa-list-alt',
					],
					'downtimes' => [
						'name' => __('Downtimes'),
						'description' => 'You can configure a downtime for hosts ' .
							'and services such as for a planned maintenance. ' .
							'In the time of the maintenance the hosts or services ' .
							'are not tracked by your monitoring system.',
						'file' => 'downtimes',
						'icon' => 'fa fa-power-off',
					],
					'logentries' => [
						'name' => __('Logentries'),
						'description' => 'Logentries contain logs, wich you can look through ' .
							'and filter or search wich logs you want to see.',
						'file' => 'logentries',
						'icon' => 'fa fa-file-text-o',
					],
					'notifications' => [
						'name' => __('Notifications'),
						'description' => 'Notifications will show you host or service notifications in a timespan.',
						'file' => 'notifications',
						'icon' => 'fa fa-envelope',
					],
					'performance_info' => [
						'name' => __('Performance Info'),
						'description' => 'Here you get an overview over your system preformance.',
						'file' => 'performance_info',
						'icon' => 'fa fa-fighter-jet',
					],
				],
			],

			'expert-monitoring' => [
				'name' => __('Expert Monitoring'),
				'directory' => 'expert_monitoring',
				'children' => [
					'user-defined-macros' => [
						'name' => __('User Defined Macros'),
						'description' => 'Intended for system paths or specific system command line options, ' .
							'to make your Nagios configuration more reusable in other system environments.',
						'file' => 'user_defined_macros',
						'icon' => 'fa fa-usd',
					],
					'host-escalations' => [
						'name' => __('Host Escalations'),
						'description' => 'Host escalations are optional follow-up notifications, ' .
							'that generates if a state change is not revoked in time.',
						'file' => 'host_escalations',
						'icon' => 'fa fa-bomb',
					],
					'service-escalations' => [
						'name' => __('Service Escalations'),
						'description' => 'Service escalations are optional follow-up notifications, ' .
							'that generates if a state change is not revoked in time.',
						'file' => 'service_escalations',
						'icon' => 'fa fa-bomb',
					],
					'host-dependencies' => [
						'name' => __('Host Dependencies'),
						'description' => '[Host dependencies] are optional settings to associate multiple hosts.',
						'file' => 'host_dependencies',
						'icon' => 'fa fa-sitemap',
					],
					'service-dependencies' => [
						'name' => __('Service Dependencies'),
						'description' => 'Service dependencies are optional settings to associate multiple services.',
						'file' => 'service_dependencies',
						'icon' => 'fa fa-sitemap',
					],
					'external-commands' => [
						'name' => __('External Commands'),
						'description' => 'The interface will help you create your own external commands ' .
							'by generating an example to explain of the supported command you want to use.',
						'file' => 'external_commands',
						'icon' => 'fa fa-terminal',
					],
				]
			],

			'distributed_monitoring' => [
				'name' => ('Distributed Monitoring'),
				'directory' => 'distributed_monitoring',
				'children' => [
					'satellites' => [
						'name' => __('Satellites'),
						'description' => 'Satellites are server which check hosts and deliver the results as a passive check to nagios.',
						'file' => 'satellites',
						'icon' => 'fa fa-cloud',
					],
				]
			],

			'reporting' => [
				'name' => ('Reporting'),
				'directory' => 'reporting',
				'children' => [
					'instant_report' => [
						'name' => __('Instant Report'),
						'description' => 'Instant reports generate reports in different formats of ' .
							'hosts or hosts and their services in a defined time span ' .
							'with hard or hard and soft sates.',
						'file' => 'instant_report',
						'icon' => 'fa fa-file-image-o',
					],
					'downtime_report' => [
						'name' => __('Downtime Report'),
						'description' => 'Downtime reports generate reports in different formats of ' .
							'hosts or hosts and their services in a defined time span in which downtimes occurred.',
						'file' => 'downtime_report',
						'icon' => 'fa fa-file-image-o',
					],
					'current_state_report' => [
						'name' => __('Current State Report'),
						'description' => 'Current state reports generate in different formats the current service states.',
						'file' => 'current_state_report',
						'icon' => 'fa fa-file-image-o',
					],
					'autoreport' => [
						'name' => __('Autoreport'),
						'description' => 'An autoreport automatically reports a report in a user defined way ' .
							'in a defined time to the defined user.',
						'file' => 'autoreport',
						'icon' => 'fa fa-file-image-o',
					],
					'settings' => [
						'name' => __('Settings'),
						'description' => 'General settings for an autoreport.',
						'file' => 'settings',
						'icon' => 'fa fa-cogs',
					],
				]
			],
			
			'plugins' => [
				'name' => 'Monitoring and Plugins',
				'directory' => 'plugins',
				'children' => [
					'nrpe' => [
						'name' => __('NRPE - Nagios Remote Plugin Executor [Linux]'),
						'file' => 'nrpe',
					],
					'check_by_ssh' => [
						'name' => __('check_by_ssh [Linux]'),
						'file' => 'check_by_ssh',
					],
					'check_wmi' => [
						'name' => __('Windows Management Instrumentation - WMI [Windows]'),
						'file' => 'check_wmi'
					],
					'check_mysql_health' => [
						'name' => __('check_mysql_health'),
						'file' => 'check_mysql_health'
					],
				]
			],

			'discovery' => [
				'name' => __('Discovery'),
				'directory' => 'discovery',
				'children' => [
					'mk_checks' => [
						'name' => __('Mk Checks'),
						'description' => 'A check_MK check is a check where you only interact with the host once.',
						'file' => 'mk_checks',
						'icon' => 'fa fa-cogs',
					],
					'mk_servicetemplates' => [
						'name' => __('Mk Servicetemplates'),
						'description' => 'You need MK service templates to add a new MK check.',
						'file' => 'mk_servicetemplates',
						'icon' => 'fa fa-pencil-square-o',
					],
				]
			],

			'documentation' => [
				'name' => __('Documentation'),
				'directory' => 'documentation',
				'children' => [
					'documentation' => [
						'name' => __('Documentation'),
						'description' => 'How to use this documentation.',
						'file' => 'documentation',
						'icon' => 'fa fa-book',
					],
				]
			],

			'communication' => [
				'name' => __('Communication'),
				'directory' => 'communication',
				'children' => [
					'communication' => [
						'name' => __('Communication'),
						'description' => 'Only users from your openITCOCKPIT implementation, ' .
							'can connect to the chat and chat with you.',
						'file' => 'communication',
						'icon' => 'fa fa-users',
					],
				]
			],

			'administration' => [
				'name' => ('Administration'),
				'directory' => 'administration',
				'children' => [
					'changelog' => [
						'name' => __('Changelog'),
						'description' => 'Changelog contains changes that made to your system, ' .
							'like editing, creating or deleting a nagios object.',
						'file' => 'changelog',
						'icon' => 'fa fa-code-fork',
					],
					'proxy_settings' => [
						'name' => __('Proxy Settings'),
						'description' => 'Here you can set up your proxy if needed.',
						'file' => 'proxy_settings',
						'icon' => 'fa fa-bolt',
					],
					'package_manager' => [
						'name' => __('Package Manager'),
						'description' => 'In this section you can see the available packages for openITCOCKPIT v3 ' .
							'and the changes that came with the new version of openITCOCKPIT v3.',
						'file' => 'package_manager',
						'icon' => 'fa fa-cloud-download',
					],
					'manage_users' => [
						'name' => __('Manage Users'),
						'description' => 'You can create, edit and delete users.',
						'file' => 'manage_users',
						'icon' => 'fa fa-user',
					],
					'manage_user_groups' => [
						'name' => __('Manage User Groups'),
						'description' => 'Manage a group of users.',
						'file' => 'manage_user_groups',
						'icon' => 'fa fa-users',
					],
					'debugging' => [
						'name' => __('Debugging'),
						'description' => 'In the debug section you will find information about the interface, processes, server, user and PHP.',
						'file' => 'debugging',
						'icon' => 'fa fa-bug',
					],
					'systemfailures' => [
						'name' => __('Systemfailures'),
						'description' => 'If your nagios did not work or is not working for some time, ' .
							'you can create a system failure to ignore nagios states for the defined time.',
						'file' => 'systemfailures',
						'icon' => 'fa fa-medkit',
					],
					'systemsettings' => [
						'name' => __('Systemsettings'),
						'description' => 'Here you configure settings for your web server, ' .
							'sudo server, monitoring, system, front end, check_mk and archive.',
						'file' => 'systemsettings',
						'icon' => 'fa fa-wrench',
					],
					'cronjobs' => [
						'name' => __('Cronjobs'),
						'description' => 'Cronjobs are tasks that will execute every x minutes.',
						'file' => 'cronjobs',
						'icon' => 'fa fa-clock-o',
					],
					'registration' => [
						'name' => __('Registration'),
						'description' => 'In the registration section you can view details of ' .
							'your current license key and get a feedback if your key is valid or not.',
						'file' => 'registration',
						'icon' => 'fa fa-check-square-o',
					],
				]
			],
			'i-doit' => [
				'name' => ('i-doit'),
				'directory' => 'i-doit',
				'children' => [
					'i-doit_systems' => [
						'name' => __('i-doit systems'),
						'description' => 'Configure your i-doit systems.',
						'file' => 'i-doit_systems',
						'icon' => 'fa fa-cogs',
					],
					'software-link_list' => [
						'name' => __('Software-Link list'),
						'description' => 'Create a software link list matched by a regular expression and a service template group',
						'file' => 'software-link_list',
						'icon' => 'fa fa-link',
					],
					'synchronisation' => [
						'name' => __('Synchronisation'),
						'description' => 'Synchronizes i-doit and openITCOCKPIT.',
						'file' => 'synchronisation',
						'icon' => 'fa fa-refresh',
					],
					'host_comparison' => [
						'name' => __('Host comparison'),
						'description' => 'Find out which hosts exist only in openITCOCKPIT.',
						'file' => 'host_comparison',
						'icon' => 'fa fa-compress',
					],
				],
			],
			
			'contribute_dev' => [
				'name' => ('Contribute and develop'),
				'directory' => 'contribute',
				'children' => [
					'github' => [
						'name' => __('GitHub'),
						'description' => 'A short description what GitHub is and why we use it',
						'file' => 'github',
						'icon' => 'fa fa-git-square',
					],
					'code_style' => [
						'name' => __('Code style guide'),
						'description' => 'Information about coding standard you should fulfill',
						'file' => 'code_style',
						'icon' => 'fa fa-code',
					],
				],
			],
			
			'additional_help' => [
				'name' => ('Additional Help'),
				'directory' => 'additional_help',
				'children' => [
					'markdown' => [
						'name' => __('Markdown'),
						'description' => 'A cheatsheet to help writing markdown formatted texts. ',
						'file' => 'markdown',
						'icon' => 'fa fa-pencil',
					],
					'mysql_performance' => [
						'name' => __('MySQL performance'),
						'description' => 'A few tips to optimize your MySQL performance.',
						'file' => 'mysql_performance',
						'icon' => 'fa fa-database',
					]
				],
			],
		];

		$parsedMarkdown = '';
		$subjectTitle = '';
		$icon = '';
		$renderPage = false;
		if($categoryUrl !== null && $pageUrl !== null){
			if(!isset($wiki[$categoryUrl]['directory']) || !isset($wiki[$categoryUrl]['children'][$pageUrl]['file'])){
				throw new NotFoundException('Record not found');
			}

			$renderPage = true;

			App::uses('File', 'Utility');
			require_once APP . 'Vendor' . DS . 'parsedown' . DS . 'Parsedown.php';
			require_once APP . 'Vendor' . DS . 'parsedown' . DS . 'ParsedownExtra.php';

			$basePath = APP . 'docs' . DS . $language;
			$categoryDirectory = $wiki[$categoryUrl]['directory'];
			$filename = $wiki[$categoryUrl]['children'][$pageUrl]['file'] . '.md';
			$filePath = $basePath . DS . $categoryDirectory . DS . $filename;

			$file = new File($filePath);
			if(!$file->exists()){
				throw new NotFoundException('File does not exists: ' . $filePath);
			}

			$subjectTitle = $wiki[$categoryUrl]['children'][$pageUrl]['name'];
			if(isset($wiki[$categoryUrl]['children'][$pageUrl]['icon'])){
				$icon = $wiki[$categoryUrl]['children'][$pageUrl]['icon'];
			}
			$parsedown = new ParsedownExtra();
			$parsedMarkdown = $parsedown->text($file->read());
		}

		$this->set(compact(['wiki', 'language', 'renderPage', 'parsedMarkdown', 'icon', 'subjectTitle']));
	}
}
