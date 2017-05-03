var fs = require('fs');

function process(ID) {

    //remove the first item of an array
    var url = 'https://www.instagram.com/explore/tags/engaged/?max_id='+ID;
    //open a page
    page = require('webpage').create();

    //store the requested url in a separate variable
    var currentUrl = url;


    page.open(url, onFinishedLoading)

    // page.onNavigationRequested = function(url, type, willNavigate, main) {
    //     console.log('\n' + currentUrl + '\nredirecting to \n' + url);
    // }

    page.onConsoleMessage = function(msg) {
      // console.log(msg);
      printMedia(msg);
    }
}


function printMedia(msg) {
  try {
    fs.write("captions.csv", msg+"\n", 'a');
  } catch(e) {
    console.log(e);
  }
}

function onFinishedLoading(status) {

    nextID = page.evaluate(function () {
      // var needle = "csrftoken";
      // var regexp = new RegExp("(?:^" + needle + "|;\s*"+ needle + ")=(.*?)(?:;|$)", "g");
      // var result = regexp.exec(document.cookie);
      // var cook = (result === null) ? null : result[1];
      // console.log(cook);
        var media = window._sharedData.entry_data.TagPage[0].tag.media.nodes;
        // for (var i = 0; i < media.length; i++) {

          var name = media[0].caption;
          name = name.replace(/[^a-zA-Z0-9 #]/g, "");

          var row = media[0].id + ', ' +name;

          console.log(row);
        // }
        // console.log(window._sharedData.entry_data.TagPage[0].tag.media.page_info.end_cursor);
        // console.log(window._sharedData.config.csrf_token);
        return window._sharedData.entry_data.TagPage[0].tag.media.page_info.end_cursor;
    });

    // media = page.evaluate(function () {

    //     return window._sharedData.entry_data.TagPage[0].tag.media.nodes;
    // });

    console.log(nextID);

    // try {
    //     fs.write("file.csv", nextID+"\n", 'a');
    // } catch(e) {
    //     console.log(e);
    // }



    // console.log(status);
    page.release();
    process(nextID);
}

process('');