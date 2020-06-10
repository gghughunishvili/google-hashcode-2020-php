<?php

class Brain
{
    public $books;
    public $libraries;
    public $days;
    public $usedLibraries = [];
    public $scannedBooks;

    public function __construct($books, $libraries, $days)
    {
        $this->books = $books;
        $this->libraries = $libraries;
        $this->days = $days;
    }

    public function solution()
    {
        $signUpProcessDays = 0;
        for ($day = 0; $day<$this->days; $day++) {
            if ($signUpProcessDays == 0) {
                $nextLibrary = $this->findLibrary();
                if ($nextLibrary) {
                    $signUpProcessDays = $nextLibrary->signUpDays;
                    $this->usedLibraries[] = $nextLibrary;
                }
            }
            $this->scanAndProcessLibraries();
            $signUpProcessDays--;
        }
        return $this->returnAnswer($this->usedLibraries);
    }

    public function findLibrary()
    {
        return array_shift($this->libraries);
    }

    public function scanAndProcessLibraries()
    {
        foreach ($this->usedLibraries as $i => $library) {
            if ($library->signUpDays === 0) {
                $this->usedLibraries[$i] = $this->scanBooks($library);
            } else {
                $this->usedLibraries[$i]->signUpDays--;
            }
        }
    }

    public function scanBooks(Library $library)
    {
        $this->scannedBooks = $library->scanBooks($this->scannedBooks);
        return $library;
    }

    public function returnAnswer($scannedLibraries)
    {
        $lib = array_pop($scannedLibraries);
        if ($lib && $lib->getScannedBooksNumber() > 0) {
            $scannedLibraries[] = $lib;
        }
        $answer = [];
        $answer[] = count($scannedLibraries);
        foreach ($scannedLibraries as $scannedLibrary) {
            $answer[] = $scannedLibrary->id . " " . $scannedLibrary->getScannedBooksNumber();
            $answer[] = implode(' ', $scannedLibrary->scannedBooks);
        }

        return implode("\n", $answer);
    }

}
