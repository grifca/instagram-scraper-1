var fs = require('fs');

var f;
var r;
var p;

var start = new Date().getTime();
console.log(start);



function process() {

  f = fs.open('datasets/clean2/data.csv', "r");

  var line = f.readLine();
      console.log(line);

  locationID();

  function locationID() {
      console.log(line);
      // fs.write("dat-new123.csv", line+"\n", 'a');

      var values = line.split(',');
      var postID = values[1];
      var newPostID = postID.trim();

      page = require('webpage').create();

      console.log(newPostID);

      var postURL = 'https://www.instagram.com/p/'+newPostID+'/';
      console.log(postURL);

      page.open(postURL, function(status) {

        console.log('open');

        var media = page.evaluate(function() {
          if(window._sharedData.entry_data.PostPage) {
            return JSON.stringify(window._sharedData.entry_data.PostPage[0].media);
          } else {
            return false;
          }
        });

        console.log(media);

        if(media) {
          var rowObj = JSON.parse(media);

          var row = rowObj.code;

          if(rowObj.location) {
            row += ','+rowObj.location.id+','+rowObj.location.name;
          } else {
            row += ',false,false';
          }

          fs.write("dat-new123.csv", row+"\n", 'a');
        }


        page.release();

        line = f.readLine();

        if(line) {
          locationID();
        } else {
          phantom.exit();
        }

      });




  }

}

process();