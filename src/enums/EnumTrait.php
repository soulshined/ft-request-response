<?php


namespace FT\RequestResponse\Enums;

/**
 *
 */
trait EnumTrait
{

    public static function values(): array
    {
        return array_map(fn ($v) => $v->value, self::cases());
    }

    public static function names(): array
    {
        return array_map(fn ($v) => $v->name, self::cases());
    }

    public static function tryFromName(string $value, bool $strict = false) : ?self {
        if (!$strict) $value = strtolower($value);

        foreach (self::cases() as $case) {
            $name = $strict ? $case->name : strtolower($case->name);

            if ($name === $value) return $case;
        }

        return null;
    }

}



?>