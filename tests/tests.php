<?php
	define('DIR', DIRECTORY_SEPARATOR);
	define('MAIN_DIR', dirname(__FILE__, 2));
	error_reporting(E_ALL | E_STRICT);
	ini_set("display_errors", true);
	require MAIN_DIR.DIR."HTTPHeader.php";
	use xenocrat\HTTPHeader;

	ob_start();
	header("Content-Type: text/html; charset=UTF-8");

	function scan_data(): array {
		$dirs = new DirectoryIterator(dirname(__FILE__).DIR.'data');
		$data = array();

		foreach ($dirs as $dir) {
			if (!$dir->isDot() && $dir->isDir()) {
				$method = $dir->getFilename();
				$tests = new DirectoryIterator($dir->getPathname());

				foreach ($tests as $test) {
					if ($test->isFile()) {
						$filepath = $test->getPathname();
						$extension = $test->getExtension();
						$basename = $test->getBasename('.'.$extension);

						switch ($extension) {
							case 'source':
								$contents = @file_get_contents($filepath);

								if ($contents !== false) {
									$data[$method][$basename]['source'] = $contents;
								}

								break;

							case 'expect':
								$contents = @file_get_contents($filepath);

								if ($contents !== false) {
									$data[$method][$basename]['expect'] = $contents;
								}

								break;
						}
					}
				}
			}
		}

		return $data;
	}

	function run_tests($data): array {
		foreach ($data as $method => $tests) {
			foreach ($tests as $number => $values) {
				if (!method_exists("xenocrat\HTTPHeader", $method))
					continue;

				if (!array_key_exists('source', $values) ||
					!array_key_exists('expect', $values)) {
					continue;
				}

				$source = $values['source'];
				$expect = $values['expect'];

				ob_start();
				var_dump(HTTPHeader::$method($source));

				$result = ob_get_clean();
				$passed = strcmp($expect, $result) === 0;

				$data[$method][$number]['result'] = $result;
				$data[$method][$number]['passed'] = $passed;
			}
		}

		return $data;
	}

	function display_results($data): void {
		foreach ($data as $method => $tests) {
			echo '<table>'."\n";
			echo '<colgroup>'."\n";
			echo '<col>'."\n";
			echo '<col span="3">'."\n";
			echo '</colgroup>'."\n";
			echo '<tr>'."\n";
			echo '<th colspan="4">'.$method.'</th>';
			echo '</tr>'."\n";
			echo '<tr>'."\n";
			echo '<th>#</th>'."\n";
			echo '<th scope="col">Source</th>'."\n";
			echo '<th scope="col">Expect</th>'."\n";
			echo '<th scope="col">Result</th>'."\n";
			echo '</tr>'."\n";
			echo '<tbody>'."\n";

			$test_total = 0;
			$pass_total = 0;

			foreach ($tests as $number => $values) {
				if (!array_key_exists('source', $values) ||
					!array_key_exists('expect', $values) ||
					!array_key_exists('result', $values) ||
					!array_key_exists('passed', $values)) {
					continue;
				}

				$test_total++;

				$source = $values['source'];
				$expect = $values['expect'];
				$result = $values['result'];
				$passed = $values['passed'];

				if ($passed) {
					$pass_total++;
				}

				$class = $passed ? 'pass' : 'fail';
				$number = htmlspecialchars($number, ENT_HTML5, "UTF-8", true);
				$source = htmlspecialchars($source, ENT_HTML5, "UTF-8", true);
				$expect = htmlspecialchars($expect, ENT_HTML5, "UTF-8", true);
				$result = htmlspecialchars($result, ENT_HTML5, "UTF-8", true);

				echo '<tr>'."\n";
				echo '<td>'."\n".$number.'</td>'."\n";

				echo '<td>'
					. '<pre>'."\n".$source.'</pre>'
					. '</td>'."\n";

				echo '<td class="'.$class.'">'
					. '<pre>'."\n".$expect.'</pre>'
					. '</td>'."\n";

				echo '<td class="'.$class.'">'
					. '<pre>'."\n".$result.'</pre>' 
					. '</td>'."\n";

				echo '</tr>'."\n";
			}

			echo '</tbody>'."\n";
			echo '<tfoot>'."\n";
			echo '<tr>'."\n";
			echo '<th scope="row" colspan="2">Totals</th>'."\n";
			echo '<td>'."\n".$test_total.'</td>'."\n";
			echo '<td>'."\n".$pass_total.'</td>'."\n";
			echo '</tr>'."\n";
			echo '</tfoot>'."\n";
			echo '</table>'."\n";
		}
	}

	#---------------------------------------------
	# Output Starts
	#---------------------------------------------
?>
<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			body {
				font-family: sans-serif;
				tab-size: 4;
				margin: 0.5rem;
			}
			table {
				table-layout: fixed;
				width: 100%;
				margin: 0.5rem 0rem;
				border-collapse: collapse;
			}
			col:first-child {
				width: calc(3ch + 2rem);
			}
			tr {
				background-color: #efefef;
			}
			th {
				background-color: #dfdfdf;
				vertical-align: middle;
				padding: 0.5rem;
				border: 1px solid #000000;
			}
			td {
				font-family: monospace;
				vertical-align: top;
				padding: 1rem;
				border: 1px solid #000000;
			}
			td.pass {
				background-color: #ebfae4;
			}
			td.fail {
				background-color: #faebe4;
			}
			pre {
				margin: 0rem;
				white-space: break-spaces;
			}
		</style>
	</head>
	<body><?php

	display_results(run_tests(scan_data()));

	?></body>
</html>
