"use strict";

var fs = require('fs');

fetchID('');

var nextID;

function fetchID(ID) {

  if(!ID) {
    ID = '';
  }

  var page = require('webpage').create();
  var url = 'https://www.instagram.com/explore/tags/engaged/?max_id='+ID;

  console.log(url);

  page.open(url, function () {
  });

  page.onLoadFinished = function(status){
    var media;
    nextID = page.evaluate(function () {

        media = window._sharedData.entry_data.TagPage[0].tag.media.nodes;



        return window._sharedData.entry_data.TagPage[0].tag.media.page_info.end_cursor;
    });

    for (var i = 0; i < media.length; i++) {
      var name = media[i].caption;
      try {
        fs.write("captions.csv", name+"\n", 'a');
      } catch(e) {
        console.log(e);
      }
    }

    try {
        fs.write("file.csv", nextID+"\n", 'a');
    } catch(e) {
        console.log(e);
    }

    // console.log(title);
    page.close();
  };

  page.onClosing = function(newID) {
    console.log('The page is closing!');
    console.log(nextID);
    fetchID(nextID);
    // console.log(page);
  };

}


