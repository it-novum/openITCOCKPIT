<?php
$data = [];
foreach($hoststatus as $host){
	$data[] = [
		($host['Hoststatus']['is_flapping'] == 1)?$this->Monitoring->hostFlappingIconColored($host['Hoststatus']['is_flapping'], '', $host['Hoststatus']['current_state']):$this->Status->humanHostStatus('test', '/hosts/browser/'.$host['Host']['id'], ['test' => ['Hoststatus' => ['current_state' => $host['Hoststatus']['current_state']]]])['html_icon'],
		($host['Hoststatus']['current_state']>0)?(($host['Hoststatus']['problem_has_been_acknowledged'])?'<i class="fa fa-user txt-color-blue"></i>':'<i class="fa fa-user"></i>'):'' ,
		($host['Hoststatus']['scheduled_downtime_depth']>0)?'<i class="fa fa-power-off"></i>':'',
		'<a href="/hosts/browser/'.$host['Host']['id'].'">'.$host['Host']['name'].'</a>',
		h((empty($host['Hoststatus']))?'N/A':$this->Utils->secondsInHumanShort(time() - strtotime($host['Hoststatus']['last_hard_state_change'])))
	];
}

echo json_encode(['data' => $data]);
