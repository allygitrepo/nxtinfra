<?php if($this->session->flashdata('success')) { ?>
	
	<script>
		console.log(1);
	Swal.fire(
	'Good job!',
	'<?= $this->session->flashdata('success'); ?>',
	'success'
	)
	</script>
	
<?php } ?>
<?php if($this->session->flashdata('failure')) { ?>
	<script>
	Swal.fire({
	type: 'error',
	title: 'Oops...',
	text: '<?= $this->session->flashdata('failure'); ?>',
	}
	)
	</script>
	
<?php } ?>
<?php if($this->session->flashdata('upload_failure')) { ?>
	
	<script>
	Swal.fire(
	'Error!',
	'<?= $this->session->flashdata('upload_failure'); ?>',
	'error'
	)
	</script>
	
<?php } ?>