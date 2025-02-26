<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class GameLogic {
	/**
	 * Таблица выплат:
	 *  - Ключ массива = символ (wolf, bull, cougar, eagle)
	 *  - Вложенный массив: [3 => X, 4 => Y, 5 => Z]
	 */
	private $paytable = [
		'wolf'   => [3 => 50,  4 => 100, 5 => 200],
		'bull'   => [3 => 25,  4 => 50,  5 => 100],
		'cougar' => [3 => 10,  4 => 20,  5 => 40 ],
		'eagle'  => [3 => 5,   4 => 10,  5 => 20 ],
	];

	/**
	 * Модель барабанов (каждый барабан — массив символов).
	 * Для простоты сделаем одинаковые барабаны.
	 */
	private $reels = [
		['wolf', 'bull', 'cougar', 'eagle', 'wolf', 'bull', 'cougar', 'eagle'],
		['wolf', 'bull', 'cougar', 'eagle', 'wolf', 'bull', 'cougar', 'eagle'],
		['wolf', 'bull', 'cougar', 'eagle', 'wolf', 'bull', 'cougar', 'eagle'],
		['wolf', 'bull', 'cougar', 'eagle', 'wolf', 'bull', 'cougar', 'eagle'],
		['wolf', 'bull', 'cougar', 'eagle', 'wolf', 'bull', 'cougar', 'eagle'],
	];

	/**
	 * Описание линий:
	 * - массив координат (column, row),
	 *   где row=0 — верхняя строка, row=1 — средняя, row=2 — нижняя (всего 3 строки).
	 * Для примера у нас 3 линии: верхняя, средняя, нижняя.
	 */
	private $lines = [
		[[0,0], [1,0], [2,0], [3,0], [4,0]],
		[[0,1], [1,1], [2,1], [3,1], [4,1]],
		[[0,2], [1,2], [2,2], [3,2], [4,2]]
	];

	/**
	 * Метод для совершения "спина" — возвращает:
	 *  [
	 *    'grid' => массив 5x3 (col x row) символов,
	 *    'win_lines' => список выигравших линий (line_id, symbol, count, payout),
	 *    'total_win' => суммарный выигрыш
	 *  ]
	 */
	public function spin() {
		$grid = [];
		for ($col = 0; $col < 5; $col++) {
			$col_array = [];
			for ($row = 0; $row < 3; $row++) {
				$col_array[] = $this->reels[$col][array_rand($this->reels[$col])];
			}
			$grid[] = $col_array;
		}

		$win_lines = [];
		$total_win = 0;

		foreach ($this->lines as $line_idx => $lineCoords) {
			$symbolsOnLine = [];
			foreach ($lineCoords as $pos) {
				$col = $pos[0];
				$row = $pos[1];
				$symbolsOnLine[] = $grid[$col][$row];
			}

			$firstSymbol = $symbolsOnLine[0];
			$matchCount = 1;
			for ($i = 1; $i < 5; $i++) {
				if ($symbolsOnLine[$i] === $firstSymbol) {
					$matchCount++;
				} else {
					break;
				}
			}

			if ($matchCount >= 3 && isset($this->paytable[$firstSymbol][$matchCount])) {
				$win = $this->paytable[$firstSymbol][$matchCount];
				$total_win += $win;
				$win_lines[] = [
					'line_id' => $line_idx,
					'symbol'  => $firstSymbol,
					'count'   => $matchCount,
					'payout'  => $win
				];
			}
		}

		return [
			'grid'      => $grid,
			'win_lines' => $win_lines,
			'total_win' => $total_win
		];
	}
}
