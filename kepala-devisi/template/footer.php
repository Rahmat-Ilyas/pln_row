<footer class="footer">
	© PLN UPT Makassar <?= date('Y') ?>. All rights reserved.
</footer>

</div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->


</div>
<!-- END wrapper -->

<script>
	var resizefunc = [];
</script>

<!-- jQuery  -->
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/bootstrap.min.js"></script>
<script src="../assets/js/detect.js"></script>
<script src="../assets/js/fastclick.js"></script>
<script src="../assets/js/jquery.slimscroll.js"></script>
<script src="../assets/js/jquery.blockUI.js"></script>
<script src="../assets/js/waves.js"></script>
<script src="../assets/js/wow.min.js"></script>
<script src="../assets/js/jquery.nicescroll.js"></script>
<script src="../assets/js/jquery.scrollTo.min.js"></script>

<!-- Data Table -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables/buttons.bootstrap.min.js"></script>

<script src="../assets/js/jquery.core.js"></script>
<script src="../assets/js/jquery.app.js"></script>
<script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhUwwpTQamL6ZhPKT2x2j2nI-eWNR8bZ0&callback=initialize&libraries=&v=weekly"
defer></script>
<script>
	$(document).ready(function() {
		$('[data-toggle1="tooltip"]').tooltip();
		$('#datatable').dataTable();

		<?php if (isset($res_updt_akun) && $res_updt_akun['status'] == 'success') { ?>
			Swal.fire({
				title: 'Berhasil Diupdte',
				text: "<?= $res_updt_akun['message'] ?>",
				type: 'success'
			}).then(function() {
				location.href = window.location.pathname;;
			});
		<?php } else if (isset($res_updt_akun) && $res_updt_akun['status'] == 'error') { ?>
			Swal.fire({
				title: 'Terjadi Kesalahan',
				text: "<?= $res_updt_akun['message'] ?>",
				type: 'error'
			});
		<?php } ?>
	});
</script>

</body>
</html>