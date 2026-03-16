<?php

declare(strict_types=1);

namespace Rearm;

class Rearm
{
    private int $attempts = 1;
    private int $sleep = 0;
    private bool $exponential = false;

    public static function times(int $attempts): self
    {
        $instance = new self();
        $instance->attempts = $attempts;

        return $instance;
    }

    public function sleep(int $milliseconds): self
    {
        $this->sleep = $milliseconds;

        return $this;
    }

    public function exponential(): self
    {
        $this->exponential = true;

        return $this;
    }

    public function run(callable $callback): mixed
    {
        $tries = 0;
        $sleep = $this->sleep;

        while (true) {

            try {
                return $callback();

            } catch (\Throwable $e) {

                $tries++;

                if ($tries >= $this->attempts) {
                    throw $e;
                }

                if ($sleep > 0) {
                    usleep($sleep * 1000);

                    if ($this->exponential) {
                        $sleep *= 2;
                    }
                }
            }
        }
    }
}