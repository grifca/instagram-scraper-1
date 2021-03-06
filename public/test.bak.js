var fs = require('fs');

var rows = [];
var ids = [];

var live = true;
var runCount = 0;
var limit = 1000;
var hitCount = 0;
var nextID;

var lastDate = false;


initialPull();

function initialPull() {
    console.log('intial');
    var url = 'https://www.instagram.com/explore/tags/engaged/';
    page = require('webpage').create();

    page.open(url, retreiveDate);
}


function retreiveDate() {
    console.log('intial retreive');
    var initialDate = page.evaluate(function () {

        var media = window._sharedData.entry_data.TagPage[0].tag.media.nodes;

        var i = media.length - 1;
        var date = media[i].date;

        return date;
    });

    page.release();

    lastDate = initialDate;

    console.log(lastDate);


    console.log(Date.now());
    process('');
}



function process(ID) {
    console.log('process');

    runCount++;

    if(rows.length > limit) {
      live = false;
    }

    var url = 'https://www.instagram.com/explore/tags/engaged/?max_id='+ID;
    page = require('webpage').create();

    var currentUrl = url;


    page.open(url, onFinishedLoading);

    page.onConsoleMessage = function(msg) {
      // printMedia(msg);
      // hitCount++;
      addRow(msg);
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






function retreiveID(offset) {
  var returnedID = page.evaluate(function (o, d) {

      var media = window._sharedData.entry_data.TagPage[0].tag.media.nodes;
      var id = false;
      console.log(JSON.stringify(media));

      var i= media.length - 1;

      if(d != false) {
        while(i--) {
          var date = media[i].date;
          var timeDiff = date - d;
          // return timeDiff;
          if(timeDiff >= 0 && timeDiff <= 3600) {
            id = media[i].id;
            break;
          }
        }
      }

      return id;
  }, offset, lastDate);

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

function onFinishedLoading(status) {
    var offset = 0;

    nextID = retreiveID(offset);



    console.log(nextID);




    page.release();

    if(nextID == false) {
      runFiles(nextID);
    } else {
      process(nextID);
    }
}


function runFiles(ID) {
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];

    try {
        fs.write("date-test.csv", row+"\n", 'a');
    } catch(e) {
        console.log(e);
    }
  }

  rows = [];

  console.log(Date.now());
  process(nextID);
}
