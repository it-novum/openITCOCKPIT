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

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-comments fa-fw "></i>
            <?php echo __('Communication'); ?>
        </h1>
    </div>
</div>

<div class="jarviswidget jarviswidget-color-blueDark chat-widget" id="wid-id-1" data-widget-editbutton="true"
     data-widget-fullscreenbutton="true">

    <header>
        <span class="widget-icon"> <i class="fa fa-comments txt-color-white"></i> </span>
        <h2> <?php echo __('Public Chat'); ?> </h2>
        <?php /*
		<div class="widget-toolbar">
			<!-- add: non-hidden - to disable auto hide -->

			<div class="btn-group">
				<button class="btn dropdown-toggle btn-xs btn-success" data-toggle="dropdown">
					Status <i class="fa fa-caret-down"></i>
				</button>
				<ul class="dropdown-menu pull-right js-status-update">
					<li>
						<a href="javascript:void(0);"><i class="fa fa-circle txt-color-green"></i> Online</a>
					</li>
					<li>
						<a href="javascript:void(0);"><i class="fa fa-circle txt-color-red"></i> Busy</a>
					</li>
					<li>
						<a href="javascript:void(0);"><i class="fa fa-circle txt-color-orange"></i> Away</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="javascript:void(0);"><i class="fa fa-power-off"></i> Log Off</a>
					</li>
				</ul>
			</div>
		</div> */ ?>
    </header>

    <!-- widget div-->
    <div>
        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <div>
                <label>Title:</label>
                <input type="text"/>
            </div>
        </div>
        <!-- end widget edit box -->

        <div class="widget-body widget-hide-overflow no-padding">
            <!-- content goes here -->

            <!-- CHAT CONTAINER -->
            <div id="chat-container">
                <?php /*
				<span class="chat-list-open-close"><i class="fa fa-user"></i><b>!</b></span>

				<div class="chat-list-body custom-scroll">
					<ul id="chat-users">
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Robin Berry <span class="badge badge-inverse">23</span><span class="state"><i class="fa fa-circle txt-color-green pull-right"></i></span></a>
						</li>
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Mark Zeukartech <span class="state"><i class="last-online pull-right">2hrs</i></span></a>
						</li>
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Belmain Dolson <span class="state"><i class="last-online pull-right">45m</i></span></a>
						</li>
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Galvitch Drewbery <span class="state"><i class="fa fa-circle txt-color-green pull-right"></i></span></a>
						</li>
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Sadi Orlaf <span class="state"><i class="fa fa-circle txt-color-green pull-right"></i></span></a>
						</li>
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Markus <span class="state"><i class="last-online pull-right">2m</i></span> </a>
						</li>
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Sunny <span class="state"><i class="last-online pull-right">2m</i></span> </a>
						</li>
						<li>
							<a href="javascript:void(0);"><img src="/smartadmin/img/avatars/male.png" alt="">Denmark <span class="state"><i class="last-online pull-right">2m</i></span> </a>
						</li>
					</ul>
				</div>
				<div class="chat-list-footer">
					<div class="control-group">
						<form class="smart-form">
							<section>
								<label class="input">
									<input type="text" id="filter-chat-list" placeholder="Filter">
								</label>
							</section>
						</form>
					</div>
				</div> */ ?>
            </div>

            <!-- CHAT BODY -->
            <div id="chat-body" class="chat-body custom-scroll">
                <ul>
                </ul>
            </div>

            <!-- CHAT FOOTER -->
            <div class="chat-footer">

                <!-- CHAT TEXTAREA -->
                <div class="textarea-div">

                    <div class="typearea">
                        <textarea placeholder="<?php echo __('Write a reply...'); ?>" id="textarea-expand"
                                  class="custom-scroll"></textarea>
                    </div>

                </div>

                <!-- CHAT REPLY/SEND -->
                <span class="textarea-controls">
					<button class="btn btn-sm btn-primary pull-right">
						<?php echo __('Send'); ?>
					</button>
					<span class="pull-right smart-form" style="margin-top: 3px; margin-right: 10px;"> <label
                                class="checkbox pull-right">
							<input type="checkbox" name="subscription" id="subscription" checked="checked">
							<i></i><?php echo __('Press'); ?>
                            <strong> <?php echo __('ENTER'); ?> </strong> <?php echo __('to send'); ?> </label> </span> </span>

            </div>

            <!-- end content -->
        </div>

    </div>
    <!-- end widget div -->
</div>