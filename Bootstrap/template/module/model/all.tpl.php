<h1>
<?php
	if ( $this->from ) {
		echo '<small><a href="' . \Supersoniq\module_model_url( $this->from ) . '">' . $this->from->name( ) . '</a></small><br>';
	}
?>
	List <?= $this->model_type ?> 
	(<?= $this->pagination->count ?>)
<?php
	if ( $this->filter ) {
		echo '<br><small>Filtered "' . $this->filter . '"</small>';
	}
?>
</h1>
<?php

include( __DIR__ . '/all-action.tpl.php' );

if ( count( $this->models ) == 0 ) {
?>
	<p><em>There is no elements to display</em></p>
<?php
} else {
	$pagination_text = 'search';
	include( __DIR__ . '/all-pagination.tpl.php' );
	$this->models = $this->models->slice( $this->pagination->start, $this->pagination->size );
?>
<table class="table table-bordered table-striped table-data">

	<?= $this->display( $this->models, 'table' ); ?>

</table>
<?php
	$pagination_text = 'position';
	include( __DIR__ . '/all-pagination.tpl.php' );
}

if ( count( $this->models ) > 10 ) {
	include( __DIR__ . '/all-action.tpl.php' );
}
?>
