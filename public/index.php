<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css"> 

<style>
    body {
        padding: 80px 40px 40px;
    }

    textarea {
        margin: 0 0 20px;
    }

    .input-group {
        margin: 0 0 20px;
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
</nav>

<?php if($_GET['success']) : ?>

<?php else : ?>
    <?php
    $max_file_size = 30000000; // size in bytes 
    ?>

    <!--
    <form method="post" action="script.php">
        <textarea class="form-control" name="htmlinput" id="htmlinput" cols="30" rows="10"></textarea>
        <a href="javascript:void(0)" class="btn btn-primary" id="example">Load Example</a>
        <button type="submit" class="btn btn-success">Scrape</button>
    </form>
    <hr> 
    -->

    <form method="post" action="upload.php" enctype="multipart/form-data">
        <fieldset class="form-group">
            <label class="file">
                <input type="hidden" name="MAX_FILE_SIZE" id="MAX_FILE_SIZE" value="<?php echo $max_file_size ?>"> 
                <input type="file" id="files" name="files" accept="text/plain">
                <span class="file-custom"></span>
            </label>
        </fieldset>
        <fieldset class="form-group">

        <?php 
        $outputs = glob('matches/*'); 

        if($outputs) {

            echo '<div class="list-group">';

            foreach($outputs as $dir => $identifier) {
                $identifier = ltrim($identifier, 'output/');
                echo '<div class="list-group-item"><input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">'.$identifier.'</div>';
            }

            echo '</div>';
        }
        ?>
        </fieldset>
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
</body>
</html>