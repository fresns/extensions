<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Plugins\QiNiu;

use App\Base\Controllers\BaseApiController;
use App\Http\Center\Common\LogService;
use App\Http\FresnsApi\Helpers\ApiConfigHelper;
use App\Http\FresnsDb\FresnsComments\FresnsComments;
use App\Http\FresnsDb\FresnsFileAppends\FresnsFileAppends;
use App\Http\FresnsDb\FresnsFiles\FresnsFiles;
use App\Http\FresnsDb\FresnsPosts\FresnsPosts;
use App\Plugins\QiNiu\Services\QiNiuTransService;
use Illuminate\Http\Request;

class QiNiuControllerTrans extends BaseApiController
{

    public function __construct()
    {

    }

    // 音视频转码回调
    // 1、将转码前的「原文件路径」填入 file_appends > file_original_path
    // 2、将转码后的「新文件路径」填入 files > file_path
    // 3、修改文件转码状态 file_appends > transcoding_state
    public function transNotify(Request $request)
    {
        $callback = $request->input('callback');
        LogService::info("qiniu transNotify callback info", $request);
        if($callback){
            $itemArr = json_decode(base64_decode($callback),true);

            $files = FresnsFiles::where('uuid',$itemArr['fileId'])->first();
            FresnsFiles::where('uuid',$itemArr['fileId'])->update(['file_path' => '/' . $itemArr['saveAsKey']]);
            $input = [
                'transcoding_state' => 3,
                'file_original_path' => $files['file_path']
            ];
            FresnsFileAppends::where('file_id',$files['id'])->update($input);
            //修改帖子或者评论内容
            $videosBucketDomain = ApiConfigHelper::getConfigByItemKey('videos_bucket_domain');
            $audiosBucketDomain = ApiConfigHelper::getConfigByItemKey('audios_bucket_domain');
            if($itemArr['tableName'] == 'posts'){
                $moreJson = FresnsPosts::where('id',$itemArr['tableId'])->value('more_json');
                $moreJsonArr = json_decode($moreJson,true);
                $fileArr = [];
                foreach($moreJsonArr['files'] as $v){
                    if($v['fid'] == $itemArr['fileId']){
                        if($v['type'] == 2){
                            $v['videoUrl'] = $videosBucketDomain . '/' . $itemArr['saveAsKey'];
                        }
                        if($v['type'] == 3){
                            $v['audioUrl'] = $audiosBucketDomain . '/' . $itemArr['saveAsKey'];
                        }
                    }
                    $fileArr[] = $v;
                }
                $data['files'] = $fileArr;
                if(!empty($moreJsonArr['icons'])){
                    $data['icons'] = $moreJsonArr['icons'];
                }
                $json = json_encode($data);
                FresnsPosts::where('id',$itemArr['tableId'])->update(['more_json' => $json]);
            }
            if($itemArr['tableName'] == 'comments'){
                $moreJson = FresnsComments::where('id',$itemArr['tableId'])->value('more_json');
                $moreJsonArr = json_decode($moreJson,true);
                $fileArr = [];
                foreach($moreJsonArr['files'] as $v){
                    if($v['fid'] == $itemArr['fileId']){
                        if($v['type'] == 2){
                            $v['videoUrl'] = $videosBucketDomain . '/' . $itemArr['saveAsKey'];
                        }
                        if($v['type'] == 3){
                            $v['audioUrl'] = $audiosBucketDomain . '/' . $itemArr['saveAsKey'];
                        }
                    }
                    $fileArr[] = $v;
                }
                $data['files'] = $fileArr;
                if(!empty($moreJsonArr['icons'])){
                    $data['icons'] = $moreJsonArr['icons'];
                }
                $json = json_encode($data);
                FresnsComments::where('id',$itemArr['tableId'])->update(['more_json' => $json]);
            }
        }
        $this->success();
    }

}