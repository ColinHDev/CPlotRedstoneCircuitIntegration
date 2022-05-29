# CPlotRedstoneCircuitIntegration

CPlotRedstoneCircuitIntegration is an addon for the plugin [CPlot](https://github.com/ColinHDev/CPlot) for the Minecraft: Bedrock Edition server software [PocketMine-MP](https://github.com/pmmp/PocketMine-MP). <br>
It integrates the plugin [RedstoneCircuit](https://github.com/tedo0627/RedstoneCircuit) into the CPlot plugin and enables and restricts the usage of RedstoneCircuit in plot worlds.

**ATTENTION**: For this plugin to work, the RedstoneCircuit's `event` config option needs to be set to `true`! Otherwise, no events will be called by RedstoneCircuit, so this addon is not able to work.

## Features
This plugin disables that piston blocks of RedstoneCircuit can be used to push or pull blocks outside of plots.