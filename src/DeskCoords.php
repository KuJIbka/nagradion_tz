<?php

class DeskCoords {
    protected $x;
    protected $y;

    public function __construct($x, $y) {
        $this->setX($x)
            ->setY($y);
    }

    /**
     * @param string $stringCoords
     * @return DeskCoords
     * @throws Exception
     */
    static public function createFromString($stringCoords) {
        if (!preg_match('/^([a-h])([1-8])$/', $stringCoords, $match)) {
            throw new \Exception('Incorrect coords: '.$stringCoords);
        }
        $descCoords = new self($match[1], $match[2]);
        return $descCoords;
    }

    /**
     * @return string
     */
    public function getX() {
        return $this->x;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getXAsNum() {
        switch ($this->getX()) {
            case 'a': return 1;
            case 'b': return 2;
            case 'c': return 3;
            case 'd': return 4;
            case 'e': return 5;
            case 'f': return 6;
            case 'g': return 7;
            case 'h': return 8;
            default: throw new \Exception('There is no field on desk as '.$this->getX());
        }
    }

    /**
     * @param string $x
     * @return self
     */
    public function setX($x) {
        $this->x = (string) $x;
        return $this;
    }

    /**
     * @return int
     */
    public function getY() {
        return $this->y;
    }

    /**
     * @param int  $y
     * @return self
     */
    public function setY($y) {
        $this->y = (int) $y;
        return $this;
    }

    /**
     * @param DeskCoords $deskCoords
     * @return bool
     */
    public function isSameSpot(DeskCoords $deskCoords) {
        return $this->getX() === $deskCoords->getX() && $this->getY() === $deskCoords->getY();
    }

    public function __toString() {
        return $this->getX().$this->getY();
    }
}
