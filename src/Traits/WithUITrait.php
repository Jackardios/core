<?php

namespace Laraneat\Core\Traits;

trait WithUITrait
{
    protected string $ui;

    /**
     * @return string
     */
    public function getUI(): string
    {
        return $this->ui;
    }

    /**
     * @param string $ui
     * @return $this
     */
    public function setUI(string $ui): self
    {
        $this->ui = $ui;

        return $this;
    }
}
