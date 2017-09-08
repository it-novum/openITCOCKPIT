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
echo $this->Html->script('vendor/sigmajs/sigma.core.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/conrad.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/utils/sigma.utils.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/utils/sigma.polyfills.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/sigma.settings.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/classes/sigma.classes.dispatcher.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/classes/sigma.classes.configurable.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/classes/sigma.classes.graph.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/classes/sigma.classes.camera.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/classes/sigma.classes.quad.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/classes/sigma.classes.edgequad.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/captors/sigma.captors.mouse.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/captors/sigma.captors.touch.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/renderers/sigma.renderers.canvas.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/renderers/canvas/sigma.canvas.labels.def.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/renderers/canvas/sigma.canvas.hovers.def.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/renderers/canvas/sigma.canvas.nodes.def.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/renderers/canvas/sigma.canvas.edges.arrow.js', ['inline' => false]);
// echo $this->Html->script('vendor/sigmajs/renderers/canvas/sigma.canvas.extremities.def.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/middlewares/sigma.middlewares.rescale.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/misc/sigma.misc.animation.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/misc/sigma.misc.bindEvents.js', ['inline' => false]);
// echo $this->Html->script('vendor/sigmajs/misc/sigma.misc.bindDOMEvents.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/misc/sigma.misc.drawHovers.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/plugins/sigma.plugins.neighborhoods/sigma.plugins.neighborhoods.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/plugins/sigma.layout.forceAtlas2/supervisor.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/plugins/sigma.layout.forceAtlas2/worker.js', ['inline' => false]);
echo $this->Html->script('vendor/sigmajs/plugins/sigma.parsers.json/sigma.parsers.json.js', ['inline' => false]);
?>
<?php $this->Paginator->options(['url' => $this->params['named']]); ?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-globe fa-fw "></i>
            <?php echo __('Expert Monitoring') ?>
            <span>>
                <?php echo __('Statusmap'); ?>
            </span>
        </h1>
    </div>
</div>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <div class="widget-toolbar" role="menu">

                    </div>

                    <span class="widget-icon hidden-mobile"> <i class="fa fa-globe"></i> </span>
                    <h2 class="hidden-mobile"><?php echo __('Statusmap'); ?> </h2>

                </header>

                <div>
                    <div class="widget-body no-padding">
                        <div id="node_search">
                            <i class="fa fa-desktop"></i> &nbsp; <?php echo __('Hostsearch'); ?>:<br/>
                            <input name="enter_node" id="enter_node"/>
                            <input type="hidden" id="my_node_id"/>
                            <button id="search_entered_node"><i class="fa fa-search-plus"></i></button>
                            <div id="autocomplete_results"></div>
                        </div>
                        <div id="my_statusmap"></div>
                        <div class="popover" id="popover"></div>
                    </div>
                </div>
            </div>
</section>
