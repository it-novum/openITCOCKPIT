<?php $this->layout = 'bootstrap2'; ?>
<?php $this->set('title_for_layout', 'Bootstrap2 examples'); ?>

<div class="row">
	<div class="span3">
		<ul class="nav nav-tabs nav-stacked affix">
			<li><a href="#forms"><i class="icon-chevron-right pull-right"></i> Forms</a></li>
			<li><a href="#pagination"><i class="icon-chevron-right pull-right"></i> Pagination</a></li>
			<li><a href="#alerts"><i class="icon-chevron-right pull-right"></i> Alerts</a></li>
		</ul>
	</div>
	<div class="span9">
		<h1>BoostCake Examples <small>Bootstrap Version 2.3.2</small></h1>

		<section id="forms">
			<div class="page-header">
				<h2>Forms</h2>
			</div>

			<h3>Default styles</h3>
			<p>Individual form controls receive styling, but without any required base class on the <code>&lt;form&gt;</code> or large changes in markup. Results in stacked, left-aligned labels on top of form controls.</p>

			<?php echo $this->element('BoostCake.bootstrap2/default_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/default_form.ctp'));
			?></pre>
			<hr>

			<h3>Search form</h3>
			<p>Add <code>.form-search</code> to the form and <code>.search-query</code> to the <code>&lt;input&gt;</code> for an extra-rounded text input.</p>

			<?php echo $this->element('BoostCake.bootstrap2/search_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/search_form.ctp'));
			?></pre>
			<hr>

			<h3>Inline form</h3>
			<p>Add <code>.form-inline</code> for left-aligned labels and inline-block controls for a compact layout.</p>

			<?php echo $this->element('BoostCake.bootstrap2/inline_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/inline_form.ctp'));
			?></pre>
			<hr>

			<h3>Horizontal form</h3>
			<p>
				Right align labels and float them to the left to make them appear on the same line as controls.
				Requires the most markup changes from a default form:
			</p>
			<ul>
				<li>Add <code>.form-horizontal</code> to the form</li>
				<li>Wrap labels and controls in <code>.control-group</code></li>
				<li>Add <code>.control-label</code> to the label</li>
				<li>Wrap any associated controls in <code>.controls</code> for proper alignment</li>
			</ul>

			<?php echo $this->element('BoostCake.bootstrap2/horizontal_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/horizontal_form.ctp'));
			?></pre>
			<hr>

			<h3>Other form example</h3>
			<?php
			$BoostCake = ClassRegistry::getObject('BoostCake');
			$BoostCake->validationErrors['price_error'] = array('Please provide a price');
			$BoostCake->validationErrors['password'] = array('Please provide a password');
			?>

			<?php echo $this->element('BoostCake.bootstrap2/other_form'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/other_form.ctp'));
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

			<?php echo $this->element('BoostCake.bootstrap2/standard_pagination'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/standard_pagination.ctp'));
			?></pre>

			<h3>Sizes</h3>
			<p>
				Fancy larger or smaller pagination? Add .pagination-large,
				<code>.pagination-small</code>, or <code>.pagination-mini</code> for additional sizes.
			</p>

			<?php echo $this->element('BoostCake.bootstrap2/sizes_pagination'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/sizes_pagination.ctp'));
			?></pre>

			<h3>Alignment</h3>
			<p>
				Add one of two optional classes to change the alignment of pagination links:
				<code>.pagination-centered</code> and <code>.pagination-right</code>.
			</p>

			<?php echo $this->element('BoostCake.bootstrap2/alignment_pagination'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/alignment_pagination.ctp'));
			?></pre>

			<h3>Pager</h3>
			<p>
				Quick previous and next links for simple pagination implementations with light markup and styles.
				It's great for simple sites like blogs or magazines.
			</p>

			<?php echo $this->element('BoostCake.bootstrap2/pager'); ?>
			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/pager.ctp'));
			?></pre>

		</section>

		<section id="alerts">
			<div class="page-header">
				<h2>Alerts</h2>
			</div>

			<?php echo $this->Session->flash('notice'); ?>
			<?php echo $this->Session->flash('success'); ?>
			<?php echo $this->Session->flash('error'); ?>

			<pre class="prettyprint"><?php
				echo h(file_get_contents(dirname(__DIR__) . '/Elements/bootstrap2/alerts.ctp'));
			?></pre>
		</section>
	</div>
</div>