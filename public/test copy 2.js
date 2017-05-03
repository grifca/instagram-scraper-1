var fs = require('fs');

var rows = [];

var live = true;
var runCount = 0;
var limit = 1000;
var hitCount = 0;
var nextID;

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

function onFinishedLoading(status) {

    nextID = page.evaluate(function () {

        var media = window._sharedData.entry_data.TagPage[0].tag.media.nodes;
        console.log(JSON.stringify(media));

        return window._sharedData.entry_data.TagPage[0].tag.media.nodes[11].id;
    });


    // console.log(hitCount);




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

    try {
        fs.write("captions.csv", row+"\n", 'a');
    } catch(e) {
        console.log(e);
    }
  }

  rows = [];

  console.log(Date.now());
  process(nextID);
}

console.log(Date.now());
process('1273980821919608030');