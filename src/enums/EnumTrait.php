<?php


namespace FT\RequestResponse\Enums;

use BackedEnum;

trait EnumTrait
{

    public static function values(): array
    {
        if (!(is_subclass_of(__CLASS__, BackedEnum::class)))
            return array_map(fn ($i) => $i->name, self::cases());

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

    public static function tryFromValue(string $value, bool $strict = false) : ?self {
        if (!$strict) $value = strtolower($value);

        $target = 'value';
        if (!(is_subclass_of(__CLASS__, BackedEnum::class)))
            $target = 'name';

        foreach (self::cases() as $case) {
            $name = $strict ? $case->{$target} : strtolower($case->{$target});

            if ($name === $value) return $case;
        }

        return null;
    }

}



?>