<?php

/*
 * Файл содержит два варианта функции
 */

/* Вариант 1 - Поразрядное сложение */
function sum(string $firstNumber, string $secondNumber)
{
    $result = '';

    $length1 = strlen($firstNumber);
    $length2 = strlen($secondNumber);
    $remainder = 0;

    $loopCounter = max($length1, $length2);
    for ($i = 1; $i <= $loopCounter; ++$i) {
        /*
         * решение вида $firstNumber[$length1 - $i] ?? 0 на ряде интерпретаторов выдавало кривой результат
         * т.к. -1 индекс иногда выдавал лежащее под индексом 0, проверял тут: http://sandbox.onlinephpfunctions.com/
         * кейс, например: sum('1', '92233720368547758079223372036854775807')
         * */
        $number1 = $length1 >= $i ? (int)$firstNumber[$length1 - $i] : 0;
        $number2 = $length2 >= $i ? (int)$secondNumber[$length2 - $i] : 0;

        $tempSum = $number1 + $number2 + $remainder;
        $remainder = (int) ($tempSum > 9);

        $result = ($tempSum % 10) . $result;
    }

    if ($remainder) {
        $result = $remainder . $result;
    }

    return $result;
}

/* Вариант 2 - Решение с ноября, складывание чанками, упрощенное, на базе массивов */
function sumOfVeryLargeNumbers(string $firstNumber, string $secondNumber)
{
    /**
     * 32 бит INT - 2147483647 - 9 символов группа может быть
     * 64 бит INT - 9223372036854775807 - 18 символов группа может быть
     */

    $maxNumberLength = (PHP_INT_SIZE === 8) ? 18 : 9;

    $result = [];
    $remainder = 0;

    while ($firstNumber || $secondNumber) {
        $lastSymbolsOfFirstNumber = mb_substr($firstNumber, -$maxNumberLength);
        $lastSymbolsOfSecondNumber = mb_substr($secondNumber, -$maxNumberLength);

        $tempSum = (string)((int)$lastSymbolsOfFirstNumber + (int)$lastSymbolsOfSecondNumber + $remainder);
        $remainder = (mb_strlen($tempSum) === $maxNumberLength + 1) ? 1 : 0;

        //складываем только часть без возможного остатка
        $result[] = mb_substr($tempSum, -$maxNumberLength);

        $firstNumber = mb_substr($firstNumber, 0, -$maxNumberLength);
        $secondNumber = mb_substr($secondNumber, 0, -$maxNumberLength);
    }

    if ($remainder) {
        $result[] = 1;
    }

    return  implode('', array_reverse($result));
}