var map;
var batch = [];
var localSearch ;
var markerArr;
var mgr;

var flyingSpeed = 25;
var url_addProductToBasket = 'addProduct.php';
var url_removeProductFromBasket = 'removeProduct.php';
var txt_totalPrice = 'Total: ';
var shopping_cart_div = false;
var flyingDiv = false;
var currentProductDiv = false;
var shopping_cart_x = false;
var shopping_cart_y = false;
var slide_xFactor = false;
var slide_yFactor = false;
var diffX = false;
var diffY = false;
var currentXPos = false;
var currentYPos = false;

var propDetailsUpadted = false;
var movedToNextProp = false;
var reverseMarker ='';
var	infodataHTML="";


function usePointFromPostcode(postcode, callbackFunction) {
	localSearch = new GlocalSearch();
	localSearch.setSearchCompleteCallback(null, 
		function() {
			
			if (localSearch.results[0])
			{		
				var resultLat = localSearch.results[0].lat;
				var resultLng = localSearch.results[0].lng;
				var county = localSearch.results[0].region;
				var city = localSearch.results[0].city;
				document.getElementById('form_addpropertymap_city_ID').value =  city;
				document.getElementById('form_addpropertymap_county_ID').value =  county;
				var point = new GLatLng(resultLat,resultLng);
				
				callbackFunction(point, postcode);
			}else{
				alert("Postcode not found through automated search!Please drag the pointer to exact property location.");
				if(document.getElementById('error_div_id'))
					document.getElementById('error_div_id').innerHTML = "<span style='color:red'>Postcode not found through automated search!Please drag the pointer to exact property location.</span>"
				var resultLat = 51.471985;
				var resultLng = -0.355582;
				var point = new GLatLng(resultLat,resultLng);
				
				callbackFunction(point, "TW3 1PA");
				
			}
		});	
		
	localSearch.execute(postcode + ", UK");
}
function editPropMap(lat,long,post)
{
	var resultLat = lat;
	var resultLng = long;
	var point = new GLatLng(resultLat,resultLng);
	mapLoad(point, post);
}

function placeMarkerAtPoint(point, postcode)
{
	var icon = new GIcon(G_DEFAULT_ICON);
	icon.image = "http://www.google.com/mapfiles/marker.png";
	icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
	icon.iconSize = new GSize(20, 34);
	icon.shadowSize = new GSize(37, 34);
	icon.iconAnchor = new GPoint(10, 34);
					
	// Set up our GMarkerOptions object
	markerOptions = { icon:icon, draggable: true};

	var marker = new GMarker(point,markerOptions);
	document.getElementById("form_addpropertymap_latitudes_ID").value = point.lat();
	document.getElementById("form_addpropertymap_longitude_ID").value = point.lng();
	GEvent.addListener(marker, "click", function() {

    marker.openInfoWindowHtml("<b>Please set property location using this marker.<br/>This is very important because initially marker may not be<br/> pointing to exact location of property.</b>");
  });


	GEvent.addListener(marker, "dragstart", function() {
          map.closeInfoWindow();
        });

    GEvent.addListener(marker, "dragend", function() {
			var point = marker.getLatLng();
			document.getElementById("form_addpropertymap_latitudes_ID").value = point.lat();
			document.getElementById("form_addpropertymap_longitude_ID").value = point.lng();
			showPanorma(point);
			//getAddress(point);
			//marker.openInfoWindowHtml();
        });

	map.addOverlay(marker);
	marker.openInfoWindowHtml("<b>Please set property location using this marker.<br/>This is very important because initially marker may not be<br/> pointing to exact location of property.</b>");
}


function placeReverseMarkerAtPoint(point, postcode)
{
	var icon = new GIcon(G_DEFAULT_ICON);
	icon.image = "images/marker.png";
	icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
	icon.iconSize = new GSize(20, 34);
	icon.shadowSize = new GSize(37, 34);
	icon.iconAnchor = new GPoint(10, 34);
					
	// Set up our GMarkerOptions object
	markerOptions = { icon:icon, draggable: true};
	point = new GLatLng(point.lat(),point.lng()+.001);
	reverseMarker = new GMarker(point,markerOptions);
GEvent.addListener(reverseMarker, "click", function() {

    reverseMarker.openInfoWindowHtml(infodataHTML);
  });
	GEvent.addListener(reverseMarker, "dragstart", function() {
          map.closeInfoWindow();
        });

    GEvent.addListener(reverseMarker, "dragend", function() {
			var point = reverseMarker.getLatLng();
//			showPanorma(point);
			getAddress(point);
			//marker.openInfoWindowHtml();
        });

	map.addOverlay(reverseMarker);
	getAddress(point);
//	reverseMarker.openInfoWindowHtml(postcode);
}



    function getAddress( latlng) {
      if (latlng != null) {
        var geocoder1 = new GClientGeocoder();
        geocoder1.getLocations(latlng, showReverseAddress);
      }
    }

    function showReverseAddress(response) {
     // map.clearOverlays();
      if (!response || response.Status.code != 200) {
        alert("Status Code:" + response.Status.code);
      } else {
        place = response.Placemark[0];
        point = new GLatLng(place.Point.coordinates[1],
                            place.Point.coordinates[0]);
		
//        marker = new GMarker(point);
  //      map.addOverlay(marker);
infodataHTML = "";
    if(place.AddressDetails.Country.AdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.ThoroughfareName){
  			var st = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.ThoroughfareName;
//			tmp = st.substring(0,1); 
			st = st.replace(/^\s*/, "").replace(/\s*$/, "");

			if ("0123456789".indexOf(st.charAt(0)) != -1)
			 {
				var indx = st.indexOf(" ");
				st = st.substring(indx,st.length);
				st = st.replace(/^\s*/, "").replace(/\s*$/, "");
			 }
			document.getElementById('form_addpropertymap_street_ID').value = st;
						        infodataHTML +='<b>Street Name:</b>' + place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.Thoroughfare.ThoroughfareName + '<br>' ;

			} else if(place.AddressDetails.Country.AdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality.Thoroughfare && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality.Thoroughfare.ThoroughfareName){
  			var st = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality.Thoroughfare.ThoroughfareName;
//			tmp = st.substring(0,1); 
			st = st.replace(/^\s*/, "").replace(/\s*$/, "");

			if ("0123456789".indexOf(st.charAt(0)) != -1)
			 {
				var indx = st.indexOf(" ");
				st = st.substring(indx,st.length);
				st = st.replace(/^\s*/, "").replace(/\s*$/, "");
			 }
			document.getElementById('form_addpropertymap_street_ID').value = st;
						        infodataHTML +='<b>Street Name:</b>' + place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality.Thoroughfare.ThoroughfareName + '<br>' ;

			}

  if(place.AddressDetails.Country.AdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName){
  			document.getElementById('form_addpropertymap_locality_ID').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName;
							infodataHTML +='<b>Locality:</b>' + place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.Locality.LocalityName + '<br>' ;
  } else if(place.AddressDetails.Country.AdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality.DependentLocalityName){
  			document.getElementById('form_addpropertymap_locality_ID').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality.DependentLocalityName;
							infodataHTML +='<b>Locality:</b>' + place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.DependentLocality.DependentLocalityName + '<br>' ;
  }

			if(place.AddressDetails.Country.AdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea && place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.SubAdministrativeAreaName){
			document.getElementById('form_addpropertymap_subadmin_ID').value = place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.SubAdministrativeAreaName;
		infodataHTML +='<b>Sub Administrative Area Name:</b>' + place.AddressDetails.Country.AdministrativeArea.SubAdministrativeArea.SubAdministrativeAreaName + '<br>'	;
			
			}
			if(place.AddressDetails.Country.AdministrativeArea && place.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName){
			document.getElementById('form_addpropertymap_admin_ID').value = place.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName;
				infodataHTML +='<b>Administrative Area Name:</b>' + place.AddressDetails.Country.AdministrativeArea.AdministrativeAreaName + '<br>' ;}
		if(place.AddressDetails.Country && place.AddressDetails.Country.CountryNameCode)
        infodataHTML +='<b>Country code:</b> ' + place.AddressDetails.Country.CountryNameCode;
		if(place && place.address)
		infodataHTML +='<br/><b>Full Address:</b>' + place.address + '<br>' ;
		reverseMarker.openInfoWindowHtml(infodataHTML);

      }
    }


function setCenterToPoint(point)
{
	map.setCenter(point, 5);
}

function showPointLatLng(point)
{
	alert("Latitude: " + point.lat() + "\nLongitude: " + point.lng());
}

function mapLoad(point, postcode) {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(point, 17, G_NORMAL_MAP);
		//placeReverseMarkerAtPoint(point, postcode);
		placeMarkerAtPoint(point, postcode);
		showPanorma(point);
	}
}

function mapLoadAndMark(pointArr,statusSpanID) {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		var point = new GLatLng(pointArr[0].lat,pointArr[0].lon);
		map.setCenter(point, 17, G_NORMAL_MAP);
		var openMarker = true;
		markerArr = new Array();
		for(var i=0;i<pointArr.length;i++){
					batch.push(addMarkerWithDetail(map, pointArr[i], openMarker, i));
					openMarker = false;
		}
		mgrOptions = { borderPadding: 0, trackMarkers: true };
        mgr = new MarkerManager(map, mgrOptions);
		mgr.addMarkers(batch, 0);
	    mgr.refresh();
		GEvent.addListener(map, "moveend", function(bounds,  markerCount) {
														   numberOfPropShow(statusSpanID);
													});
		GEvent.addListener(map, "dragend", function(bounds,  markerCount) {
														   numberOfPropShow(statusSpanID);
													});
		GEvent.addListener(map, "zoomend", function(bounds,  markerCount) {
														   numberOfPropShow(statusSpanID);
													});
		
		//showPanorma(point);
		GEvent.addListener(map, "infowindowopen", function() {
														   callFlyProp();
													});
}
}

function showPropOnMap(pointArr) {
	if (GBrowserIsCompatible()) {
		map = new GMap2(document.getElementById("map"));
		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		var point = new GLatLng(pointArr[0].lat,pointArr[0].lon);
		map.setCenter(point, 17, G_NORMAL_MAP);
		var openMarker = true;
		pointElement = pointArr[0];
        var marker = new GMarker(point);
		map.addOverlay(marker);
        marker.openInfoWindowHtml(pointElement.desc);
	}
}
function showPropOnStreetView(pointArr) {
	if (GBrowserIsCompatible()) {
		var point = new GLatLng(pointArr[0].lat,pointArr[0].lon);
		panoramaOptions = { latlng:point, features: {  streetView: true,    userPhotos: false  }};
		var myPano = new GStreetviewPanorama(document.getElementById("streetViewDivId"), panoramaOptions);
	}
}



function numberOfPropShow(statusSpanID){
		var bound = map.getBounds() ;
		var count = 0;
		for(var i=0;i<batch.length;i++){
					if(bound.containsLatLng(batch[i].getLatLng() ))
					count = count + 1;
		}
		document.getElementById(statusSpanID).innerHTML = count;
}
function callFlyProp()
{
	if(propDetailsUpadted == false && movedToNextProp == true)
	{
		setTimeout('callFlyProp()',50);
	} 
	else if(propDetailsUpadted == true && movedToNextProp == true)
	{
		flyToPropertyDeatils('markerdivid663343', 'propertyDetailForMap');
		propDetailsUpadted = false;
		movedToNextProp = false;
	}
}

function showAddress(address) {
	var geocoder = new GClientGeocoder();
  geocoder.getLatLng(
    address,
    function(point) {
      if (!point) {
        alert(address + " not found");
      } else {
        map.setCenter(point, 13);
        var marker = new GMarker(point);
        map.addOverlay(marker);
        marker.openInfoWindowHtml(address);
      }
    }
  );
}

function showPanorma(point){
	panoramaOptions = { latlng:point, features: {  streetView: true,    userPhotos: false  }};
	var myPano = new GStreetviewPanorama(document.getElementById("pano"), panoramaOptions);
}


 function addMarkerWithDetail(map, pointElement, openMarker, markerIndex) {
      if (GBrowserIsCompatible()) {
		var point = new GLatLng(pointElement.lat,pointElement.lon);
        var marker = new GMarker(point);
		if(markerIndex != null)
			markerArr[markerIndex] = marker;
        //map.addOverlay(marker);
		if(openMarker){
        marker.openInfoWindowHtml(pointElement.desc);
		}
										  
         GEvent.addListener(marker, 'click', function(point) {
														var cafeIcon = new GIcon();
														cafeIcon.image = "http://gesoftlabs.com/real/images/marker_visited.png";
														cafeIcon.shadow = "";
														cafeIcon.iconSize = new GSize(16, 16);
														cafeIcon.shadowSize = new GSize(22, 20);
														cafeIcon.iconAnchor = new GPoint(8, 8);
														markerOptions = { icon:cafeIcon };
														map.addOverlay(new GMarker(point, markerOptions));
														marker.openInfoWindowHtml(pointElement.desc);
														GEvent.clearListeners(marker, 'click');
														GEvent.addListener(marker, 'click', function() {marker.openInfoWindowHtml(pointElement.desc);});
							/*		,
            {maxContent: maxContentDiv, 
             maxTitle: "More Info"});

          var iw = map.getInfoWindow();
          GEvent.addListener(iw, "maximizeclick", function() 
												   { GDownloadUrl(
																  pointElement.photo, function(data) 
																	{
																		maxContentDiv.innerHTML = data;
																	}
																 );
                                                   }
							);*/
        });
		
		return marker;
      }
    }
function calcDist(lat1,lon1,lat2,lon2){
	var R=6371;
	var d=Math.acos(Math.sin(lat1)*Math.sin(lat2)+
	Math.cos(lat1)*Math.cos(lat2)*Math.cos(lon2-lon1))*R;
	return d;
}

function nextNearestProperty(propIndex, propArr){
	var minDist=999999999999999999;
	var nextPropIndex;
	for(var i=0;i<propArr.length;i++)
	{
		if(!propArr[i].visited&&propIndex!=i)
		{
			var dist=calcDist(propArr[propIndex].lat,propArr[propIndex].lon,propArr[i].lat,propArr[i].lon);
			if(dist<minDist)
			{
				minDist=dist;
				nextPropIndex=i;
			}
		}
	}
	return nextPropIndex;
}
function visitNextProperty(propArr){
	propDetailsUpadted = false; //this variable controls start of flying
	var presentProperty = presentPropCounter;
	var nextNearProp = nextNearestProperty(presentProperty, propArr);
	if(nextNearProp == null){
		alert("You have visited all the properties.");
		return;
	}
	var newPoint = new GLatLng(propArr[nextNearProp].lat,propArr[nextNearProp].lon);
	var polyline = new GPolyline(
								 [
								  new GLatLng(propArr[presentProperty].lat, propArr[presentProperty].lon),
								  newPoint
								 ], "#ff0000", 5);
	map.closeInfoWindow();
	map.panTo(newPoint);
	map.addOverlay(polyline);
	propArr[presentProperty].visited = true;
    mytime = setTimeout(function(){markerArr[nextNearProp].openInfoWindowHtml(propArr[nextNearProp].desc)},900)
	
	//Spry.Utils.updateContent('propertyDetailForMap',"propertyDetailForMap.php?propertyId"+propArr[nextNearProp].id+"&sourcedivID=markerdivid663343");
	presentPropCounter = nextNearProp;
	movedToNextProp = true;
}
function GEviewProperty(reverse){
	ge.setBalloon(null);
	if(order_by!="latitude")
	{
		if(property_counter<0)
		{
			property_counter=0;
			var last_prop=property_counter;
		}
		else
		{
			var last_prop=property_counter;
			property_counter=nextClosestProperty(property_counter);
		}

		props[property_counter].visited=true;
	}
	else
	{
		if(property_counter>=props.length-1||property_counter<0)
		{
			if(reverse)
			{
				property_counter--;
			}
			else
			{
				property_counter=0;
				for(var i=0;i<ge_line_styles.length;i++)
				{
					ge_line_styles[i].getColor().set('0000f0ff');
				}
				
				ge_line_styles=new Array();
			}
		}
		else
		{
			if(reverse)
			{
				if(property_counter>0)
				{
					property_counter--;
				}
			}
			else
			{
				property_counter++;
			}
		}
		
		var last_prop=property_counter-1;
	}
	
	var p=property_counter;
	current_property_id=props[p].id;
	var photo_el=document.getElementById('preview_photo');
	photo_el.src=props[p].photo;
	if(props[p].photo_h>200)
	{
		photo_el.className="portrait";
	}
	else
	{
		photo_el.className="";
	}

	var price=writePrice(props[p].price_from,'js');
	if(props[p].price_to)
	{
		price+=" - "+writePrice(props[p].price_to,'js');
	}

	document.getElementById('preview_title').innerHTML="<b>"+price+per_week+"</b><br/>"+props[p].title;
	document.getElementById('preview_desc').innerHTML=props[p].desc+"<br /><a href='javascript:visitProperty();'>More details</a>";
	document.getElementById('preview_arrow').src="/i/preview_arrow_left_ani.gif";
	var lat=props[p].lat;
	var lon=props[p].lon;
	var la=ge.createLookAt('');
	la.set(lat,lon,0,ge.ALTITUDE_RELATIVE_TO_GROUND,0,70,500);
	ge.getView().setAbstractView(la);
	if(p>0&&!reverse)
	{
		var lat_prev=props[last_prop].lat;
		var lon_prev=props[last_prop].lon;
		var lineStringPlacemark=ge.createPlacemark('');
		var lineString=ge.createLineString('');
		lineString.setTessellate(true);
		lineString.getCoordinates().pushLatLngAlt(lat_prev,lon_prev,0);
		lineString.getCoordinates().pushLatLngAlt(lat,lon,0);
		if(!lineStringPlacemark.getStyleSelector())
		{
			lineStringPlacemark.setStyleSelector(ge.createStyle(''));
		}
	
		var lineStyle=lineStringPlacemark.getStyleSelector().getLineStyle();
		lineStyle.setWidth(7);
		lineStyle.getColor().set('9000f0ff');
		ge_line_styles.push(lineStyle);
		if(p>1)
		{
		}
	
		lineStringPlacemark.setGeometry(lineString);
		ge.getFeatures().appendChild(lineStringPlacemark);
	}

	if(reverse)
	{
		ge_line_styles[p].getColor().set('0000f0ff');
		ge_line_styles.pop();
	}
}

function shoppingCart_getTopPos(inputObj)
{		
  var returnValue = inputObj.offsetTop;
  while((inputObj = inputObj.offsetParent) != null){
  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetTop;
  }
  return returnValue;
}

function shoppingCart_getLeftPos(inputObj)
{
  var returnValue = inputObj.offsetLeft;
  while((inputObj = inputObj.offsetParent) != null){
  	if(inputObj.tagName!='HTML')returnValue += inputObj.offsetLeft;
  }
  return returnValue;
}

function flyToPropertyDeatils(sourceDivId, destinationDivId)
{
	
	if(!shopping_cart_div)shopping_cart_div = document.getElementById(destinationDivId);
	if(!flyingDiv){
		flyingDiv = document.createElement('DIV');
		flyingDiv.style.position = 'absolute';
		document.body.appendChild(flyingDiv);
	}
	
	shopping_cart_x = shoppingCart_getLeftPos(shopping_cart_div);
	shopping_cart_y = shoppingCart_getTopPos(shopping_cart_div);

	currentProductDiv = document.getElementById(sourceDivId);
	
	currentXPos = shoppingCart_getLeftPos(currentProductDiv);
	currentYPos = shoppingCart_getTopPos(currentProductDiv);
	
	diffX = shopping_cart_x - currentXPos;
	diffY = shopping_cart_y - currentYPos;
	

	
	var shoppingContentCopy = currentProductDiv.cloneNode(true);
	shoppingContentCopy.id='';
	flyingDiv.innerHTML = '';
	flyingDiv.style.left = currentXPos + 'px';
	flyingDiv.style.top = currentYPos + 'px';
	flyingDiv.appendChild(shoppingContentCopy);
	flyingDiv.style.display='block';
	flyingDiv.style.width = currentProductDiv.offsetWidth + 'px';
	startFlying();
	
}

function startFlying()
{
	var maxDiff = Math.max(Math.abs(diffX),Math.abs(diffY));
	var moveX = (diffX / maxDiff) * flyingSpeed;;
	var moveY = (diffY / maxDiff) * flyingSpeed;	
	
	currentXPos = currentXPos + moveX;
	currentYPos = currentYPos + moveY;
	
	flyingDiv.style.left = Math.round(currentXPos) + 'px';
	flyingDiv.style.top = Math.round(currentYPos) + 'px';	
	
	
	if(moveX>0 && currentXPos > shopping_cart_x){
		flyingDiv.style.display='none';		
	}
	if(moveX<0 && currentXPos < shopping_cart_x){
		flyingDiv.style.display='none';		
	}
		
	if(flyingDiv.style.display=='block')setTimeout('startFlying()',50); 
}
