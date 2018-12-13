<?php

class Pawn extends Figure {
    /**
     * {@inheritdoc}
     */
    public function canMove(Desk $desc, MoveCoords $move) {
        parent::canMove($desc, $move);
        $xDiff = $move->getXDiff();
        $yDiff = $move->getYDiff();

        if ($this->getIsBlack()) {
            $xDiff *= -1;
            $yDiff *= -1;
        }

        if ($yDiff < 0) {
            throw new \Exception('Pawn can not move back Move: '.$move);
        }
        $enemyFigure = $desc->findFigure($move->getTo());

        if (!(($enemyFigure && abs($xDiff) === 1 && $yDiff === 1)
            || (
                !$enemyFigure
                && ($move->getFrom()->getY() === 2 || $move->getFrom()->getY() === 7)
                && $yDiff > 0 && $yDiff < 3
                && $xDiff === 0
                && $move->isFreeLine()
            ) || (
                !$enemyFigure
                && ($move->getFrom()->getY() !== 2 && $move->getFrom()->getY() !== 7)
                && $xDiff === 0
                && $yDiff === 1
            )
        )) {
            throw  new \Exception('You can not do this move: '.$move);
        }
    }

    public function __toString() {
        return $this->isBlack ? '♟' : '♙';
    }
}
