<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace Plugins\QiNiu\ServicesOld;

use App\Helpers\ConfigHelper;
use Plugins\QiNiu\QiNiuConfig;

class QiNiuVideoService extends QiNiuService
{
    // 获取视频防盗链地址
    public function getVideoDownloadUrl($url, $options = [])
    {

        // 获取防盗链配置
        $videoUrlStatus = ConfigHelper::fresnsConfigByItemKey(QiNiuConfig::VIDEO_URL_STATUS);
        $videoUrlExpire = ConfigHelper::fresnsConfigByItemKey(QiNiuConfig::VIDEO_URL_EXPIRE);

        // 判断防盗链状态
        if ($videoUrlStatus === false) {
            return $url;
        }

        // 将地址时效由分钟转换成秒
        $videoUrlExpire = intval($videoUrlExpire * 60);

        // 获取地址
        $downloadUrl = $this->getPrivateDownloadUrl($url, $videoUrlExpire);

        // 输出地址
        return $downloadUrl;
    }
}
