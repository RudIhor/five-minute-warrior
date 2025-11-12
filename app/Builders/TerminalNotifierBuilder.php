<?php

declare(strict_types=1);

namespace App\Builders;

final class TerminalNotifierBuilder extends Builder
{
    /** @var array<string, string> */
    private array $options = [];

    public static function make(): self
    {
        return new self();
    }

    public function setTitle(string $title): self
    {
        $this->addOption('title', $title);

        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->addOption('message', $message);

        return $this;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->addOption('subtitle', $subtitle);

        return $this;
    }

    public function setSound(string $sound = 'default'): self
    {
        $this->addOption('sound', $sound);

        return $this;
    }

    public function exec(): void
    {
        shell_exec($this->asString());
    }

    private function asString(): string
    {
        $result = '';

        foreach ($this->options as $key => $value) {
            $result .= ' '. $key . ' ' . "'$value'";
        }

        return 'terminal-notifier' . $result;
    }

    private function addOption(string $key, string $value): void
    {
        $this->options['-' . $key] = $value;
    }
}
