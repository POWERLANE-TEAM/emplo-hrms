<div>
    <form wire:submit="updateDeviceIp">
        <label for="ip">Set Ip Address</label>
        <input type="text" id="ip" wire:model="ipAddress" />

        <button type="submit">Set</button>        
    </form>

    <button wire:click="shutdownDevice" type="button" class="btn btn-primary">Shut down</button>
    <button wire:click="restartDevice" type="button" class="btn btn-primary">Restart</button>
    <button wire:click="testDeviceVoice" type="button" class="btn btn-primary">Test Voice</button>

    <div class="fs-4 my-3">Device Information</div>
    <div>
        <div>{{ $device->name }}</div>
        <div>{{ $device->version }}</div>
        <div>{{ $device->osVersion }}</div>
        <div>{{ $device->serialNumber }}</div>
        <div>{{ $device->platform }}</div>
        <div>{{ $device->ssr }}</div>
        <div>{{ $device->fmVersion }}</div>
        <div>{{ $device->pinWidth }}</div>
    </div>
</div>
