<?php

namespace App\Utility;

class UltilityClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function diffDTOs(array $oldList, array $newList,string $oldKey,string $newKey): array
    {
        //get the values of the specified keys from both lists
        $oldData = self::getArrayOfValues($oldList, $oldKey);
        $newData = self::getArrayOfValues($newList, $newKey);
        // Re-index both lists by ID
        $oldMap = collect($oldList)->keyBy($oldKey);
        $newMap = collect($newList)->keyBy($newKey);
        // Find added
        $added = array_diff($newData, $oldData);

        // Find removed
        $removed = array_diff($oldData, $newData);

        // Find updated (exists in both but data changed)
        $updated = [];
        foreach ($newMap as $id => $newDto) {
            if ($oldMap->has($id)) {
                $oldDto = $oldMap[$id];
                if ($newDto != $oldDto) { // shallow compare
                    $updated[]=[$id];
                }
            }
        }

        return [
            'added'   => $added,
            'removed' => $removed,
            'updated' => $updated,
        ];
    }

    public static function filterDTO(array $oldList,string $key,string $value): array
    {
        $newList = collect($oldList)->filter(function ($item) use ($key, $value) {
            return isset($item[$key]) && $item[$key] == $value;
        })->values()->toArray();
        return $newList;
    }

    public static function getArrayOfValues(array $array, string $key): array
    {
        return collect($array)->pluck($key)->toArray();
    }
}
