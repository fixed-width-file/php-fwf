<?php

namespace Kelsoncm\Fwf\Hydrating;

class HydrateUtils
{
    private static array $typeMap = [
        'char' => \Kelsoncm\Fwf\Columns\CharColumn::class,
        'right_char' => \Kelsoncm\Fwf\Columns\RightCharColumn::class,
        'positive_integer' => \Kelsoncm\Fwf\Columns\PositiveIntegerColumn::class,
        'positive_decimal' => \Kelsoncm\Fwf\Columns\PositiveDecimalColumn::class,
        'date' => \Kelsoncm\Fwf\Columns\DateColumn::class,
        'time' => \Kelsoncm\Fwf\Columns\TimeColumn::class,
        'datetime' => \Kelsoncm\Fwf\Columns\DateTimeColumn::class,

        // Python class names
        'pyfwf.columns.CharColumn' => \Kelsoncm\Fwf\Columns\CharColumn::class,
        'pyfwf.columns.RightCharColumn' => \Kelsoncm\Fwf\Columns\RightCharColumn::class,
        'pyfwf.columns.PositiveIntegerColumn' => \Kelsoncm\Fwf\Columns\PositiveIntegerColumn::class,
        'pyfwf.columns.PositiveDecimalColumn' => \Kelsoncm\Fwf\Columns\PositiveDecimalColumn::class,
        'pyfwf.columns.DateColumn' => \Kelsoncm\Fwf\Columns\DateColumn::class,
        'pyfwf.columns.TimeColumn' => \Kelsoncm\Fwf\Columns\TimeColumn::class,
        'pyfwf.columns.DateTimeColumn' => \Kelsoncm\Fwf\Columns\DateTimeColumn::class,
        'pyfwf.descriptors.HeaderRowDescriptor' => \Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor::class,
        'pyfwf.descriptors.DetailRowDescriptor' => \Kelsoncm\Fwf\Descriptors\DetailRowDescriptor::class,
        'pyfwf.descriptors.FooterRowDescriptor' => \Kelsoncm\Fwf\Descriptors\FooterRowDescriptor::class,
        'pyfwf.descriptors.RowDescriptor' => \Kelsoncm\Fwf\Descriptors\RowDescriptor::class,
        'pyfwf.descriptors.FileDescriptor' => \Kelsoncm\Fwf\Descriptors\FileDescriptor::class,

        // Java class names (legacy and io.github.kelsoncm)
        'com.kelsoncm.fwf.columns.CharColumn' => \Kelsoncm\Fwf\Columns\CharColumn::class,
        'com.kelsoncm.fwf.columns.RightCharColumn' => \Kelsoncm\Fwf\Columns\RightCharColumn::class,
        'com.kelsoncm.fwf.columns.PositiveIntegerColumn' => \Kelsoncm\Fwf\Columns\PositiveIntegerColumn::class,
        'com.kelsoncm.fwf.columns.PositiveDecimalColumn' => \Kelsoncm\Fwf\Columns\PositiveDecimalColumn::class,
        'com.kelsoncm.fwf.columns.DateColumn' => \Kelsoncm\Fwf\Columns\DateColumn::class,
        'com.kelsoncm.fwf.columns.TimeColumn' => \Kelsoncm\Fwf\Columns\TimeColumn::class,
        'com.kelsoncm.fwf.columns.DateTimeColumn' => \Kelsoncm\Fwf\Columns\DateTimeColumn::class,
        'com.kelsoncm.fwf.descriptors.HeaderRowDescriptor' => \Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor::class,
        'com.kelsoncm.fwf.descriptors.DetailRowDescriptor' => \Kelsoncm\Fwf\Descriptors\DetailRowDescriptor::class,
        'com.kelsoncm.fwf.descriptors.FooterRowDescriptor' => \Kelsoncm\Fwf\Descriptors\FooterRowDescriptor::class,
        'com.kelsoncm.fwf.descriptors.RowDescriptor' => \Kelsoncm\Fwf\Descriptors\RowDescriptor::class,
        'com.kelsoncm.fwf.descriptors.FileDescriptor' => \Kelsoncm\Fwf\Descriptors\FileDescriptor::class,

        'io.github.kelsoncm.fwf.columns.CharColumn' => \Kelsoncm\Fwf\Columns\CharColumn::class,
        'io.github.kelsoncm.fwf.columns.RightCharColumn' => \Kelsoncm\Fwf\Columns\RightCharColumn::class,
        'io.github.kelsoncm.fwf.columns.PositiveIntegerColumn' => \Kelsoncm\Fwf\Columns\PositiveIntegerColumn::class,
        'io.github.kelsoncm.fwf.columns.PositiveDecimalColumn' => \Kelsoncm\Fwf\Columns\PositiveDecimalColumn::class,
        'io.github.kelsoncm.fwf.columns.DateColumn' => \Kelsoncm\Fwf\Columns\DateColumn::class,
        'io.github.kelsoncm.fwf.columns.TimeColumn' => \Kelsoncm\Fwf\Columns\TimeColumn::class,
        'io.github.kelsoncm.fwf.columns.DateTimeColumn' => \Kelsoncm\Fwf\Columns\DateTimeColumn::class,
        'io.github.kelsoncm.fwf.descriptors.HeaderRowDescriptor' => \Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor::class,
        'io.github.kelsoncm.fwf.descriptors.DetailRowDescriptor' => \Kelsoncm\Fwf\Descriptors\DetailRowDescriptor::class,
        'io.github.kelsoncm.fwf.descriptors.FooterRowDescriptor' => \Kelsoncm\Fwf\Descriptors\FooterRowDescriptor::class,
        'io.github.kelsoncm.fwf.descriptors.RowDescriptor' => \Kelsoncm\Fwf\Descriptors\RowDescriptor::class,
        'io.github.kelsoncm.fwf.descriptors.FileDescriptor' => \Kelsoncm\Fwf\Descriptors\FileDescriptor::class,

        // Short names
        'CharColumn' => \Kelsoncm\Fwf\Columns\CharColumn::class,
        'RightCharColumn' => \Kelsoncm\Fwf\Columns\RightCharColumn::class,
        'PositiveIntegerColumn' => \Kelsoncm\Fwf\Columns\PositiveIntegerColumn::class,
        'PositiveDecimalColumn' => \Kelsoncm\Fwf\Columns\PositiveDecimalColumn::class,
        'DateColumn' => \Kelsoncm\Fwf\Columns\DateColumn::class,
        'TimeColumn' => \Kelsoncm\Fwf\Columns\TimeColumn::class,
        'DateTimeColumn' => \Kelsoncm\Fwf\Columns\DateTimeColumn::class,
        'HeaderRowDescriptor' => \Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor::class,
        'DetailRowDescriptor' => \Kelsoncm\Fwf\Descriptors\DetailRowDescriptor::class,
        'FooterRowDescriptor' => \Kelsoncm\Fwf\Descriptors\FooterRowDescriptor::class,
        'RowDescriptor' => \Kelsoncm\Fwf\Descriptors\RowDescriptor::class,
        'FileDescriptor' => \Kelsoncm\Fwf\Descriptors\FileDescriptor::class,
    ];

    public static function resolveClassName(string|array $typeOrMap, ?string $hintProp = null): string
    {
        if (is_string($typeOrMap)) {
            if (isset(self::$typeMap[$typeOrMap])) {
                return self::$typeMap[$typeOrMap];
            }
            if (class_exists($typeOrMap)) {
                return $typeOrMap;
            }
            throw new \InvalidArgumentException("Cannot resolve class name: {$typeOrMap}");
        }

        if (isset($typeOrMap['class_name'])) {
            return self::resolveClassName($typeOrMap['class_name'], $hintProp);
        }
        if (isset($typeOrMap['type'])) {
            return self::resolveClassName($typeOrMap['type'], $hintProp);
        }
        if (isset($typeOrMap['details'])) {
            return \Kelsoncm\Fwf\Descriptors\FileDescriptor::class;
        }
        if (isset($typeOrMap['columns'])) {
            if ($hintProp === 'header' || (isset($typeOrMap['row_type']) && $typeOrMap['row_type'] === 'header')) {
                return \Kelsoncm\Fwf\Descriptors\HeaderRowDescriptor::class;
            }
            if ($hintProp === 'footer' || (isset($typeOrMap['row_type']) && $typeOrMap['row_type'] === 'footer')) {
                return \Kelsoncm\Fwf\Descriptors\FooterRowDescriptor::class;
            }
            return \Kelsoncm\Fwf\Descriptors\DetailRowDescriptor::class;
        }

        throw new \InvalidArgumentException("Representation map must contain 'class_name', 'type', 'details', or 'columns'.");
    }

    public static function hydrateObject(array $repr, ?string $hintProp = null): object
    {
        $className = self::resolveClassName($repr, $hintProp);
        $reflection = new \ReflectionClass($className);

        /** @var array $argsSpec */
        $argsSpec = $className::$hydratingArgs ?? [];
        $constructorArgs = [];

        foreach ($argsSpec as $argName) {
            $value = $repr[$argName] ?? null;

            if ($value === null) {
                $constructorArgs[] = null;
                continue;
            }

            if (is_array($value)) {
                if (isset($value['class_name']) || isset($value['type']) || isset($value['columns']) || isset($value['details'])) {
                    $value = self::hydrateObject($value, $argName);
                } else {
                    $hydratedList = [];
                    foreach ($value as $item) {
                        if (is_array($item)) {
                            $hydratedList[] = self::hydrateObject($item, $argName);
                        } else {
                            $hydratedList[] = $item;
                        }
                    }
                    $value = $hydratedList;
                }
            }

            $constructorArgs[] = $value;
        }

        return $reflection->newInstanceArgs($constructorArgs);
    }

    public static function dehydrateObject(object $obj): array
    {
        $className = get_class($obj);
        $result = [
            'class_name' => $className,
        ];

        /** @var array $argsSpec */
        $argsSpec = $className::$hydratingArgs ?? [];

        foreach ($argsSpec as $argName) {
            $getterName = 'get' . str_replace('_', '', ucwords($argName, '_'));
            if (method_exists($obj, $getterName)) {
                $val = $obj->$getterName();
            } else {
                $prop = new \ReflectionProperty($className, $argName);
                $prop->setAccessible(true);
                $val = $prop->getValue($obj);
            }

            if (is_object($val)) {
                $result[$argName] = self::dehydrateObject($val);
            } elseif (is_array($val)) {
                $dehydratedArray = [];
                foreach ($val as $item) {
                    if (is_object($item)) {
                        $dehydratedArray[] = self::dehydrateObject($item);
                    } else {
                        $dehydratedArray[] = $item;
                    }
                }
                $result[$argName] = $dehydratedArray;
            } else {
                $result[$argName] = $val;
            }
        }

        return $result;
    }
}
