<?php

class Library {
    public $id;
    public $numberOfBooks;
    public $signUpDays;
    public $shipAmount;
    public $bookIds;
    public $scannedBooks = [];

    public function __construct($id, $numberOfBooks, $signUpDays, $shipAmount, $bookIds)
    {
        $this->id = $id;
        $this->numberOfBooks = (int) $numberOfBooks;
        $this->signUpDays = (int) $signUpDays;
        $this->shipAmount = (int) $shipAmount;
        $this->bookIds = $bookIds;
    }

    public function scanBooks($alreadyScannedBooks)
    {
        for($i=0; $i<$this->shipAmount; $i++) {

            if (empty($this->bookIds)) {
                break;
            }
            $book = array_pop($this->bookIds);

            while (isset($alreadyScannedBooks[$book])) {
                $book = array_pop($this->bookIds);
            }
            if ($book == null) {
                break;
            }
            $alreadyScannedBooks[$book] = true;
            $this->scannedBooks[] = $book;
        }
        return $alreadyScannedBooks;
    }

    public function getScannedBooksNumber()
    {
        return count($this->scannedBooks);
    }
}
