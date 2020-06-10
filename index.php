<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('max_execution_time', 1000);
ini_set('memory_limit', '1024M');
error_reporting(E_ALL);

include_once "helpers.php";
include_once "Brain.php";
include_once "Book.php";
include_once "Library.php";

// ---------------------------------------- FILE Handling --------------------------------------- \\

$CHOOSE_FILE = 3;

$FILENAMES = ['a_example', 'b_read_on', 'c_incunabula', 'd_tough_choices', 'e_so_many_books', 'f_libraries_of_the_world'];

$inputPath = 'inputs/' . $FILENAMES[ $CHOOSE_FILE ] . '.txt';
$outputPath = 'outputs/' . $FILENAMES[ $CHOOSE_FILE ] . '.txt';

// ---------------------------------------- FILE Handling --------------------------------------- \\


//****************************************** READING **************************************\\
$file = fopen($inputPath, 'r');

/* Start Reading */

/////////////////////////////////////// Read First line \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$firstLine = fgets($file);
list($B, $L, $Days) = explode(' ', $firstLine);

$Days = (int) $Days;

$secondLine = fgets($file);
$booksScore = explode(' ', $secondLine);

$books = [];
$booksScores = [];

foreach ($booksScore as $id => $score) {
    $books[$id] = new Book($id, $score);
    $booksScore[$id] = $score;
}

$libraries = [];
for ($i = 0; $i<$L; $i++) {
    $libraryLine = fgets($file);
    list($numOfBooks, $signUpDays, $shipAmount) = explode(' ', $libraryLine);
    $booksLine = trim(fgets($file));
    $bookIds = explode(' ', $booksLine);

    // Sort book Ids based on score
    uasort($bookIds, function($b1, $b2) {
       global $booksScore;
       if ($booksScore[$b1] == $booksScore[$b2]) {
           return 0;
       }

       return $booksScore[$b1] > $booksScore[$b2] ? -1 : 1;
    });

    $libraries[$i] = new Library($i, $numOfBooks, $signUpDays, $shipAmount, $bookIds);
}

function getBookScores(Library $lib, $totalBooks)
{
    global $booksScore;

    $sum = 0;
    foreach ($lib->bookIds as $bookId) {
        if ($totalBooks == 0) {
            break;
        }
        $sum += (int) $booksScore[$bookId];
        $totalBooks--;
    }

    return $sum;
}


uasort($libraries, function(Library $a, Library $b) {
    global $Days;

    $lib1Books = ($Days - $a->signUpDays) * $a->shipAmount;
    $lib2Books = ($Days - $b->signUpDays) * $b->shipAmount;

    $lib1BookScores = getBookScores($a, $lib1Books);
    $lib2BookScores = getBookScores($a, $lib2Books);

    if ($lib1BookScores == $lib2BookScores) {
        return 0;
    }
    return ($lib1BookScores > $lib2BookScores) ? -1 : 1;
});

/////////////////////////////////////// Create Brain object  \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$brain = new Brain($books, $libraries, $Days);

//printJustArray($brain->solution());

//****************************************** READING **************************************\\



/////////////////////////////////// Solution and writing in File \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
$answer = $brain->solution();

writeInFile($outputPath, $answer);

//printJustArray($brain);
