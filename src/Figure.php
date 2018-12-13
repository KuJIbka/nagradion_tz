<?php

class Figure {
    protected $isBlack;

    public function __construct($isBlack) {
        $this->isBlack = $isBlack;
    }

    /**
     * @return bool
     */
    public function getIsBlack() {
        return $this->isBlack;
    }

    /** @noinspection PhpToStringReturnInspection */
    public function __toString() {
        throw new \Exception("Not implemented");
    }

    /**
     * @param Desk $desc
     * @param MoveCoords $move
     * @return void
     * @throws \Exception
     */
    public function canMove(Desk $desc, MoveCoords $move) {
        $figureOnNewPos = $desc->findFigure($move->getTo());
        if ($figureOnNewPos && $figureOnNewPos->isBlack === $this->getIsBlack()) {
            throw new \Exception('You can not eat you own figures on '.$move->getTo());
        }
    }
}
