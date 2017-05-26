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

?>
<header id="header">
    <div id="logo-group">
        <span id="logo"><?php echo $systemname; ?></span>
        <?php if ($loggedIn): ?>
        <?php /*
			<span id="activity" class="activity-dropdown"> <i class="fa fa-bullhorn"></i> <b class="badge"> 21 </b> </span>



			<!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
			<div class="ajax-dropdown">

				<!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
				<div class="btn-group btn-group-justified" data-toggle="buttons">
					<label class="btn btn-default">
						<input type="radio" name="activity" id="ajax/notify/mail.html">
						Msgs <b class="badge">14</b></label>
					<label class="btn btn-default">
						<input type="radio" name="activity" id="ajax/notify/notifications.html">
						notify <b class="badge">3</b></label>
					<label class="btn btn-default">
						<input type="radio" name="activity" id="ajax/notify/tasks.html">
						Tasks <b class="badge">4</b></label>
				</div>

				<!-- notification content -->
				<div class="ajax-notifications custom-scroll">

					<div class="alert alert-transparent">
						<h4>Click a button to show messages here</h4>
						This blank page message helps protect your privacy, or you can show the first message here automatically.
					</div>

					<i class="fa fa-lock fa-4x fa-border"></i>

				</div>
				<!-- end notification content -->

				<!-- footer: refresh area -->
				<span> Last updated on: 12/12/2013 9:43AM
					<button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
						<i class="fa fa-refresh"></i>
					</button> </span>
				<!-- end footer -->

			</div> */ ?>
    </div>

    <div class="pull-right">
        <div class="btn-header pull-right">
            <span> <a href="/login/logout" data-original-title="<?php echo __('Sign out'); ?>" data-placement="left"
                      rel="tooltip" data-container="body"><i class="fa fa-sign-out"></i></a> </span>
        </div>
        <div id="show_shortcuts" class="btn-header pull-right hidden-mobile hidden-tablet" data-toggle="modal"
             data-target="#ShortcutsHelp">
            <span> <a href="javascript:void(0);" data-original-title="<?php echo __('Keyboard shortcuts'); ?>"
                      data-placement="left" rel="tooltip" data-container="body"><i
                            class="fa fa-keyboard-o"></i></a> </span>
        </div>
        <div id="hide-menu" class="btn-header pull-right">
            <span> <a href="javascript:void(0);" data-original-title="<?php echo __('Collapse menu'); ?>"
                      data-placement="left" rel="tooltip" data-container="body"><i class="fa fa-arrow-circle-left"></i></a> </span>
        </div>
        <div class="btn-header pull-right">
            <span> <a href="/exports/index" data-original-title="<?php echo __('Refresh monitoring configuration'); ?>"
                      data-placement="left" rel="tooltip" data-container="body"><i class="fa fa-retweet" id="i-export-running-checker"></i></a></span>
        </div>
        <?php if($exportRunningHeaderInfo === 'yes') :?>
            <span id="monitoring-export-running-checker" class="hidden">1</span>
        <?php endif; ?>
        <div class="btn-header pull-right hidden-mobile hidden-tablet">
            <span><a href="javascript:void(0);" id="globalServertime" style="font-weight:normal;"
                     data-render-utc="<?php echo time(); ?>"
                     data-render-servertime="<?php echo date('F d, Y H:i:s'); ?>"
                     server-timezone-offset="<?php $d = new DateTime();
                     echo $d->getOffset(); ?>" data-original-title="<?php echo __('Server time'); ?>"
                     data-placement="left" rel="tooltip" data-container="body"></a></span>
        </div>
        <div class="btn-header pull-right hidden-mobile hidden-tablet" style="display:none;">
            <?php App::uses('Timezone', 'Lib'); ?>
            <span><a href="javascript:void(0);" id="localClienttime"
                     user-timezone="<?php echo h($this->Auth->user('timezone')); ?>"
                     timezone-offset="<?php echo h(Timezone::getUserSystemOffset($this->Auth->user('timezone'))); ?>"
                     data-original-title="<?php echo __('Your local time'); ?>" data-placement="left" rel="tooltip"
                     data-container="body"></a></span>
        </div>
        <?php
        if (version_compare($availableVersion, $installedVersion) > 0 && $hasRootPrivileges === true): ?>
            <div class="btn-header pull-right hidden-mobile hidden-tablet">
                <span> <a href="/packetmanager/index" data-original-title="<?php echo __('New version available!'); ?>"
                          data-placement="left" rel="tooltip" data-container="body"><i
                                class="txt-color-blue fa fa-fire"></i></a> </span>
            </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</header>