<?php
/**
 * @copyright Copyright (c) 2017 Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\UserUsageReport;

use OCP\Files\FileInfo;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait Formatter {

	protected $timestamp;

	protected function printRecord(InputInterface $input, OutputInterface $output, $userId, array $report) {
		$separator = $input->getOption('field-separator');
		if ($this->timestamp === null) {
			$this->timestamp = date($input->getOption('date-format'));
		}

		$data = '"'. $userId . '"'. $separator;
		$data .= '"'. $this->timestamp . '"'. $separator;
		if ($input->getOption('last-login')) {
			$data .= '"'. date($input->getOption('date-format'), $report['login']) . '"'. $separator;
		}
		$data .= (!is_numeric($report['quota']) ? '"'. $report['quota'] . '"' : $report['quota']). $separator;
		$data .= (!is_numeric($report['used']) ? '"'. $report['used'] . '"' : $report['used']). $separator;
		$data .= $report['files'] . $separator;
		$data .= $report['shares'] . $separator;
		$data .= $report['uploads'] . $separator;
		$data .= $report['downloads'] . $separator;
	        $data .= '"' . $report['display_name'] . '"';
	
		$output->writeln($data);
	}

	public function humanFileSize($bytes) {
		if ($bytes < 0) {
			return FileInfo::SPACE_UNKNOWN;
		}

		if ($bytes < 1024) {
			return "$bytes B";
		}
		$bytes = round($bytes / 1024, 2);
		if ($bytes < 1024) {
			return "$bytes KB";
		}
		$bytes = round($bytes / 1024, 2);
		if ($bytes < 1024) {
			return "$bytes MB";
		}
		$bytes = round($bytes / 1024, 2);
		if ($bytes < 1024) {
			return "$bytes GB";
		}
		$bytes = round($bytes / 1024, 2);
		if ($bytes < 1024) {
			return "$bytes TB";
		}

		$bytes = round($bytes / 1024, 2);
		return "$bytes PB";
	}

}
