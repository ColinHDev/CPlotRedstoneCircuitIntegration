<?php

declare(strict_types=1);

namespace ColinHDev\CPlotRedstoneCircuitIntegration;

use ColinHDev\CPlotRedstoneCircuitIntegration\listener\BlockPistonEventListener;
use pocketmine\plugin\PluginBase;

class CPlotRedstoneCircuitIntegration extends PluginBase {

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents(new BlockPistonEventListener(), $this);
    }
}