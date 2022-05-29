<?php

declare(strict_types=1);

namespace ColinHDev\CPlotRedstoneCircuitIntegration\listener;

use ColinHDev\CPlot\plots\Plot;
use ColinHDev\CPlot\provider\DataProvider;
use ColinHDev\CPlot\worlds\NonWorldSettings;
use ColinHDev\CPlot\worlds\WorldSettings;
use pocketmine\event\Listener;
use tedo0627\redstonecircuit\event\BlockPistonExtendEvent;
use tedo0627\redstonecircuit\event\BlockPistonRetractEvent;

class BlockPistonEventListener implements Listener {

    /**
     * @priority LOWEST
     */
    public function onBlockPistonExtend(BlockPistonExtendEvent $event) : void {
        $this->onBlockPistonEvent($event);
    }

    /**
     * @priority LOWEST
     */
    public function onBlockPistonRetract(BlockPistonRetractEvent $event) : void {
        $this->onBlockPistonEvent($event);
    }

    private function onBlockPistonEvent(BlockPistonExtendEvent|BlockPistonRetractEvent $event) : void {
        $position = $event->getBlock()->getPosition();
        $worldSettings = DataProvider::getInstance()->loadWorldIntoCache($position->getWorld()->getFolderName());
        if (!($worldSettings instanceof WorldSettings)) {
            if (!($worldSettings instanceof NonWorldSettings)) {
                $event->cancel();
            }
            return;
        }
        $plot = Plot::loadFromPositionIntoCache($position);
        if (!($plot instanceof Plot)) {
            $event->cancel();
            return;
        }

        foreach (array_merge($event->getBreakBlocks(), $event->getMoveBlocks()) as $block) {
            $plotOfBlock = Plot::loadFromPositionIntoCache($block->getPosition());
            if (!($plotOfBlock instanceof Plot) || !$plot->isSame($plotOfBlock)) {
                $event->cancel();
                return;
            }
        }
    }
}