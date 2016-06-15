<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css"> 
<link rel="stylesheet" href="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.css">

<style>
    body {
        padding: 80px 40px 40px;
    }

    textarea {
        margin: 0 0 20px;
    }

    .input-group {
    	margin: 0 0 20px;
        /*width: 100%;*/
    }

    .navbar {
        background: black;
    }

    .navbar a {
        color: white;
    }
</style>
</head>
<body>

<nav class="navbar navbar-fixed-top navbar-dark">
  <a class="navbar-brand" href="/">Extractor</a>
  <a class="navbar-brand" href="datasets.php">Datasets</a>
  <a class="navbar-brand" href="matches.php">Matches</a>
  <a class="navbar-brand" href="reports.php">Reports</a>
</nav>

<?php if($_GET['i']) : ?>

    <div class="btn-group pull-xs-right" role="group" aria-label="Basic example">
      <button type="button" class="btn btn-secondary">View Images</button>
      <button type="button" class="btn btn-secondary" rel="<?php echo $_GET['i']; ?>" id="download-btn">Download Data</button>
    </div>

    <div class="input-group">
        <div class="btn-group">
          <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reports</button>
          <div class="dropdown-menu">
            
            <?php 
            $outputs = glob('output/*'); 

            if($outputs) {

                foreach($outputs as $dir => $identifier) {
                    $identifier = ltrim($identifier, 'output/reports');
                    echo '<a href="view.php?i='.$identifier.'" class="dropdown-item">'.$identifier.'</a>';
                }
            }
            ?>
          </div>
        </div>
    </div>



    <div class="input-group"> <span class="input-group-addon">Filter</span>
        <input id="filter" type="text" class="form-control" placeholder="Type here...">
    </div>

<?php
echo '<table class="table table-hover" data-toggle="table">';
$f = fopen("output/".$_GET['i']."/reports/report_".$_GET['i'].".csv", "r");
$rowcount = 0;
while (($line = fgetcsv($f)) !== false) {
	if($rowcount == 0) {
		echo '<thead><tr>';

		foreach ($line as $cell) {
			echo '<th data-sortable="true">' . htmlspecialchars($cell) . '</th>';
		}

		echo "</tr></thead><tbody>";


	} else {
		echo '<tr class="searchable">';

		foreach ($line as $cell) {
			echo "<td>" . htmlspecialchars($cell) . "</td>";
		}

		echo "</tr>";
	}
	$rowcount++;
}
fclose($f);
echo "</tbody></table>";
?>

<?php else : ?>

    <form method="post" action="script.php">
        <textarea class="form-control" name="htmlinput" id="htmlinput" cols="30" rows="10"></textarea>
        <a href="javascript:void(0)" class="btn btn-primary" id="example">Load Example</a>
        <button type="submit" class="btn btn-success">Scrape</button>
    </form>

	<script>
        var exampleLink = document.getElementById("example");
        exampleLink.addEventListener('click', loadExample);

        function loadExample() {
            var textarea = document.getElementById("htmlinput");
            textarea.innerHTML= '<div class="_nljxa"> <div class="_myci9"> <a class="_8mlbc _vbtk2 _t5r8b" href="/p/BGS4jBjhzEA/?tagged=engagementring"> <div class="_22yr2"> <div class="_jjzlb"><img alt="Pastrami boudin hamburger flank cupim, kevin bresaola tri-tip. Fatback short ribs beef ribs, #shankle porchetta frankfurter #salami brisket drumstick boudin filet mignon. Pancetta #doner fatback #short ribs, corned beef #capicola porchetta #meatloaf pork beef ribs shankle leberkas. Bacon brisket #boudin fatback short ribs. Ground round #biltong cow pastrami, doner meatball cupim hamburger t-bone #boudin #leberkas." class="_icyx7" id="pImage_12" src="https://scontent-lhr3-1.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/13320288_253911148308504_1177136384_n.jpg?ig_cache_key=MTI2NjMyMzE0MjcwMDY0MjU2MA%3D%3D.2"></div><div class="_ovg3g"></div></div></a> <a class="_8mlbc _vbtk2 _t5r8b" href="/p/BGRKquQJ-sc/?tagged=engagementring"> <div class="_22yr2"> <div class="_jjzlb"><img alt="Bacon #ipsum dolor amet leberkas sirloin doner #hamburger fatback chicken. Ham hock porchetta jerky pastrami #cupim beef strip steak. Biltong shoulder jerky cupim, pork loin #landjaeger fatback #venison chicken ball #tip doner frankfurter. #Pork chop pastrami #pork jowl pig tri-tip #capicola cupim andouille biltong. Meatloaf salami #bresaola, #brisket #ground round biltong filet mignon #t-bone jowl sirloin. Biltong jowl ham hock, swine #drumstick turkey filet mignon venison cupim rump capicola #prosciutto #porchetta." class="_icyx7" id="pImage_13" src="https://scontent-lhr3-1.cdninstagram.com/t51.2885-15/e15/13394920_111036352652670_1244523066_n.jpg?ig_cache_key=MTI2NTgzOTg4NjYxNDEyOTQzNg%3D%3D.2"></div><div class="_ovg3g"></div></div><div class="_1lp5e"> <div class="_cxj4a"><span class="_hrkr1 _soakw coreSpriteVideoIconDesktop">Video</span></div></div></a> </div></div>';
        }
	</script>

<?php endif; ?>


<script   src="https://code.jquery.com/jquery-2.2.4.min.js"   integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="   crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/js/bootstrap.min.js"></script>
<script src="https://rawgit.com/wenzhixin/bootstrap-table/master/src/bootstrap-table.js"></script>


    <script>

    (function ($) {

        $('#filter').keyup(function () {

        	console.log('press');

            var rex = new RegExp($(this).val(), 'i');
            $('.searchable').hide();
            $('.searchable').filter(function () {
                return rex.test($(this).text());
            }).show();

        })

    }(jQuery));

    $('#download-btn').click(function() {
        window.location.href = $(this).attr('rel');
    });

// document.onkeypress = function (e) {
//     e = e || window.event;
//     // use e.keyCode
//     var inputfilter = document.getElementById("filter");
//     var filterRun = inputfilter.getAttribute('id');
//     alert(filterRun);
// 	// var rex = new RegExp(filterRun, 'i');

//     // Array.prototype.filter.call(document.querySelectorAll('.searchable'), filterFn);

// };

// function filterFn() {
// 	alert('filter');
// }

    </script>


</body>
</html>