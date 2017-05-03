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

function process(ID) {

    runCount++;

    if(rows.length > limit) {
      live = false;
    }

    console.log('running: '+ID);

    var url = 'https://www.instagram.com/explore/tags/engaged/?max_id='+ID;
    page = require('webpage').create();

    var currentUrl = url;


    page.open(url, onFinishedLoading);

    page.onConsoleMessage = function(msg) {
      console.log(msg);
      // addRow(msg);
    }
}


function onFinishedLoading(status) {
    var offset = 0;

    nextID = retreiveID(offset);
    console.log('starting cursor: ' + nextID);

    fetchQuery();
}

function fetchQuery() {
  var queryurl = "https://www.instagram.com/query/?q=ig_hashtag%28proposal%29+{+media.after%28J0HV5cP4QAAAF0HV5cH5wAAAFiYA%2C+10%29+{%0A++count%2C%0A++nodes+{%0A++++caption%2C%0A++++code%2C%0A++++location+{%0A++++++lat%2C%0A++++++lng%0A++++}%2C%0A++++comments+{%0A++++++count%0A++++}%2C%0A++++date%2C%0A++++dimensions+{%0A++++++height%2C%0A++++++width%0A++++}%2C%0A++++display_src%2C%0A++++id%2C%0A++++is_video%2C%0A++++likes+{%0A++++++count%0A++++}%2C%0A++++owner+{%0A++++++id%0A++++}%2C%0A++++thumbnail_src%2C%0A++++video_views%0A++}%2C%0A++page_info%0A}%0A+}&ref=tag%3A%3Ashow";

  // console.log(queryurl);

  page.release();

  page = require('webpage').create();

  page.open('http://matthewshields.co.uk', ajaxXCall);
  page.release();
  phantom.exit();

}


function ajaxXCall() {
  console.log('call');
  page.evaluate(function(data) {
    console.log('hi');
    console.log(data);
    // var resultObject = JSON.parse(jsonSource);
    // console.log(resultObject);
  });
}


function retreiveID(offset) {
  var returnedID = page.evaluate(function () {
      var media = window._sharedData.entry_data.TagPage[0].tag.media.page_info.end_cursor;
      console.log(media);
      return media;
  });

  console.log('returned cursor: ' + returnedID);
  return returnedID;
}


function testID(testingID) {
  var e = ids.indexOf(testingID);

  if(e == -1) {
    return true;
  }
  else {
    return false;
  }
}





function printMedia(msg) {
  try {
    fs.write("captions.csv", msg+"\n", 'a');
  } catch(e) {
    console.log(e);
  }
}

function addRow(msg) {

  var results = JSON.parse(msg);


  for (var i = 0; i < results.length; i++) {

    var name = results[i].caption;
    name = name.replace(/[^a-zA-Z0-9 #]/g, "");

    var row = results[i].id + ', ' + results[i].code + ', ' +name;

    var e = rows.indexOf(row);

    if(e == -1) {
      rows.push(row);
      hitCount++;
      console.log(hitCount);
    }

    live = false;

  }
}








function runFiles(ID) {
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];

    pageCount++;

    try {
      if(pageCount == perPageLimit) {
        pageID++;
        fs.write("5date-test"+pageID+".csv", row+"\n", 'a');
        pageCount = 0;
      } else {
        fs.write("5date-test"+pageID+".csv", row+"\n", 'a');
      }

        
    } catch(e) {
        console.log(e);
    }
  }

  rows = [];

  console.log('date end: ' + Date.now());
  process(nextID);
}

console.log('date start: ' + Date.now());
process('');