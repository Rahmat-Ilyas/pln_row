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


<script src="../assets/js/jquery.core.js"></script>
<script src="../assets/js/jquery.app.js"></script>
<script src="../assets/plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhUwwpTQamL6ZhPKT2x2j2nI-eWNR8bZ0&callback=initialize&libraries=&v=weekly"
defer></script>
<script>
	$(document).ready(function() {
		$('[data-toggle1="tooltip"]').tooltip();
		// $('#datatable').dataTable();
	});

	var latitude = document.getElementById('latitude').value;
	var longitude = document.getElementById('longitude').value;
	var lokasi_center = document.getElementById('lokasi_center').value;
	var luas_daerah = document.getElementById('luas_daerah').value;
	var radius = document.getElementById('radius').value;
	var marker;

	// Rumus Radius/Jari-Jari
	var ls = luas_daerah*7/22;
	var radius_fix = Math.sqrt(ls * 1e+6);

	// Rumus Luas 
	var luas_fix = 3.14 * radius_fix * radius_fix;

	function initialize() {
		var lsd = document.getElementById('luas_daerah');
		var rad = document.getElementById('radius');
		var center = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
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
			center: center,
			radius: radius_fix,
		});

		google.maps.event.addDomListener(lsd, 'keyup', function(e) {
			luas_daerah = this.value;
			ls = luas_daerah*7/22;
			radius_fix = Math.sqrt(ls * 1e+6);
			rad.value = (radius_fix/1000).toFixed(2);
			cityCircle.setMap(null);
			marker.setPosition(center);
			cityCircle = new google.maps.Circle({
				strokeColor: "#81c868",
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "#81c868",
				fillOpacity: 0.35,
				clickable: false,
				map,
				center: center,
				radius: radius_fix,
			});
		});

		google.maps.event.addDomListener(rad, 'keyup', function(e) {
			radius = this.value;
			luas_daerah = 3.14 * radius * radius;
			ls = luas_daerah*7/22;
			radius_fix = Math.sqrt(ls * 1e+6);
			lsd.value = luas_daerah.toFixed(2);
			cityCircle.setMap(null);
			marker.setPosition(center);
			cityCircle = new google.maps.Circle({
				strokeColor: "#81c868",
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "#81c868",
				fillOpacity: 0.35,
				clickable: false,
				map,
				center: center,
				radius: radius_fix,
			});
		});

		google.maps.event.addListener(map, 'click', function(event) {
			center = event.latLng;
			document.getElementById('latitude').value = event.latLng.lat();
			document.getElementById('longitude').value = event.latLng.lng();
			cityCircle.setMap(null);
			cityCircle = new google.maps.Circle({
				strokeColor: "#81c868",
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: "#81c868",
				fillOpacity: 0.35,
				clickable: false,
				map,
				center: event.latLng,
				radius: radius_fix,
			});

			if( marker ){
				marker.setPosition(center);
			} else {
				marker = new google.maps.Marker({
					position: event.latLng,
					map: this
				});
			}
		});
	}
</script>

</body>
</html>