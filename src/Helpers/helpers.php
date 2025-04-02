<?php

function dd(...$args)
{
    var_dump(...$args);
    die();
}

/**
 * ChatGPT©
 * @param array $arrays
 * @return array
 */
function cartesianProduct(array $arrays): array
{
    return array_reduce(
        $arrays,
        function ($carry, $array) {
            $result = [];
            foreach ($carry as $c) {
                foreach ($array as $item) {
                    $result[] = array_merge($c, [$item]);
                }
            }
            return $result;
        },
        [[]],
    );
}
