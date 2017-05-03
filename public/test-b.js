var fs = require('fs');

var rows = [];
var ids = [];

var live = true;
var runCount = 0;
var limit = 1000;
var hitCount = 0;
var nextID;
var perPageLimit = 50000;
var pageCount = 0;
var pageID = 0;

var sC = 'J0HV5evKQAAAF0HV5ZfxgAAAFpopAA';
var newSC = false;
var f;
var r;
var p;

var start = new Date().getTime();
console.log(start);

  function locationPos() {
    console.log('yo');
    r = fs.open('dat-new123.csv', "r");
    var rline = r.readLine();

    locationGather();

    function locationGather() {
      console.log(rline);

      if(rline) {
        var values = rline.split(',');
        var postID = values[0];
        var locationID = values[1];

        if(postID != 'false' && locationID != 'false') {

          var newPostID = postID.trim();
          var newLocationID = locationID.trim();

          page = require('webpage').create();

          page.open('https://www.instagram.com/explore/locations/'+newLocationID+'/', function(status) {
              console.log('start pos 2');

            var media = page.evaluate(function() {
              if(window._sharedData.entry_data.LocationsPage) {
                return JSON.stringify(window._sharedData.entry_data.LocationsPage[0].location);
              } else {
                return false;
              }
            });

            console.log(media);

            var row = newPostID;

            if(media) {
              var rowObj = JSON.parse(media);


              if(rowObj.lat && rowObj.lng) {
                row += ','+ rowObj.lat + ',' + rowObj.lng;
              } else {
                row += ',false,false';
              }
            } else {
              row += ',false,false';
            }


            page.release();
            fs.write("dat-new12345.csv", row+"\n", 'a');
            rline = r.readLine();
            locationGather();

          });
        } else {
          rline = r.readLine();
          locationGather();
        }
      } else {
        r.close();
        locationExamine();
      }
    }
  }




  function locationExamine() {
    p = fs.open('dat-new12345.csv', "r");
    var pline = p.readLine();

    console.log(p);
    console.log(pline);

    locationAssess();

    function locationAssess() {

      if(pline) {
        console.log('pline');

        var values = pline.split(',');
        var locationLat = values[1];
        var locationLng = values[2];

        console.log(locationLat);
        console.log(locationLng);

        if(locationLat != 'false' && locationLng != 'false') {
          var postID = values[0];
          var newPostID = postID.trim();

          var newLocationLat = locationLat.trim();

          var newLocationLng = locationLng.trim();

          page = require('webpage').create();
          var mapURL = 'http://maps.googleapis.com/maps/api/geocode/json?latlng='+newLocationLat+','+newLocationLng+'&sensor=false';
          console.log(mapURL);
          page.open(mapURL, function(status) {
              console.log('start pos 3');

              var jsonSource = page.plainText;
              var resultObject = JSON.parse(jsonSource);

              results = resultObject.results;

              if (results[1]) {
                  var country = null, countryCode = null, city = null, cityAlt = null;
                  var c, lc, component;
                  for (var r = 0, rl = results.length; r < rl; r += 1) {
                      var result = results[r];

                      if (!city && result.types[0] === 'locality') {
                          for (c = 0, lc = result.address_components.length; c < lc; c += 1) {
                              component = result.address_components[c];

                              if (component.types[0] === 'locality') {
                                  city = component.long_name;
                                  break;
                              }
                          }
                      }
                      else if (!city && !cityAlt && result.types[0] === 'administrative_area_level_1') {
                          for (c = 0, lc = result.address_components.length; c < lc; c += 1) {
                              component = result.address_components[c];

                              if (component.types[0] === 'administrative_area_level_1') {
                                  cityAlt = component.long_name;
                                  break;
                              }
                          }
                      } else if (!country && result.types[0] === 'country') {
                          country = result.address_components[0].long_name;
                          countryCode = result.address_components[0].short_name;
                      }

                      if (city && country) {
                          break;
                      }
                  }

                  console.log("City: " + city + ", City2: " + cityAlt + ", Country: " + country + ", Country Code: " + countryCode);

                  var row = newPostID+','+city+','+cityAlt+','+country+','+countryCode;
                  fs.write("dat-new12345678.csv", row+"\n", 'a');
                }

                    // var row = newPostID+','+resultObject.results[3].formatted_address;

                    // var row = '';

                    // if(resultObject.data.latitude && resultObject.data.longitude) {
                    //   row += newPostID +','+ resultObject.data.latitude + ',' + resultObject.data.longitude;
                    // } else {
                    //   row += newPostID +',false,false';
                    // }

                    // // console.log(row);

              // fs.write("dat-new12345678.csv", row+"\n", 'a');
              pline = p.readLine();
              locationAssess();
          });
        } else {
          pline = p.readLine();
          locationAssess();
        }
      } else {

        var end = new Date().getTime();
        var time = end - start;
        console.log('Execution time: ' + time);
        phantom.exit();
      }
    }
  }

locationPos();