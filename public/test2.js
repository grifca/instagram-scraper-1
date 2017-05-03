var fs = require('fs');

var rows = [];
var ids = [];

var live = true;
var runCount = 0;
var limit = 1000;
var hitCount = 0;
var nextID;
var perPageLimit = 5;
var pageCount = 0;
var pageID = 0;

function process(ID) {

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
  var returnedID = page.evaluate(function (o) {

      // var media = window._sharedData.entry_data.TagPage[0].tag.media.nodes;
      // console.log(JSON.stringify(media));

      return window._sharedData.entry_data.TagPage[0].tag.media.nodes[11].id;
  }, offset);

  if(testID(returnedID)) {

    var returnedmedia = page.evaluate(function () {

        var media = window._sharedData.entry_data.TagPage[0].tag.media.nodes;
        console.log(JSON.stringify(media));

        // return window._sharedData.entry_data.TagPage[0].tag.media.nodes[11].id;
    });

    ids.push(returnedID);
    console.log('not exist ' + returnedID);
    return returnedID;
  } else {
    offset++;
    var newRetreived = retreiveID(offset);
    console.log('exist ' + newRetreived);
    // return newRetreived;
  }
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

    if(live) {
      process(nextID);
    } else {
      runFiles(nextID);
    }
}


function runFiles(ID) {
  for (var i = 0; i < rows.length; i++) {
    var row = rows[i];

    pageCount++;

    try {
      if(pageCount > perPageLimit) {
        pageID++;
        fs.write("date-test"+pageID+".csv", row+"\n", 'a');
        pageCount = 0;
      } else {
        fs.write("date-test"+pageID+".csv", row+"\n", 'a');
      }

        
    } catch(e) {
        console.log(e);
    }
  }

  rows = [];

  console.log(Date.now());
  process(nextID);
}

console.log(Date.now());
process('1275782516826164142');