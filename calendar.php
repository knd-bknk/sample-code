<?php

/**
 * カレンダー情報の作成
 *
 * @param DateTime $firstDate
 *
 * @return array
 */
function generateCalendar(DateTime $firstDate): array
{
    $calendar = [];
    $row = 0;

    // 見出し（曜日）
    $headList = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
    foreach ($headList as $day) {
        $calendar[$row][] = generateCell($day);
    }

    // 次の行へ
    $row++;

    // 月初の曜日（0:日、1:月、...）
    $dayOfWeek = $firstDate->format('w');

    // 1行目の空白列を追加
    for ($i = 0; $i < $dayOfWeek; $i++) {
        $calendar[$row][] = generateCell('');
    }

    // 月末
    $lastDay = $firstDate->format('t');

    // 日付の列を追加
    for ($date = 1; $date <= $lastDay; $date++) {
        $calendar[$row][] = generateCell($date);

        if (count($calendar[$row]) % 7 === 0) {
            // 1週間分追加したら次の行へ
            $row++;
        }
    }

    return $calendar;
}

/**
 * セルの作成
 *
 * @param string $text
 *
 * @return string
 */
function generateCell(string $text): string
{
    // 前後に空白を入れる
    return sprintf('%4s%s', $text, ' ');
}

/**
 * カレンダーを出力
 *
 * @param array $calendar
 *
 * @return void
 */
function displayCalendar(array $calendar): void
{
    foreach ($calendar as $i => $row) {
        echo implode('|', $row) . "\n";

        // 1行目（見出し）のあとに区切り線を追加
        if ($i === 0) {
            echo "----------------------------------------\n";
        }
    }
}

$errMsg = "yyyy-mm 形式で入力してください\n";
$input = $argv[1] ?? null;

# 未入力の場合処理終了
if (is_null($input)) {
    exit($errMsg);
}

try {
    // yyyy-mm-01 00:00:00 で取得
    $firstDate = new DateTime($input, new DateTimeZone('Asia/Tokyo'));

    if ($firstDate->format('d') !== '01') {
        // もし「日」まで入力されていた場合、強制的に「1日」に変更する
        $firstDate->setDate($firstDate->format('Y'), $firstDate->format('m'), '01');
    }
} catch (Exception $e) {
    exit($errMsg);
}

// カレンダー作成＆出力
$calendar = generateCalendar($firstDate);
displayCalendar($calendar);
