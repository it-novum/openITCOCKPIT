<?php $this->layout = 'bootstrap3'; ?>
<?php $this->set('title_for_layout', 'Bootstrap3 examples'); ?>

<div class="row">
	<div class="col col-md-3">
		<ul class="nav nav-pills nav-stacked affix">
			<li><a href="#forms"><span class="glyphicon glyphicon-chevron-right pull-right"></span> Forms</a></li>
			<li><a href="#pagination"><span class="glyphicon glyphicon-chevron-right pull-right"></span> Pagination</a></li>
			<li><a href="#alerts"><span class="glyphicon glyphicon-chevron-right pull-right"></span> Alerts</a></li>
		</ul>
	</div>
	<div class="col col-md-9">
		<h1>BoostCake Examples <small>Bootstrap Version 3.0.0</small></h1>

		<section id="forms">
			<div class="page-header">
				<h2>Forms</h2>
			</div>

			<h3>Default styles</h3>
			<p>Individual form controls receive styling, but without any required base class on the <code>&lt;form&gt;</code> or large changes in markup. Results in stacked, left-aligned labels on top of form controls.</p>

			<?php echo $this->element('BoostCake.bootstrap3/default_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/default_form.ctp'));
			?></pre>
			<hr>

			<h3>Inline form</h3>
			<p>Add <code>.form-inline</code> for left-aligned labels and inline-block controls for a compact layout.</p>

			<?php echo $this->element('BoostCake.bootstrap3/inline_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/inline_form.ctp'));
			?></pre>
			<hr>

			<h3>Horizontal form</h3>
			<p>
				Use Bootstrap's predefined grid classes to align labels and groups of form controls in a horizontal layout.
			</p>

			<?php echo $this->element('BoostCake.bootstrap3/horizontal_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/horizontal_form.ctp'));
			?></pre>
			<hr>

			<h3>Other form example</h3>
			<?php
			$BoostCake = ClassRegistry::getObject('BoostCake');
			$BoostCake->validationErrors['password'] = array('Please provide a password');
			$BoostCake->validationErrors['price_error'] = array('Please provide a password');
			?>

			<?php echo $this->element('BoostCake.bootstrap3/other_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/other_form.ctp'));
			?></pre>
		</section>

		<section id="pagination">
			<div class="page-header">
				<h2>Pagination</h2>
			</div>

			<h3>Standard pagination</h3>
			<p>
				Simple pagination inspired by Rdio, great for apps and search results.
				The large block is hard to miss, easily scalable, and provides large click areas.
			</p>

			<?php
			$this->Paginator->request->params['paging']['Post'] = array(
				'page' => 10,
				'current' => 20,
				'count' => 1000,
				'prevPage' => true,
				'nextPage' => true,
				'pageCount' => 200,
				'order' => null,
				'limit' => 20,
				'options' => array(
					'page' => 1,
					'conditions' => array()
				),
				'paramType' => 'named'
			);
			$this->Paginator->options(array('model' => 'Post'));
			?>

			<?php echo $this->element('BoostCake.bootstrap3/standard_pagination'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/standard_pagination.ctp'));
			?></pre>

			<h3>Sizes</h3>
			<p>
				Fancy larger or smaller pagination? Add .pagination-lg,
				<code>.pagination-sm</code>, or <code>.pagination-mini</code> for additional sizes.
			</p>

			<?php echo $this->element('BoostCake.bootstrap3/sizes_pagination'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/sizes_pagination.ctp'));
			?></pre>

			<h3>Pager</h3>
			<p>
				Quick previous and next links for simple pagination implementations with light markup and styles.
				It's great for simple sites like blogs or magazines.
			</p>

			<?php echo $this->element('BoostCake.bootstrap3/pager'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/pager.ctp'));
			?></pre>

		</section>

		<section id="alerts">
			<div class="page-header">
				<h2>Alerts</h2>
			</div>

			<?php echo $this->Session->flash('success'); ?>
			<?php echo $this->Session->flash('info'); ?>
			<?php echo $this->Session->flash('warning'); ?>
			<?php echo $this->Session->flash('danger'); ?>

			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap3/alerts.ctp'));
			?></pre>
		</section>
	</div>
</div>
