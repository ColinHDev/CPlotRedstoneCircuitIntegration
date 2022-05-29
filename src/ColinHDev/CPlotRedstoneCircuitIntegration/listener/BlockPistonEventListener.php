<?php

declare(strict_types=1);

namespace ColinHDev\CPlotRedstoneCircuitIntegration\listener;

use ColinHDev\CPlot\plots\Plot;
use ColinHDev\CPlot\provider\DataProvider;
use ColinHDev\CPlot\worlds\NonWorldSettings;
use ColinHDev\CPlot\worlds\WorldSettings;
use pocketmine\event\Listener;
use pocketmine\math\Facing;
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
        $block = $event->getPiston();
        $position = $block->getPosition();
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

        foreach ($event->getBreakBlocks() as $breakBlock) {
            if (!$plot->isOnPlot($breakBlock->getPosition())) {
                $event->cancel();
                return;
            }
        }

        $face = $event instanceof BlockPistonExtendEvent ? $block->getPistonArmFace() : Facing::opposite($block->getPistonArmFace());
        foreach ($event->getMoveBlocks() as $moveBlock) {
            $moveBlockPosition = $moveBlock->getPosition();
            if (!$plot->isOnPlot($moveBlockPosition)) {
                $event->cancel();
                return;
            }
            if (!$plot->isOnPlot($moveBlockPosition->getSide($face))) {
                $event->cancel();
                return;
            }
        }
    }
}