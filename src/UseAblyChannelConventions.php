<?php

namespace SpringboardVR\LaravelAblyBroadcaster;

use Illuminate\Support\Str;

trait UseAblyChannelConventions
{
    /**
     * Return true if channel is protected by authentication..
     *
     * @param  string  $channel
     * @return bool
     */
    public function isGuardedChannel($channel)
    {
        return Str::startsWith($channel, ['private-', 'presence-', 'private-encrypted-']);
    }

    /**
     * Remove prefix from channel name.
     *
     * @param  string  $channel
     * @return string
     */
    public function normalizeChannelName($channel)
    {
        if ($this->isGuardedChannel($channel)) {
            return Str::startsWith($channel, 'private-')
                ? Str::replaceFirst('private-', '', Str::replaceFirst('encrypted-', '', $channel))
                : Str::replaceFirst('presence-', '', $channel);
        }

        return $channel;
    }

    /**
     * Format the channel array into an array of strings.
     *
     * @param  array  $channels
     * @return array
     */
    protected function formatChannels(array $channels)
    {
        return array_map(function ($channel) {
            $channel = (string) $channel;
            if (Str::startsWith($channel, ['private-', 'presence-'])) {
                if (Str::startsWith($channel, 'private-encrypted-')) {
                    return Str::replaceFirst('private-encrypted-', 'private-encrypted:', $channel);
                }

                return Str::startsWith($channel, 'private-')
                    ? Str::replaceFirst('private-', 'private:', $channel)
                    : Str::replaceFirst('presence-', 'presence:', $channel);
            }
            return 'public:' . $channel;
        }, $channels);
    }
}
