<?php

class Desk {
    protected $isBlackMove = false;

    private $figures = [];

    public function __construct() {
        $this->figures['a'][1] = new Rook(false);
        $this->figures['b'][1] = new Knight(false);
        $this->figures['c'][1] = new Bishop(false);
        $this->figures['d'][1] = new Queen(false);
        $this->figures['e'][1] = new King(false);
        $this->figures['f'][1] = new Bishop(false);
        $this->figures['g'][1] = new Knight(false);
        $this->figures['h'][1] = new Rook(false);

        $this->figures['a'][2] = new Pawn(false);
        $this->figures['b'][2] = new Pawn(false);
        $this->figures['c'][2] = new Pawn(false);
        $this->figures['d'][2] = new Pawn(false);
        $this->figures['e'][2] = new Pawn(false);
        $this->figures['f'][2] = new Pawn(false);
        $this->figures['g'][2] = new Pawn(false);
        $this->figures['h'][2] = new Pawn(false);

        $this->figures['a'][7] = new Pawn(true);
        $this->figures['b'][7] = new Pawn(true);
        $this->figures['c'][7] = new Pawn(true);
        $this->figures['d'][7] = new Pawn(true);
        $this->figures['e'][7] = new Pawn(true);
        $this->figures['f'][7] = new Pawn(true);
        $this->figures['g'][7] = new Pawn(true);
        $this->figures['h'][7] = new Pawn(true);

        $this->figures['a'][8] = new Rook(true);
        $this->figures['b'][8] = new Knight(true);
        $this->figures['c'][8] = new Bishop(true);
        $this->figures['d'][8] = new Queen(true);
        $this->figures['e'][8] = new King(true);
        $this->figures['f'][8] = new Bishop(true);
        $this->figures['g'][8] = new Knight(true);
        $this->figures['h'][8] = new Rook(true);
    }

    /**
     * @param $move
     * @throws \Exception
     */
    public function move($moveString) {
        $move = MoveCoords::getFromString($this, $moveString);

        $figure = $this->getFigure($move->getFrom());
        if (!$this->checkMoveOrder($figure)) {
            throw new \Exception(
                'Wrong order of move, expect '.
                $this->getColorLabel($this->isBlackMove).
                ' get '.$this->getColorLabel($figure->getIsBlack()).
                ' on move '.$move
            );
        }

        $figure->canMove($this, $move);

        $xFrom = $move->getFrom()->getX();
        $yFrom = $move->getFrom()->getY();

        $xTo = $move->getTo()->getX();
        $yTo = $move->getTo()->getY();

        $this->figures[$xTo][$yTo] = $this->figures[$xFrom][$yFrom];
        $this->isBlackMove = $this->getNextMoveOrder();

        unset($this->figures[$xFrom][$yFrom]);
    }

    public function dump() {
        for ($y = 8; $y >= 1; $y--) {
            echo "$y ";
            for ($x = 'a'; $x <= 'h'; $x++) {
                if (isset($this->figures[$x][$y])) {
                    echo $this->figures[$x][$y];
                } else {
                    echo '-';
                }
            }
            echo "\n";
        }
        echo "  abcdefgh\n";
    }

    /**
     * @param DeskCoords $deskCoords
     * @return Figure|null
     */
    public function findFigure(DeskCoords $deskCoords) {
        $figure = null;
        $x = $deskCoords->getX();
        $y = $deskCoords->getY();

        if (isset($this->figures[$x][$y])) {
            $figure = $this->figures[$x][$y];
        }
        return $figure;
    }

    /**
     * @param DeskCoords $deskCoords
     * @return Figure
     *
     * @throws \Exception
     */
    public function getFigure(DeskCoords $deskCoords) {
        $figure = $this->findFigure($deskCoords);
        if (!$figure) {
            throw new \Exception('There is no figure on '.$deskCoords);
        }
        return $figure;
    }

    /**
     * @param Figure $figure
     * @return bool
     */
    public function checkMoveOrder(Figure $figure) {
        return $this->isBlackMove === $figure->getIsBlack();
    }

    /**
     * @param bool $isBlack
     * @return string
     */
    public function getColorLabel($isBlack) {
        $colors = ['White', 'Black'];
        return $colors[(bool) $isBlack];
    }

    /**
     * @return bool
     */
    public function getNextMoveOrder() {
        return $this->isBlackMove = !$this->isBlackMove;
    }
}
