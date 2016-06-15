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

    <div class="input-group"> <span class="input-group-addon">Filter</span>
        <input id="filter" type="text" class="form-control" placeholder="Type here...">
    </div>

<?php
echo '<table class="table table-hover" data-toggle="table">';
$f = fopen("datasets/".$_GET['i']."/scrape_".$_GET['i'].".csv", "r");
$rowcount = 0;
while (($line = fgetcsv($f)) !== false && $rowcount <= 100) {
	if($rowcount == 0) {
		echo '<thead class="thead-inverse"><tr>';

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