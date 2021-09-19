<html>
    <head>
        <!--External CSS link-->
       <link href="css/styles.css" rel="stylesheet">
       <title> IMDB Movie Search </title>
    </head>
    <body>
        <header>
            <h1>IMDB Movie Search</h1>   
        </header>
        <p>
            <?php
            // Open database file
            // https://www.kaggle.com/mukul1904/imdb-top-250
            $db = new SQLite3('imdb.sqlite');

            // Load received form data into variables
            if (!empty($_POST["rank"])) {
                #echo "RANK";
                $rank = $_POST["rank"];
            } else if (!empty($_POST["title"])) {
                #echo "TITLE";
                $title = $_POST["title"];
            } else if (!empty($_POST["year"])) {
                #echo "YEAR";
                $year = $_POST["year"];
            }

            // If rank is specified
            if (isset($rank)) {
                // Query movie using rank
                $query = $db->prepare('SELECT * FROM imdb100 WHERE rank = ?');
                $query->bindParam(1, $rank, SQLITE3_TEXT);
            } 
            // If title is specified
            else if (isset($title)) {
                // Ignore input letter case
                $query = $db->prepare('SELECT * FROM imdb100 WHERE title = ? COLLATE NOCASE');
                $query->bindParam(1, $title, SQLITE3_TEXT);
            } 
            // If year is specified
            else if (isset($year)) {
                // Query movie using year
                $query = $db->prepare('SELECT * FROM imdb100 WHERE year = ?');
                $query->bindParam(1, $year, SQLITE3_TEXT);
            } 
            // Otherwise, cannot handle request
            else {
                echo "Cannot handle request.";
            }

            // Execute sql query
            $res = $query->execute();

            // Find out how many entries were returned from sql query
            $nrows = 0;
            while ($rows = $res->fetchArray()) {
                $nrows++;
            }

            // If no result returned then no valid answer
            if ($nrows == 0) {
                echo "Did not find valid answer.";
            } else {
                // Otherwise output result to HTML
		        echo "<h2>Here are the movies:</h2>";
                echo "<p>Rank &nbsp&nbsp Title &nbsp&nbsp Year</p>";
                while ($row = $res->fetchArray()) {
                    $line = sprintf("%-10s &nbsp %-10s &nbsp %s\n", $row[0], $row[1], $row[2]);
                    echo $line;
                    echo "<br>";
                }
            }
            ?>
        </p>
        <!--Hyperlink to return to prior page-->
        <a href="http://18.221.177.230/html/moviesearch.html">Return to Page</a>
    </body>
</html>