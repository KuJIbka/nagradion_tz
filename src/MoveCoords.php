<?php

class MoveCoords {
    protected $from;
    protected $to;

    /** @var Desk */
    protected $desk;

    /**
     * MoveCoords constructor.
     * @param Desk $desk
     * @param DeskCoords $from
     * @param DeskCoords $to
     * @throws Exception
     */
    public function __construct(Desk $desk, DeskCoords $from, DeskCoords $to) {
        $this->setFrom($from)
            ->setTo($to);
        $this->desk = $desk;

        if ($from->isSameSpot($to)) {
            throw new \Exception('Can not move to the same spot move: '.$this);
        }
    }

    /**
     * @param string $stringMove
     * @return self
     * @throws Exception
     */
    static public function getFromString(Desk $desk, $stringMove) {
        $splits = explode('-', $stringMove);
        $from = DeskCoords::createFromString($splits[0]);
        $to = DeskCoords::createFromString($splits[1]);
        $move = new self($desk, $from, $to);
        return $move;
    }

    /**
     * @return DeskCoords
     */
    public function getFrom() {
        return $this->from;
    }

    /**
     * @param DeskCoords $from
     * @return self
     */
    public function setFrom(DeskCoords $from) {
        $this->from = $from;
        return $this;
    }

    /**
     * @return DeskCoords
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * @param DeskCoords $to
     * @return self
     */
    public function setTo(DeskCoords $to) {
        $this->to = $to;
        return $this;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getXDiff() {
        return $this->getTo()->getXAsNum() - $this->getFrom()->getXAsNum();
    }

    /**
     * @return int
     */
    public function getYDiff() {
        return $this->getTo()->getY() - $this->getFrom()->getY();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isLine() {
        $xDiff = $this->getXDiff();
        $yDiff = $this->getYDiff();

        return  abs($xDiff) === abs($yDiff)
            || ($xDiff === 0 && abs($yDiff) > 0)
            || (abs($xDiff) > 0 && $yDiff === 0)
        ;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isFreeLine() {
        $result = false;
        if ($this->isLine()) {
            $diffX = abs($this->getXDiff());
            $diffY = abs($this->getYDiff());

            if ($this->getFrom()->getX() > $this->getTo()->getX()) {
                $startX = $this->getTo()->getX();
                $endX = $this->getFrom()->getX();

                $startY = $this->getTo()->getY();
                $endY = $this->getFrom()->getY();
            } else {
                $startX = $this->getFrom()->getX();
                $endX = $this->getTo()->getX();

                $startY = $this->getFrom()->getY();
                $endY = $this->getTo()->getY();
            }

            $isFree = true;
            $x = $startX;
            $y = $startY;
            $yIncr = $endY > $startY ? 1 : -1;

            while (1) {
                if (!($x === $this->getFrom()->getX() && $y == $this->getFrom()->getY())) {
                    if ($this->desk->findFigure(new DeskCoords($x, $y))) {
                        $isFree = false;
                        break;
                    }
                }

                # vertical
                if ($diffX === 0 && $diffY > 0) {
                    $y += $yIncr;
                }
                # horizontal
                if ($diffX > 0 && $diffY === 0) {
                    $x++;
                }
                # diagonal
                if ($diffX === $diffY && $diffX > 0 && $diffY > 0) {
                    $x++;
                    $y += $yIncr;
                }

                if ($x === $endX && $y === $endY) {
                    break;
                }
            }
            $result = $isFree;
        }
        return $result;
    }

    public function __toString()
    {
        return $this->getFrom()->getX().$this->getFrom()->getY().'-'.
            $this->getTo()->getX().$this->getTo()->getY();
    }
}
