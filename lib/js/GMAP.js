
var	GMAPqueue = [], 
	GMAP,
	initializeGMAPs;

(function($) {

	var	empty = {
			init: function() { }
		},
		errors = {
			g: 'GMAP uses the Google Maps API. Make sure that this JS is available',
			l: 'GMAP expects locs (2nd arg) to be defined as an array/fn/src file.',
			nl: 'No locations in GMAP data'
		};

	GMAP = function(div, locs, mapOptions) {
		
		/*
			locs is either an array of locations, { title: , address: , latlng: LATLONG }
			or a function that returns an array of locations (if using ajax, make sure it is sync)
			the center of hte map will be the first location in the array
		*/

		if (typeof google.maps.Map == 'undefined') {
			
			GMAPqueue.push({
				div: div,
				locs: locs,
				mapOptions: mapOptions
			});

			return empty;
		}

		var $div = sky.getDivObject(div), data;

		// check requirements
		if (!$div) $.error('gmap expects a div to be given to it as a parameter');
		if (typeof google == 'undefined') $.error(errors.g);
		if (typeof locs == 'undefined') $.error(errors.l);
		
		mapOptions = mapOptions || {};
		data = {
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
			},
			stylers: [
		    	{ visibility: "off" }
		    ]   
		};

		return {
			init: function(callback) {

				// bind the data to this closure
				var cnt = { data: data, methods: this };
				
				data.locs = cnt.methods.getLocs();
				if (!data.locs) $.error(errors.nl);
				
				// extend mapOptions with configs	
				$.extend(data.mapOptions, data.params.mapOptions);

				data.mapOptions.center = data.mapOptions.center || data.locs[0].latlng;
				data.map = new google.maps.Map(data.containerELEMENT, data.mapOptions);
				data.mapBounds = new google.maps.LatLngBounds();

				// drop markers using callbacks
				$.each(data.locs, function(i, item) {
					if (!sky.call(cnt.data.params.mapOptions.setMarker, cnt, i, item)) {
						sky.call(cnt.methods.setMarker, cnt, i, item);
					} 
				});

				// set map bounds
				if (cnt.data.locs.length !== 1) {
					cnt.data.map.fitBounds(cnt.data.mapBounds);
				} 

				sky.call(callback, cnt, cnt);
				
			},
			getLocs: function() {
				var l = data.params.locs;
				if (!$.isArray(l) && typeof l !== 'function') $.error(errors.l);
				return ($.isArray(l)) ? l : l();
			},
			setMarker : function(i, item) {
				
				var	marker = new google.maps.Marker({
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
					
					var content, inside;

					inside = (loc.html) 
						? '<div class="gmaps-content-small-desc">' + loc.html + '</div>'
						: '';

					content = '<div class="gmaps-content">'
							+ '<div class="gmaps-content-title">' + loc.title + '</div>'
							+ inside
							+ '</div>';

					data.infoWindow = new google.maps.InfoWindow({ content: content })
									. open(data.map, marker);
					
				});
			}
		};	
	};


	initializeGMAPs = function() {
		$.each(GMAPqueue, function(i, m) {
			GMAP(m.div, m.locs, m.mapOptions).init();
		});
	}

})(jQuery);