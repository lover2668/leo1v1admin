<?php
namespace Squirrel_tea\Core;
/**
 *
 * 环境判断类
 *
 * Class environment
 */
class Environment{

    /**
     * @descrpition 判断平台是否是SAE
     * @param $appname，运行时所属环境下的应用名称
     * @param $accesskey, 运行时所属环境下的accesskey
     * @return bool
     */
    public static function isSae($appname,$accesskey){
        if(!strcmp(HTTP_APPNAME_SST,$appname)&&!strcmp(HTTP_ACCESSKEY_SST,$accesskey)){
            return true;
        }
        return false;
    }
}
?>
