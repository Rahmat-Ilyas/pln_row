<footer class="footer text-right">
	<div class="container">
		<div class="row">
			© PLN UPT Makassar <?= date('Y') ?>. All rights reserved.
		</div>

	</div>
</footer>

</div>
<!-- ============================================================== -->
<!-- End Right content here -->
<!-- ============================================================== -->


</div>
<!-- END wrapper -->

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>

<!-- Data Table -->
<script src="../assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="../assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="../assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="../assets/plugins/datatables/buttons.bootstrap.min.js"></script>

<script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Page Specific JS Libraries -->
<script src="../assets/plugins/dropzone/dropzone.js"></script>
<script src="../assets/js/webcam.min.js"></script>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhUwwpTQamL6ZhPKT2x2j2nI-eWNR8bZ0&callback=initialize&libraries=&v=weekly"
defer></script>
<script>
	var marker;

	// Rumus Radius/Jari-Jari
	var ls = <?= $rds['luas_daerah'] ?>*7/22;
	var radius_fix = Math.sqrt(ls * 1e+6);

	function initialize() {
		var loc = document.getElementById('location');
		const center_rad = { lat: <?= $rds['latitude'] ?>, lng: <?= $rds['longitude'] ?> };
		var center = { lat: <?= $rds['latitude'] ?>, lng: <?= $rds['longitude'] ?> };
		// Create the map.
		var map = new google.maps.Map(document.getElementById("map"), {
			zoom: 11,
			center: center,
			mapTypeId: "terrain",
		});

		marker = new google.maps.Marker({
			position: center,
			map,
		});

		var cityCircle = new google.maps.Circle({
			strokeColor: "#81c868",
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: "#81c868",
			fillOpacity: 0.35,
			clickable: false,
			map,
			center: center_rad,
			radius: radius_fix,
		});

		google.maps.event.addListener(map, 'click', function(event) {
			var lat1 = center_rad.lat;
			var lng1 = center_rad.lng;
			var lat2 = event.latLng.lat();
			var lng2 = event.latLng.lng();
			var distanceInMeters = getDistanceBetweenPoints(lat1, lng1, lat2, lng2);

			if (distanceInMeters > radius_fix) {
				alert("Pastikan anda memlih lokasi dalam radius yang telah di tentukan!");
				document.getElementById('latitude').value = null;
				document.getElementById('longitude').value = null;
				marker.setPosition(center_rad);
			} else {
				center = event.latLng;
				map.setZoom(14);
				map.setCenter(center);

				document.getElementById('latitude').value = event.latLng.lat();
				document.getElementById('longitude').value = event.latLng.lng();

				if( marker ){
					marker.setPosition(center);
				} else {
					marker = new google.maps.Marker({
						position: event.latLng,
						map: this
					});
				}
			}
		});

		google.maps.event.addDomListener(loc, 'click', function(e) {
			e.preventDefault();

			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					var lat1 = center_rad.lat;
					var lng1 = center_rad.lng;
					var lat2 = position.coords.latitude;
					var lng2 = position.coords.longitude;
					var distanceInMeters = getDistanceBetweenPoints(lat1, lng1, lat2, lng2);

					if (distanceInMeters > radius_fix) {
						alert("Pastikan lokasi anda berada dalam radius yang telah di tentukan!");
						document.getElementById('latitude').value = null;
						document.getElementById('longitude').value = null;
						marker.setPosition(center_rad);
					} else {
						center = { lat: position.coords.latitude, lng: position.coords.longitude };
						map.setZoom(14);
						map.setCenter(center);

						document.getElementById('latitude').value = position.coords.latitude;
						document.getElementById('longitude').value = position.coords.longitude;

						if( marker ){
							marker.setPosition(center);
						} else {
							marker = new google.maps.Marker({
								position: center,
								map: this
							});
						}
					}
				}, function() {
					alert('Anda harus memberi izin untuk mengakses lokasi anda!');
				});
			} else {
				alert('geolocation failure!');
			}
		});
	}

	// Haversine Formula
	function degreesToRadians(degrees){
		return degrees * Math.PI / 180;
	}

	function getDistanceBetweenPoints(lat1, lng1, lat2, lng2){
		let R = 6378137;
		let dLat = degreesToRadians(lat2 - lat1);
		let dLong = degreesToRadians(lng2 - lng1);
		let a = Math.sin(dLat / 2)*Math.sin(dLat / 2)+Math.cos(degreesToRadians(lat1))*Math.cos(degreesToRadians(lat1))*Math.sin(dLong / 2)*Math.sin(dLong / 2);

		let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
		let distance = R * c;

		return distance;
	}

	$(document).ready(function() {
		$('[data-toggle1="tooltip"]').tooltip();
		$('#datatable').dataTable();

		$('#btn-edit-profil').click(function(event) {
			$('#edit-profil').removeAttr('hidden');
			$('#view-profil').attr('hidden', '');
		});

		$('#batal-update').click(function(event) {
			$('#edit-profil').attr('hidden', '');
			$('#view-profil').removeAttr('hidden');
		});

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