<?php

declare(strict_types=1);

namespace ColinHDev\CPlotRedstoneCircuitIntegration\listener;

use ColinHDev\CPlot\plots\Plot;
use ColinHDev\CPlot\utils\APIHolder;
use pocketmine\event\Listener;
use pocketmine\math\Facing;
use tedo0627\redstonecircuit\event\BlockPistonExtendEvent;
use tedo0627\redstonecircuit\event\BlockPistonRetractEvent;

class BlockPistonEventListener implements Listener {
    use APIHolder;

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
        /** @phpstan-var true|false|null $isPlotWorld */
        $isPlotWorld = $this->getAPI("1.0.0")->isPlotWorld($position->getWorld())->getResult();
        if ($isPlotWorld !== true) {
            if ($isPlotWorld !== false) {
                $event->cancel();
            }
            return;
        }
        /** @phpstan-var Plot|false|null $plot */
        $plot = $this->getAPI("1.0.0")->getOrLoadPlotAtPosition($position)->getResult();
        if (!($plot instanceof Plot) || !$plot->isOnPlot($position->getSide($block->getPistonArmFace()))) {
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
            if (!$plot->isOnPlot($moveBlockPosition) || !$plot->isOnPlot($moveBlockPosition->getSide($face))) {
                $event->cancel();
                return;
            }
        }
    }
}