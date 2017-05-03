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

function process(ID) {

    // runCount++;

    // console.log('running: '+ID);

    

    // console.log(queryurl);

    page = require('webpage').create();

    page.onError = function(msg, trace) {

      var msgStack = ['ERROR: ' + msg];

      if (trace && trace.length) {
        msgStack.push('TRACE:');
        trace.forEach(function(t) {
          msgStack.push(' -> ' + t.file + ': ' + t.line + (t.function ? ' (in function "' + t.function +'")' : ''));
        });
      }

      console.error(msgStack.join('\n'));

    };

    // page.open('https://www.instagram.com/explore/tags/engaged/');

    page.open('https://www.instagram.com/explore/tags/engaged/', function (status) {
      if (status === 'success') {

        // Inject jQuery for scraping (you need to save jquery-1.6.1.min.js in the same folder as this file)
        page.injectJs('jquery.min.js');
      }
    });


    page.onLoadFinished = function(status) {
      // console.log('Status: ' + status);
      evaluatePage();
    };

    page.onConsoleMessage = function(msg) {
      // console.log(msg);
      printMedia(msg);
      // hitCount++;
      // addRow(msg);
    }
}


function pageLoaded() {
  console.log('laod');
}


function evaluatePage() {
  console.log('eal');
    page.evaluate(function () {
        // var jsonSource = page.plainText;
        // var resultObject = JSON.parse(jsonSource);

        // var images = resultObject.media.nodes;
        // newSC = resultObject.media.page_info.end_cursor;

        // if(newSC.indexOf('%') >= 0) {
        //   newSC = newSC.substring(0, newSC.indexOf('%'));
        // }

        // for(var i in images) {
        //   printMedia(JSON.stringify(images[i]));
        // }


        // page.release();
        // setInterval(function() {
        //   renewPage();
        // }, 1000);



        fetchPage(window._sharedData.entry_data.TagPage[0].tag.media.page_info.end_cursor);

        function fetchPage(ID) {
          $.ajax({
            url: "https://www.instagram.com/query/?q=ig_hashtag%28engaged%29+{+media.after%28"+ID+"%2C+1000%29+{%0A++count%2C%0A++nodes+{%0A++++caption%2C%0A++++code%2C%0A++++location+{%0A++++++lat%2C%0A++++++lng%0A++++}%2C%0A++++comments+{%0A++++++count%0A++++}%2C%0A++++date%2C%0A++++dimensions+{%0A++++++height%2C%0A++++++width%0A++++}%2C%0A++++display_src%2C%0A++++id%2C%0A++++is_video%2C%0A++++likes+{%0A++++++count%0A++++}%2C%0A++++owner+{%0A++++++id%0A++++}%2C%0A++++thumbnail_src%2C%0A++++video_views%0A++}%2C%0A++page_info%0A}%0A+}&ref=tag%3A%3Ashow", //Relative or absolute path to response.php file
            success: function(data) {

              var images = data.media.nodes;

              for(var i in images) {
                console.log(JSON.stringify(images[i]));
              }

              // console.log(JSON.stringify(data));
              fetchPage(data.media.page_info.end_cursor);
            },
            error: function(data) {
              console.log('error');
            }
          });
        }



        function renewPage() {
          $.ajax({
            type: 'POST',
            url: "https://www.instagram.com/ajax/bz", //Relative or absolute path to response.php file,
            data: {"q":[{"page_id":"obtc2j","posts":[["timespent_bit_array",{"tos_id":"obtc2j","start_time":1469559693
,"tos_array":[31,0],"tos_len":6,"tos_seq":0,"tos_cum":5,"log_time":1469559698306},1469559698306,0]],"trigger"
:"timespent_bit_array"}],"ts":1469559707800},
            success: function(data) {
              console.log(JSON.stringify(data));
            },
            error: function(req, err) {
              console.log('my message' + err);
            }
          });
        }
        


    });
}





function printMedia(msg) {
  var rowObj = JSON.parse(msg);

  var row = '';

  var rowID = rowObj.code;

  row += rowID;

  if(rowObj.location) {
    row += ','+rowObj.location.lat;
  } else {
    row += ',false';
  }

  if(rowObj.location) {
    row += ','+rowObj.location.lng;
  } else {
    row += ',false';
  }
  // console.log(msg);

  try {
    fs.write("captions-newwwww.csv", row+"\n", 'a');
  } catch(e) {
    // console.log(e);
  }
}






console.log('date start: ' + Date.now());
process(sC);