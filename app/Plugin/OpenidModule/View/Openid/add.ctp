<?php
	echo $this->Form->create('Oauth2Client', array(
		'url' => array(
			'controller' => 'clients',
			'action' => 'add')));
	echo $this->Form->input('client_id', array(
		'label' => __('Client Id'),
		'type' => 'text'));
	echo $this->Form->input('secret', array());
	echo $this->Form->input('redirect_uri', array());
	echo $this->Form->end(__('Submit'));
?>