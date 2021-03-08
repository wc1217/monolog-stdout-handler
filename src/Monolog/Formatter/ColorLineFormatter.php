<?php

namespace Monolog\Formatter;

use Monolog\Logger;

/**
 * Formats incoming records into a one-line colored string
 */
class ColorLineFormatter extends LineFormatter {
    const NONE_COLOR = 0;

    private $colors = array(
        'notice' => 42, //white-green
        'error' => 31, //red
        'debug' => 32, //green
        'warning' => 33, //yellow
        'info' => 34, //blue
        'critical' => 35, //purple
        'emergency' => 36, //cyan
        'alert' => 37, //white
    );

    /**
     * {@inheritdoc}
     */
    public function format(array $record): string {
        $output = parent::format($record);
        if (!$this->detectColors()) {
            return $output;
        }
        $level = $record['level'] ?? 'debug';
        $valueParameter = strtolower(Logger::getLevelName($level));
        return $this->applyBeginningColor($valueParameter) . $output . $this->applyEndingColor();
    }

    private function applyBeginningColor($valueParameter): string {
        return $this->renderShellColor($this->getColorByName($valueParameter));
    }

    private function applyEndingColor(): string {
        return $this->renderShellColor(self::NONE_COLOR);
    }

    /**
     * Returns the shell color id for a specified color name or the default none color
     * @param string $name
     * @return int
     */
    private function getColorByName(string $name): int {
        if (isset($this->colors[$name])) {
            return $this->colors[$name];
        }

        return self::NONE_COLOR;
    }

    /**
     * Render the shell color code
     * @param integer $id
     * @return string
     * @throws \LogicException
     */
    private function renderShellColor(int $id): string {
        if (!is_int($id)) {
            throw new \LogicException('Unable to render the shell color.');
        }

        return sprintf(
            chr(27) . '[%dm',
            $id
        );
    }

    /**
     * @return bool
     */
    public function detectColors(): bool {
        return (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg')
            && stream_isatty(STDOUT)
            && getenv('NO_COLOR') === false // https://no-color.org
            && (defined('PHP_WINDOWS_VERSION_BUILD')
                ? (function_exists('sapi_windows_vt100_support') && sapi_windows_vt100_support(STDOUT))
                || getenv('ConEmuANSI') === 'ON' // ConEmu
                || getenv('ANSICON') !== false // ANSICON
                || getenv('term') === 'xterm' // MSYS
                || getenv('term') === 'xterm-256color' // MSYS
                : true);
    }
}
