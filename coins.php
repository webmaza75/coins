<?php
/**
 * 8 монет, найти 1 монету отличным весом
 */

/**
 * Подготовка монет с разным весом
 */
$nCoins = 8; // всего монет (по идее, подходит любое количество из геометрической прогрессии по знаменателю 2)
$equalWeight = rand(2, 7); // вес одинаковых монет

function doOtherWeight($equalWeight)
{
    do {
        $otherWeight = rand(2, 7); // вес отличающейся монеты
    } while ($otherWeight == $equalWeight);
    return $otherWeight;
}

// Заполнение массива 7-ю одинаковыми монетами
$coinsArray = array_fill(0, $nCoins - 1, $equalWeight);

// Добавление еще одной монеты, отличной от других
$coinsArray[] = doOtherWeight($equalWeight);

// Перемешивание монет в массиве
shuffle($coinsArray);

// Массивы, к которым можно вернуться в случае выбора не той кучи
$prevLeftArray = $coinsArray; // левая куча
$prevRightArray = $coinsArray; //правая куча

searchCoin($nCoins, $coinsArray);

$count = 0; // Количество шагов (итераций) поиска

/**
 * @param $nCoins - количество монет в исследуемой куче
 * @param $coinsArray - куча монет перед взвешиванием
 * @param string $target - направление (left или right) при выборе кучи
 * @param string $search - сначала ищем монету меньшим весом (min), если ошиблись - c бОльшим (max)
 * @todo - Монеты делим на 2 равные кучи и взвешиваем каждую.
 * Исследуем кучу монет с меньшим весом, предполагая, что именно там искомая монета.
 * Снова делим выбранную кучу пополам и взвешиваем.
 * Если вес куч одинаковый, то выбираем противоположную кучу с предыдущего шага и теперь ищем монету с бОльшим весом.
 * Когда монета найдена, вызываем функцию, которая показывает весь исходный массив,
 * вес монеты и ее положение в этом массиве.
 */
function searchCoin($nCoins, $coinsArray, $target = 'left', $search = 'min') {
    static $count;
    $count++;
    global $prevLeftArray;
    global $prevRightArray;

    $nCoins >>= 1; // Делим исходную кучу пополам
    $leftArray = array_slice($coinsArray, 0, $nCoins); // Левая куча с монетами
    $rightArray = array_slice($coinsArray, $nCoins, $nCoins); // Правая куча с монетами

    $leftSum = array_sum($leftArray); // Общий вес левой кучи
    $rightSum = array_sum($rightArray); // Общий вес правой кучи

    if ($leftSum < $rightSum) {
        if ($search == 'min') { // По умолчанию ищем меньший вес монеты
            $target = 'left'; // По умолчанию ищем слева
            $currentArray = $leftArray;
        } else {
            $target = 'right'; // Меняем курс, ищем справа
            $currentArray = $rightArray;
        }
        $prevLeftArray = $leftArray; // Сохраняем состояние левой кучи в случае неудачного деления правой
        $prevRightArray = $rightArray; // Сохраняем состояние правой кучи в случае неудачного деления левой

        if ($nCoins == 1) { // Нашли искомую монету
            getCoinID ($currentArray[0], $count); // Выводим вес искомой монеты и ее положение в исходном массиве
            exit;
        }

    } elseif ($leftSum > $rightSum) {
        if ($search == 'min') {
            $target = 'right';
            $currentArray = $rightArray; // Для деления выбираем правую кучу
        }
        else {
            $target = 'left';
            $currentArray = $leftArray; // Для деления выбираем левую кучу
        }
        $prevLeftArray = $leftArray;
        $prevRightArray = $rightArray;

        if ($nCoins == 1) { // Нашли искомую монету
            getCoinID ($currentArray[0], $count); // Выводим вес искомой монеты и ее положение в исходном массиве
            exit;
        }

    } else {
        $search = 'max'; // Искомая монета бОльшего, а не меньшего веса

        if ($target == 'left') {
            $target = 'right'; // Меняем направления движения
            $currentArray = $prevRightArray; // Содержимое противоположной кучи на итерацию ранее
            $nCoins <<= 1; // Возвращаемся на шаг назад (количество монет * 2)
        } else {
            $target = 'left';
            $currentArray = $prevLeftArray;
            $nCoins <<= 1;
        }
    }
    searchCoin($nCoins, $currentArray, $target, $search); // Вызываем эту же функцию (рекурсия)
}

function getCoinID ($value, $count) {
    global $coinsArray;
    $id = array_search($value, $coinsArray);
    echo '<pre>'.var_export($coinsArray, TRUE).'</pre>';
    echo 'Weight of coin = '.$value.'<br>'.PHP_EOL;
    echo 'Id of coin in array = '.$id.'<br>'.PHP_EOL;
    echo 'Iterations = '.$count.'<br>'.PHP_EOL;
}





