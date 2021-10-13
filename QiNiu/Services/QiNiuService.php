<?php

/*
 * Fresns (https://fresns.org)
 * Copyright (C) 2021-Present Jarvis Tang
 * Released under the Apache-2.0 License.
 */

namespace App\Plugins\QiNiu\Services;

use App\Helpers\StrHelper;
use App\Http\Center\Common\LogService;
use App\Http\FresnsApi\Helpers\ApiConfigHelper;
use App\Plugins\QiNiu\QiNiuConfig;
use Qiniu\Auth;
use Qiniu\Config;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

// 加载七牛云 SDK
require_once ( dirname(dirname(__FILE__)) . "/QiNiuSdk/autoload.php");

class QiNiuService
{

    public $qiNiuAppId;
    public $qiNiuAppKey;
    public $qiNiuBucketDomain;
    public $qiNiuBucketName ;
    public $qiNiuArea;
    public $qiNiuAuth;
    public $type;

    public function __construct($type){
        $this->type = $type;
        $this->init();
        $this->qiNiuAuth = new Auth($this->qiNiuAppId, $this->qiNiuAppKey);
    }

    // 初始化（获取存储配置）
    public function init(){
        if($this->type == 1){
            $this->qiNiuAppId = ApiConfigHelper::getConfigByItemKey('images_secret_id');
            $this->qiNiuAppKey = ApiConfigHelper::getConfigByItemKey('images_secret_key');
            $this->qiNiuBucketDomain = ApiConfigHelper::getConfigByItemKey('images_bucket_domain');
            $this->qiNiuBucketName = ApiConfigHelper::getConfigByItemKey('images_bucket_name');
            $this->qiNiuArea =  ApiConfigHelper::getConfigByItemKey('images_bucket_area');
        }
        if($this->type == 2){
            $this->qiNiuAppId = ApiConfigHelper::getConfigByItemKey('videos_secret_id');
            $this->qiNiuAppKey = ApiConfigHelper::getConfigByItemKey('videos_secret_key');
            $this->qiNiuBucketDomain = ApiConfigHelper::getConfigByItemKey('videos_bucket_domain');
            $this->qiNiuBucketName = ApiConfigHelper::getConfigByItemKey('videos_bucket_name');
            $this->qiNiuArea =  ApiConfigHelper::getConfigByItemKey('videos_bucket_area');
        }
        if($this->type == 3){
            $this->qiNiuAppId = ApiConfigHelper::getConfigByItemKey('audios_secret_id');
            $this->qiNiuAppKey = ApiConfigHelper::getConfigByItemKey('audios_secret_key');
            $this->qiNiuBucketDomain = ApiConfigHelper::getConfigByItemKey('audios_bucket_domain');
            $this->qiNiuBucketName = ApiConfigHelper::getConfigByItemKey('audios_bucket_name');
            $this->qiNiuArea =  ApiConfigHelper::getConfigByItemKey('audios_bucket_area');
        }
        if($this->type == 4){
            $this->qiNiuAppId = ApiConfigHelper::getConfigByItemKey('docs_secret_id');
            $this->qiNiuAppKey = ApiConfigHelper::getConfigByItemKey('docs_secret_key');
            $this->qiNiuBucketDomain = ApiConfigHelper::getConfigByItemKey('docs_bucket_domain');
            $this->qiNiuBucketName = ApiConfigHelper::getConfigByItemKey('docs_bucket_name');
            $this->qiNiuArea =  ApiConfigHelper::getConfigByItemKey('docs_bucket_area');
        }
    }

    // 获取上传 token
    public function getUploadToken($type = QiNiuConfig::TYPE_IMAGE, $key , $expires = 3600 ){

        $policy = $this->getPolicy($type);

        $token = $this->qiNiuAuth->uploadToken($this->qiNiuBucketName, $key, 86400, $policy);

        return $token;
    }

    // 上传本地文件
    public function uploadLocalFile($filePath, $key){

        // 生成上传 Token
        $token = $this->qiNiuAuth->uploadToken($this->qiNiuBucketName);
        // 要上传文件的本地路径
        // $filePath = './php-logo.png';

        // 上传到七牛存储后保存的文件名
        //  $key = 'my-php-logo.png';

        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        // 调用 UploadManager 的 putFile 方法进行文件的上传，该方法会判断文件大小，进而决定使用表单上传还是分片上传，无需手动配置。
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        LogService::info('QiNIU-putFile-ret',$ret);
        LogService::info('QiNIU-putFile-err',$err);
    }

    // 获取防盗链链接
    public function getPrivateDownloadUrl($baseUrl, $expires = 3600 ){
        $privateUrl = $this->qiNiuAuth->privateDownloadUrl($baseUrl, $expires);
        return $privateUrl;
    }

    // 删除文件
    public function deleteResource($bucket, $key){
        $config = new Config();
        $bucketManager = new BucketManager($this->qiNiuAuth, $config);

        $res = $bucketManager->delete($bucket, $key);

        return $res;
    }


    /**
     * 列举资源文件
     * https://developer.qiniu.com/kodo/api/1284/list
     */
    public function listFiles(){

        $prefix = '';   // 要列取文件的公共前缀
        $marker = '';   // 上次列举返回的位置标记，作为本次列举的起点信息。
        $limit = 500;   // 本次列举的条目数，，范围为 1-1000
        $delimiter = '/';

        $bucketManager = new BucketManager($this->qiNiuAuth);

        // 列举文件
        list($ret, $err) = $bucketManager->listFiles($this->qiNiuBucketName, $prefix, $marker, $limit, $delimiter);

        $res = $ret['items'] ?? [];

        return $res;
    }

    // 上传策略
    // https://developer.qiniu.com/kodo/1235/vars#xvar
    public function getPolicy($type){

        if($type == QiNiuConfig::TYPE_IMAGE){
            $service = new QiNiuImageService($type);
            return $service->getPolicy();
        }

        $defaultReturnBody = '{
            "name": $(fname),
            "size": $(fsize),
            "hash": $(etag)
        }';

        $policy = [
            'returnBody'    => $defaultReturnBody,
        ];
        return $policy;
    }

    // 获取资源 URI
    public function getEncodedEntryURI($bucket, $key){
        $entry = "$bucket:$key";
        $encodedEntryURI = urlsafe_base64_encode($entry);
        return $encodedEntryURI;
    }

    // 获取上传限制
    public function getUploadLimit(){
        $infoArr = [];

        $limitFieldArr = [
            'images_ext',
            'images_max_size',
            'videos_ext',
            'videos_max_size',
            'videos_max_time',
            'audios_ext',
            'audios_max_time',
            'audios_max_size',
            'docs_ext',
            'docs_max_size'
        ];
        foreach ($limitFieldArr as $limitField){
            $infoArr[$limitField]= ApiConfigHelper::getConfigByItemKey($limitField);
        }

        return $infoArr;
    }

    // todo 此处 key 的规则同文件目录存储规则, 需要提供规则
    public function generatQiNiuKey($type){
        $randString = StrHelper::randString(10);
        $key = "test/" . $randString;
        $key = $randString;
        return $key;
    }
}