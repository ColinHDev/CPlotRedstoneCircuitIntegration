<?php

declare(strict_types=1);

namespace ColinHDev\CPlotRedstoneCircuitIntegration;

use ColinHDev\CPlotRedstoneCircuitIntegration\listener\BlockPistonEventListener;
use pocketmine\plugin\PluginBase;
use tedo0627\redstonecircuit\RedstoneCircuit;

class CPlotRedstoneCircuitIntegration extends PluginBase {

    public function onEnable() : void {
        if (RedstoneCircuit::isCallEvent() === false) {
            throw new \RuntimeException(
                "CPlotRedstoneCircuitIntegration requires RedstoneCircuit's \"event\" config option to be set to \"true\"."
            );
        }

        $this->getServer()->getPluginManager()->registerEvents(new BlockPistonEventListener(), $this);
    }
}