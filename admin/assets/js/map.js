



$('#modal-success').on('show.bs.modal', function(event) {
  var stationlist = [];
  var j = stationlist.length;
  var res = $('#input_edit').val();
  if(res!=''){
    console.log("im here")
    var stationlist = JSON.parse(res);
    j = stationlist.length;
    console.log(j);

    $('#direct_div').css('display','block');

  }
  console.log("called modal");

  $('#driven').html('<div id="bar"><p class="auto"><input type="text" id="autoc"/></p><p><a id="clear" href="#">Clear Map</a></div>');
     //initialize();
     //liveSnapToRoad();
      initialize();
      liveSnapToRoad();
      console.log(stationlist);
      var default_mark = {
          "lat": -33.866419380463505,
          "lng": 151.1950064735413
      };
      if(stationlist.length>0){
        map_marker();
        set_metrix(stationlist); 
        var default_mark = {
          "lat": $("#station_lat_1").val(),
          "lng": $("#station_lng_1").val()
        };       
      }
      
});



var apiKey = 'AIzaSyBKtzIz99JEgz4ltmLmlwXXdGwsyDa7a40';

var map;
var drawingManager;
var placeIdArray = [];
var polylines = [];
var snappedCoordinates = [];
var all_overlays = [];
var gmarkers = [];
var pathValues = [];
if(stationlist==undefined){
  var stationlist = [];
}


//initialize();
function initialize() {
    if($("#station_lat_1").val()!=undefined){
      var lat = parseFloat($("#station_lat_1").val()).toFixed(4);
      var lng = parseFloat($("#station_lng_1").val()).toFixed(4);
      lat = Number(lat);
      lng = Number(lng);
    } else {
      var lat = 30.5852;
      var lng = 36.2384;
    }
    var mapOptions = {
        zoom: 17,
        center: {
            lat: lat,
            lng: lng
        }
    };
    console.log(mapOptions);
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    var geocoder = new google.maps.Geocoder();
    // Adds a Places search box. Searching for a place will center the map on that
    // location.
    map.controls[google.maps.ControlPosition.RIGHT_TOP].push(
        document.getElementById('bar'));
    var autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('autoc'));
    autocomplete.bindTo('bounds', map);
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
    });

    // Enables the polyline drawing control. Click on the map to start drawing a
    // polyline. Each click will add a new vertice. Double-click to stop drawing.
    drawingManager = new google.maps.drawing.DrawingManager({
        drawingMode: google.maps.drawing.OverlayType.POLYLINE,
        drawingControl: true,
        drawingControlOptions: {
            position: google.maps.ControlPosition.TOP_CENTER,
            drawingModes: [
                google.maps.drawing.OverlayType.POLYLINE,
                google.maps.drawing.OverlayType.MARKER
            ]
        },
        polylineOptions: {
            strokeColor: '#696969',
            strokeWeight: 2
        },
        markerOptions: {
            icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
        },
    });
    drawingManager.setMap(map);

    // Snap-to-road when the polyline is completed.
    drawingManager.addListener('polylinecomplete', function(poly) {
        var path = poly.getPath();
        polylines.push(poly);
        placeIdArray = [];
        runSnapToRoad(path);
    });


    google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
        console.log(e);
        all_overlays.push(e);
    });

    drawingManager.addListener('markercomplete', function(mark) {
        var lat = mark.getPosition().lat();
        var lng = mark.getPosition().lng();
        /*if(stationlist==undefined){
          var stationlist = [];
        }*/
          j = stationlist.length+1;
          console.log(stationlist);
        var latlng = {
            "lat": lat,
            "lng": lng
        };
        var station = {
            "location": "",
            "latlng": latlng,
            "distance": "",
            "duration": "",
            "arrival_time":"",
            "departure_time":"",
            "index":j,
            "id":""
        };

        geocoder.geocode({
            'latLng': latlng
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    station.location = results[0].formatted_address;
                    stationlist.push(station);
                    console.log(stationlist);
                    map_marker();
                    calculate_distance(stationlist);
                }
            } else {
                alert("error");
            }
        });

        

        //station.latlng = lat+","+lng;

        //console.log(title);
        
        
        console.log(stationlist);
    });

    var markersArray = [];
    // Clear button. Click to remove all polylines.
    $('#clear').click(function(ev) {
        pathValues = [];
        stationlist = [];
        j = 0;
        $(".static").remove();
        for (var i = 0; i < polylines.length; ++i) {
            polylines[i].setMap(null);
        }
        polylines = [];

        for (var i = 0; i < all_overlays.length; i++) {
            all_overlays[i].overlay.setMap(null);
        }
        all_overlays = [];
        for (i = 0; i < gmarkers.length; i++) {
            gmarkers[i].setMap(null);
        }
        $("#direct_div").css('display','none');
        ev.preventDefault();
        return false;
    });

    $('.rm_tag').click(function(){
      console.log('called');
      console.log(gmarkers);
    })



    

    /*var myLatlng = new google.maps.LatLng(-33.866419380463505, 151.1950064735413);
    var marker = new google.maps.Marker({
        position: default_mark, //myLatlng,
        map: map,
        title: 'Hello World!',
        label: 'my word',

        icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
    });

    gmarkers.push(marker);*/


}


function calculate_distance(stationlist) {
    $("#direct_div").css('display','block');
    if (stationlist.length > 1) {
        var origin1 = stationlist[stationlist.length - 1].latlng;
        var destinationA = stationlist[stationlist.length - 2].latlng;
        var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
            origins: [origin1],
            destinations: [destinationA],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false
        }, function(response, status) {
            if (status !== 'OK') {
                alert('Error was: ' + status);
            } else {
                console.log(response.rows[0].elements[0]);
                stationlist[stationlist.length - 1].distance = response.rows[0].elements[0].distance.text;
                stationlist[stationlist.length - 1].duration = response.rows[0].elements[0].duration.text;
            }
        });
    } else {
        stationlist[stationlist.length - 1].distance = 0;
        stationlist[stationlist.length - 1].duration = 0;
    }
    var key = stationlist.length - 1;
    setTimeout(function(){
      set_metrix(stationlist);
    },500);  
    //var origin1 = {lat: 10.0159, lng: 76.3419};
    //var destinationA = {lat: 10.0237, lng: 76.3116}; 

    
}


function set_metrix(stationlist=null){  
    $(".static").remove();
    stationlist.forEach(function(item,index_id) {
    $('#myTable tr:last').after('<tr class="static"><td scope="row">'+item.index+'</td><td><input type="text" id="location_'+item.index+'" name="location[]" value="'+item.location+'"/></td><td>'+item.latlng.lat+'</td><td>'+item.latlng.lng+'</td><td><input type="number" id="index'+item.index+'" name="index[]" value="'+item.index+'"/></td><td>'+item.duration+'</td><td>'+item.distance+'</td><td><i class="fa fa-fw fa-trash-o" onclick="remove_item('+index_id+')" class="rm_tag"></i></td></tr>');
  })  
}

function remove_item(key){

  for(var k=key;k<stationlist.length;k++){
    stationlist[k].index = parseInt(stationlist[k].index) - 1;
  }
  
  stationlist.splice(key,1);
  if(stationlist.length>0){
    calculate_distance(stationlist);
    map_marker();
    j = stationlist.length;
  } else {
    $(".static").remove();
    map_marker();
    j = 0;
    $("#direct_div").css('display','none');
  }
  
}

// Snap a user-created polyline to roads and draw the snapped path
function runSnapToRoad(path) {
  var array = []
    pathValues = pathValues.length==0?array:pathValues;
    for (var i = 0; i < path.getLength(); i++) {
        pathValues.push(path.getAt(i).toUrlValue());
    }

    pathValues = cleanArray(pathValues);
    console.log(pathValues);

    $.get('https://roads.googleapis.com/v1/snapToRoads', {
        interpolate: true,
        key: apiKey,
        path: pathValues.join('|')
    }, function(data) {
        processSnapToRoadResponse(data);
        drawSnappedPolyline();
        getAndDrawSpeedLimits();
    });

    for (var i = 0; i < polylines.length; ++i) {
        polylines[i].setMap(null);
    }
    polylines = [];
}

function cleanArray(actual) {
  var newArray = new Array();
  for (var i = 0; i < actual.length; i++) {
    if (actual[i]!="") {
      newArray.push(actual[i]);
    }
  }
  return newArray;
}




// Store snapped polyline returned by the snap-to-road service.
function processSnapToRoadResponse(data) {
    snappedCoordinates = [];
    placeIdArray = [];
    for (var i = 0; i < data.snappedPoints.length; i++) {
        var latlng = new google.maps.LatLng(
            data.snappedPoints[i].location.latitude,
            data.snappedPoints[i].location.longitude);
        snappedCoordinates.push(latlng);
        placeIdArray.push(data.snappedPoints[i].placeId);
    }
}

// Draws the snapped polyline (after processing snap-to-road response).
function drawSnappedPolyline() {
    var snappedPolyline = new google.maps.Polyline({
        path: snappedCoordinates,
        strokeColor: 'black',
        strokeWeight: 3
    });

    snappedPolyline.setMap(map);
    polylines.push(snappedPolyline);
}

// Gets speed limits (for 100 segments at a time) and draws a polyline
// color-coded by speed limit. Must be called after processing snap-to-road
// response.
function getAndDrawSpeedLimits() {
    for (var i = 0; i <= placeIdArray.length / 100; i++) {
        // Ensure that no query exceeds the max 100 placeID limit.
        var start = i * 100;
        var end = Math.min((i + 1) * 100 - 1, placeIdArray.length);

        drawSpeedLimits(start, end);
    }
}

// Gets speed limits for a 100-segment path and draws a polyline color-coded by
// speed limit. Must be called after processing snap-to-road response.
function drawSpeedLimits(start, end) {
    var placeIdQuery = '';
    for (var i = start; i < end; i++) {
        placeIdQuery += '&placeId=' + placeIdArray[i];
    }

    $.get('https://roads.googleapis.com/v1/speedLimits',
        'key=' + apiKey + placeIdQuery,
        function(speedData) {
            processSpeedLimitResponse(speedData, start);
        }
    );
}

// Draw a polyline segment (up to 100 road segments) color-coded by speed limit.
function processSpeedLimitResponse(speedData, start) {
    var end = start + speedData.speedLimits.length;
    for (var i = 0; i < speedData.speedLimits.length - 1; i++) {
        var speedLimit = speedData.speedLimits[i].speedLimit;
        var color = getColorForSpeed(speedLimit);

        // Take two points for a single-segment polyline.
        var coords = snappedCoordinates.slice(start + i, start + i + 2);

        var snappedPolyline = new google.maps.Polyline({
            path: coords,
            strokeColor: color,
            strokeWeight: 6
        });
        snappedPolyline.setMap(map);
        polylines.push(snappedPolyline);
    }
}

function getColorForSpeed(speed_kph) {
    if (speed_kph <= 40) {
        return 'purple';
    }
    if (speed_kph <= 50) {
        return 'blue';
    }
    if (speed_kph <= 60) {
        return 'green';
    }
    if (speed_kph <= 80) {
        return 'yellow';
    }
    if (speed_kph <= 100) {
        return 'orange';
    }
    return 'red';
}




function liveSnapToRoad() {



    var path_string = $("#root_map").val();
    console.log(path_string);

    pathValues = path_string.split('#');

    console.log(pathValues);
    if(pathValues.length!==0){
      $.get('https://roads.googleapis.com/v1/snapToRoads', {
          interpolate: true,
          key: apiKey,
          path: pathValues.join('|')
      }, function(data) {
          processSnapToRoadResponse(data);
          drawSnappedPolyline();
          getAndDrawSpeedLimits();
      });
    } else {
      pathValues = [];
    }

    

}

$("#save_record").on('click',function(){
  
  $(".dynamic").remove();
  var station = $("input[name='location[]']").map(function(){return $(this).val();}).get();
  var index = $("input[name='index[]']").map(function(){return $(this).val();}).get();  
  $('#root_map').val(pathValues.join("#"));
  stationlist.forEach(function(item,index_id) {
    console.log(station[index_id]);
    console.log(item);
    item.location = station[index_id];
    item.index = index[index_id];
  });
  stationlist = stationlist.sort(keysrt('index'));
  distance_matrix(stationlist); 

})

function map_marker(){
  console.log(stationlist);
  for (var i = 0; i < all_overlays.length; i++) {
      all_overlays[i].overlay.setMap(null);
  }
  all_overlays = [];
  for (i = 0; i < gmarkers.length; i++) {
      gmarkers[i].setMap(null);
  }

  var myLatlng = new google.maps.LatLng(-33.866419380463505,151.1950064735413);
  stationlist.forEach(function(item) {
     var marker = new google.maps.Marker({
       position: item.latlng,//myLatlng,
       map: map,
       title: item.location+" - "+item.index,
   
       icon: 'https://developers.google.com/maps/documentation/javascript/examples/full/images/beachflag.png'
     });

     gmarkers.push(marker);
  })
}

function distance_matrix(stationlist){
  console.log(stationlist);
  for(var k=0;k<stationlist.length-1;k++){
  if (k >= 1) {
        console.log(stationlist[k]);
        var origin1 = stationlist[k].latlng;
        var destinationA = stationlist[k - 1].latlng;
        var service = new google.maps.DistanceMatrixService;
        service.getDistanceMatrix({
            origins: [origin1],
            destinations: [destinationA],
            travelMode: 'DRIVING',
            unitSystem: google.maps.UnitSystem.METRIC,
            avoidHighways: false,
            avoidTolls: false
        }, function(response, status) {
            if (status !== 'OK') {
                alert('Error was: ' + status);
            } else {
                stationlist[k].distance = response.rows[0].elements[0].distance.text;
                stationlist[k].duration = response.rows[0].elements[0].duration.text;
            }
        });
    } else {
        stationlist[k].distance = 0;
        stationlist[k].duration = 0;
    }
    
    

  }
  console.log(stationlist);

  var timer = setInterval(function(){
    console.log('called');
    if(k==stationlist.length-1){
      clearInterval(timer);
      setTimeout(function(){
        stationlist.forEach(function(item,index_id) {
          
          $('#main_station_table tr:last').after('<tr class="dynamic"><td scope="row"><input type="hidden" id="id_'+index_id+'" name="ids[]" value="'+item.id+'"/><input type="text" id="location_'+index_id+'" name="location[]" value="'+item.location+'"/></td><td><input type="hidden" id="station_lat_'+index_id+'" name="station_lat[]" value="'+item.latlng.lat+'"/>'+item.latlng.lat+'</td><td><input type="hidden" id="station_lng_'+index_id+'" name="station_lng[]" value="'+item.latlng.lng+'"/>'+item.latlng.lng+'</td><td><input type="number" id="index_'+index_id+'" name="index[]" value="'+item.index+'"/></td><td><input type="text" id="arrival_time_'+index_id+'" name="arrival_time[]" value="'+item.arrival_time+'" class="timepicker"/></td><td><input type="text" id="departure_time_'+index_id+'" name="departure_time[]" value="'+item.departure_time+'" class="timepicker" /></td><td><input type="hidden" id="duration_'+index_id+'" name="duration[]" value="'+item.duration+'"/>'+item.duration+'</td><td><input type="hidden" id="distance_'+index_id+'" name="distance[]" value="'+item.distance+'"/>'+item.distance+'</td></tr>');
            
          });
        $(".timepicker").timepicker();
        $('#main_station').show('slow');
        $('#modal-success').modal('toggle');

      },500)
    }
  },100)
}

function keysrt(key) {
  return function(a,b){
   if (a[key] > b[key]) return 1;
   if (a[key] < b[key]) return -1;
   return 0;
  }
}





//initialize();