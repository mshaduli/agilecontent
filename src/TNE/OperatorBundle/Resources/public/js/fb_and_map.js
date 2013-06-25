function showLargeMap(lat,lang){
        var mapViewOptions = {
            zoom: 8,
            center: new google.maps.LatLng(lat,lang),
            disableDefaultUI: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var mapViewLarge = new google.maps.Map(document.getElementById("map-view-large"), mapViewOptions);
        var mapViewLargeMarker = new google.maps.Marker({
            position: new google.maps.LatLng(lat,lang),
            map: mapViewLarge,
            icon: '/bundles/tneoperator/img/map/marker_blue.png'
        });
        
        var contentString = '<div id="content" class="map-address-popup">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">Uluru</h1>'+
      '<div id="bodyContent">'+
      'test'+
      '</div>'+
      '</div>';

  var infowindow = new google.maps.InfoWindow({
      content: contentString,
      disableAutoPan: true
  });
  
  google.maps.event.addListener(mapViewLargeMarker, 'click', function() {
    infowindow.open(mapViewLarge,mapViewLargeMarker);
  });
    }