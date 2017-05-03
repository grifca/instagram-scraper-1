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

    runCount++;

    console.log('running: '+ID);

    var queryurl = "https://www.instagram.com/query/?q=ig_hashtag%28engaged%29+{+media.after%28"+ID+"%2C+10%29+{%0A++count%2C%0A++nodes+{%0A++++caption%2C%0A++++code%2C%0A++++location+{%0A++++++lat%2C%0A++++++lng%0A++++}%2C%0A++++comments+{%0A++++++count%0A++++}%2C%0A++++date%2C%0A++++dimensions+{%0A++++++height%2C%0A++++++width%0A++++}%2C%0A++++display_src%2C%0A++++id%2C%0A++++is_video%2C%0A++++likes+{%0A++++++count%0A++++}%2C%0A++++owner+{%0A++++++id%0A++++}%2C%0A++++thumbnail_src%2C%0A++++video_views%0A++}%2C%0A++page_info%0A}%0A+}&ref=tag%3A%3Ashow";

    console.log(queryurl);

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

    page.open(queryurl, function () {
        var jsonSource = page.plainText;
        var resultObject = JSON.parse(jsonSource);

        var images = resultObject.media.nodes;
        newSC = resultObject.media.page_info.end_cursor;

        if(newSC.indexOf('%') >= 0) {
          newSC = newSC.substring(0, newSC.indexOf('%'));
        }

        for(var i in images) {
          printMedia(JSON.stringify(images[i]));
        }


        page.release();
        setTimeout(function() {
          process(newSC);
        }, 1000);


        // console.log(resultObject.status);
        console.log('date end: ' + Date.now());
        // phantom.exit();
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