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

    public static function diffDTOs(array $oldList, array $newList,string $key): array
    {
        // Re-index both lists by ID
        $oldMap = collect($oldList)->keyBy($key);
        $newMap = collect($newList)->keyBy($key);

        // Find added
        $added = $newMap->keys()->diff($oldMap->keys())->map(fn($id) => $newMap[$id])->values();

        // Find removed
        $removed = $oldMap->keys()->diff($newMap->keys())->map(fn($id) => $oldMap[$id])->values();

        // Find updated (exists in both but data changed)
        $updated = collect();
        foreach ($newMap as $id => $newDto) {
            if ($oldMap->has($id)) {
                $oldDto = $oldMap[$id];
                if ($newDto != $oldDto) { // shallow compare
                    $updated->push(['old' => $oldDto, 'new' => $newDto]);
                }
            }
        }

        return [
            'added'   => $added,
            'removed' => $removed,
            'updated' => $updated,
        ];
    }
}
