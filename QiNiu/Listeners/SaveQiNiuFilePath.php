<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\QiNiu\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Plugins\QiNiu\Events\FileUpdateToQiNiuSuccessfual;

class SaveQiNiuFilePath
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(FileUpdateToQiNiuSuccessfual $event)
    {
        $event->fileModel->update([
            'file_path' => $event->qiniuFilePath,
        ]);
    }
}
