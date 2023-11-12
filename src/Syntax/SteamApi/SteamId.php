<?php

namespace Syntax\SteamApi;

use Syntax\SteamApi\Exceptions\UnrecognizedId;

trait SteamId
{
    public $formatted;

    private $rawValue;

    private static $ID32 = 'id32';

    private static $ID64 = 'id64';

    private static $ID3 = 'id3';

    private static $id64Base = '76561197960265728';

    /**
     * @param string|int  $id
     * @param string|null $format
     *
     * @return mixed
     */
    public function convertId($id, $format = null)
    {
        $this->convertToAll($id);

        switch ($format) {
            case 'ID32':
            case 'id32':
            case 32:
                return $this->formatted->{self::$ID32};
            case 'ID64':
            case 'id64':
            case 64:
                return $this->formatted->{self::$ID64};
            case 'ID3':
            case 'id3':
            case 3:
                return $this->formatted->{self::$ID3};
            default:
                return $this->formatted;
        }
    }

    protected function setUpFormatted()
    {
        $this->formatted                = new \stdClass();
        $this->formatted->{self::$ID32} = null;
        $this->formatted->{self::$ID64} = null;
        $this->formatted->{self::$ID3}  = null;
    }

    private function convertToAll($id)
    {
        [$type, $matches] = $this->determineIDType($id);

        $this->getRawValue($id, $type, $matches);

        // Convert to each type
        $this->convertToID32();
        $this->convertToID64();
        $this->convertToID3();

        return $this->formatted;
    }

    private function convertToID32()
    {
        $z                              = bcdiv($this->rawValue, '2', 0);
        $y                              = bcmul($z, '2', 0);
        $y                              = bcsub($this->rawValue, $y, 0);
        $formatted                      = "STEAM_1:$y:$z";
        $this->formatted->{self::$ID32} = $formatted;
    }

    private function convertToID64()
    {
        $formatted                      = bcadd($this->rawValue, self::$id64Base, 0);
        $this->formatted->{self::$ID64} = $formatted;
    }

    private function convertToID3()
    {
        $formatted                     = "[U:1:$this->rawValue]";
        $this->formatted->{self::$ID3} = $formatted;
    }

    private function determineIDType($id)
    {
        $id = trim((string) $id);

        if (preg_match('/^STEAM_[0-1]:([0-1]):([0-9]+)$/', $id, $matches)) {
            return ['ID32', $matches];
        }
        if (preg_match('/^[0-9]+$/', $id)) {
            return ['ID64', null];
        }
        if (preg_match('/^\[U:1:([0-9]+)\]$/', $id, $matches)) {
            return ['ID3', $matches];
        }

        throw new UnrecognizedId('Id [' . $id . '] is not recognized as a steam id.');
    }

    /**
     * Get a raw value from any type of steam id
     *
     * @param $id
     * @param $type
     * @param $matches
     */
    private function getRawValue($id, $type, $matches)
    {
        switch ($type) {
            case 'ID32':
                $this->rawValue = bcmul((string) $matches[2], '2', 0);
                $this->rawValue = bcadd($this->rawValue, (string) $matches[1], 0);

                $this->formatted->{self::$ID32} = $id;

                break;
            case 'ID64':
                $this->rawValue = bcsub((string) $id, self::$id64Base, 0);

                $this->formatted->{self::$ID64} = $id;

                break;
            case 'ID3':
                $this->rawValue = $matches[1];

                $this->formatted->{self::$ID3} = $id;

                break;
        }
    }

}
