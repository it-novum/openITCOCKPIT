<?php
$data = [];
foreach($servicestatus as $service){
	$data[] = [
		($service['Servicestatus']['is_flapping'] == 1)?$this->Monitoring->serviceFlappingIconColored($service['Servicestatus']['is_flapping'], '', $service['Servicestatus']['current_state']):$this->Status->humanServiceStatus('test', '/services/browser/'.$service['Service']['id'], ['test' => ['Servicestatus' => ['current_state' => $service['Servicestatus']['current_state']]]])['html_icon'],
		($service['Servicestatus']['current_state']>0)?(($service['Servicestatus']['problem_has_been_acknowledged'])?'<i class="fa fa-user txt-color-blue"></i>':'<i class="fa fa-user"></i>'):'' ,
		($service['Servicestatus']['scheduled_downtime_depth']>0)?'<i class="fa fa-power-off"></i>':'',
		'<a href="/hosts/browser/'.$service['Host']['id'].'">'.$service['Host']['name'].'</a>',
		'<a href="/service/browser/'.$service['Service']['id'].'">'.((!empty($service['Service']['name']))?$service['Service']['name']:$service['Servicetemplate']['name']).'</a>',
		h((empty($service['Servicestatus']))?'N/A':$this->Utils->secondsInHumanShort(time() - strtotime($service['Servicestatus']['last_hard_state_change'])))
	];
}

echo json_encode(['data' => $data]);
