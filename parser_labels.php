<?php
//Объединение всех уникальных словарных помет слова внутри  толкований в одну строку
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


// Открытие файла labels.csv для чтения и записи
$labelsFile = fopen('../data/labels.csv', 'r');
$outputFile = fopen('../data/labels_parsed.csv', 'w');

// Инициализация переменных для хранения предыдущего id и слов
$prevId = "";
$prevWord1 = "";
$prevWords = "";

while (($row = fgetcsv($labelsFile, 0, '$')) !== false) {
    if (count($row) < 3) {
        continue; // Пропускаем строки с недостающими данными
    }

    $id = $row[0];
    $word1 = $row[1];
    $word2 = $row[2];

    if ($id == $prevId) {
        // Если текущий id совпадает с предыдущим, добавляем текущее слово2 к предыдущим словам
        $prevWords .= " " . $word2;
    } else {
        // Если id не совпадает, записываем предыдущую строку в файл вывода
        if ($prevId != "") {
            fputcsv($outputFile, array($prevId, $prevWord1, $prevWords),$separator="$");
        }
        // Обновляем значения переменных для новой строки
        $prevId = $id;
        $prevWord1 = $word1;
        $prevWords = $word2;
    }
}

// Записываем последнюю строку в файл вывода
if ($prevId != "") {
    fputcsv($outputFile, array($prevId, $prevWord1, $prevWords),$separator="$");
}

// Закрытие файлов
fclose($labelsFile);
fclose($outputFile);
