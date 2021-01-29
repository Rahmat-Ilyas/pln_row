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
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>

<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhUwwpTQamL6ZhPKT2x2j2nI-eWNR8bZ0&callback=initialize&libraries=&v=weekly"
defer></script>
<script>
	let map;
	let panorama;
	var marker;

	function initialize() {
		const berkeley = { lat: -5.1291464, lng: 119.4161042 };
		const sv = new google.maps.StreetViewService();
		panorama = new google.maps.StreetViewPanorama(
			document.getElementById("pano")
			);

		map = new google.maps.Map(document.getElementById("map"), {
			center: berkeley,
			zoom: 18,
			streetViewControl: false,
		});

		sv.getPanorama({ location: berkeley, radius: 50 }, processSVData);

		map.addListener("click", (event) => {
			sv.getPanorama({ location: event.latLng, radius: 50 }, processSVData);
		});

		panorama.addListener("position_changed", () => {
			marker.setPosition(panorama.getPosition());
			var lokasi = panorama.getPosition();
			$('#latitude').val(lokasi.lat());
			$('#longitude').val(lokasi.lng());
		});
	}

	function processSVData(data, status) {
		if (status === "OK") {
			const location = data.location;
			if (marker) {
				marker.setPosition(location.latLng);
			} else {
				marker = new google.maps.Marker({
					position: location.latLng,
					map,
					title: location.description,
				});
			}
			panorama.setPano(location.pano);
			panorama.setPov({
				heading: 270,
				pitch: 0,
			});
			panorama.setVisible(true);
		} else {
			console.error("Street View data not found for this location.");
		}
	}

	$(document).ready(function() {
		$('[data-toggle1="tooltip"]').tooltip();
		$('#datatable').dataTable();
	});
</script>

</body>
</html>