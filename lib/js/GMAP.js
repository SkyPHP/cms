var GMAP = function(div, locs, mapOptions) {
	/*
		locs is either an array of locations, { title: , address: , latlng: LATLONG }
		or a function that returns an array of locations (if using ajax, make sure it is sync)
		the center of hte map will be the first location in the array
	*/
	var $div = aql._getDivObject(div);
	if (!$div) {
		$.error('gmap expects a div to be given to it as a parameter');
		return;
	}
	if (typeof google == 'undefined') {
		$.error('gmap uses the google maps api, make sure that the javascript is present');
		return;
	}
	if (typeof locs == 'undefined') {
		$.error('gmap expects locations to be defined, either by an array, a remote source file, or a callback function');
		return;
	}
	
	if (!mapOptions) mapOptions = {};
	var data = {
			container: $div,
			containerELEMENT: $div.get(0),
			infoWindow: null,
			params: {
				locs: locs,
				mapOptions: mapOptions
			},
			mapOptions: {
				zoom: 17,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: false,
				disableDefaultUI: false
			}
		};
	return  {
		init: function() {
			var cnt = { data: data, methods: this };
			data.locs = cnt.methods.getLocs();
			if (!data.locs) {
				$.error('no locations in GMAP data');
				return;
			} 
			$.extend(data.mapOptions, data.params.mapOptions);			
			if (!data.mapOptions.center) data.mapOptions.center = data.locs[0].latlng;
			data.map = new google.maps.Map(data.containerELEMENT, data.mapOptions);
			data.mapBounds = new google.maps.LatLngBounds();
			$.each(data.locs, function(i, item) {
				if (!aql._callback(cnt.data.params.mapOptions.setMarker, cnt, i, item)) {
					aql._callback(cnt.methods.setMarker, cnt, i, item);
				} 
			});
			if (cnt.data.locs.length == 1) {
				
			} else {
				cnt.data.map.fitBounds(cnt.data.mapBounds);
			}
			
		},
		getLocs: function() {
			if ($.isArray(data.params.locs)) return data.params.locs;
			else if (typeof data.params.locs == 'function') return data.params.locs();
			else {
				$.error('Locs parameter was invalid in GMAP');
				return null;
			}
		},
		setMarker : function(i, item) {
			var marker = new google.maps.Marker({
				map: this.data.map,
				draggable: false,
				animation: google.maps.Animation.DROP,
				position: item.latlng,
				title: item.title
			});
			this.data.mapBounds.extend(marker.position);
			this.methods.setMarkerClick(marker, item);
		},
		setMarkerClick: function(marker, loc) {
			google.maps.event.addListener(marker, 'click', function() {
				if (data.infoWindow) data.infoWindow.close();
				var content = loc.title;
				if (loc.address) content += '<div class="small-desc">' + loc.html + '</div>';
				data.infoWindow = new google.maps.InfoWindow({
					content: content
				});
				data.infoWindow.open(data.map, marker);
			});
		}
	};	
};