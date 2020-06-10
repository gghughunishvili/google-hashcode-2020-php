<?php

class Book {
    public $id;
    public $score;

    public function __construct($id, $score)
    {
        $this->id = $id;
        $this->score = (int)$score;
    }
}
