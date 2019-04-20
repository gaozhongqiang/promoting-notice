<?php
/**
 * Created by PhpStorm.
 * User: zhw
 * Date: 2019/4/20
 * Time: 11:48
 */
namespace Promoting\Notice;

class Style{
    /**
     *组装,推广中心的 推广二维码，我的推广链接，IOS推广包，安装推广包，获取推广素材等。。。
     * @param $promuser_id 推广用户ID
     * @param $image_code_path 推广二维码链接
     * @param $short_url 推广短链接
     * @param $promuser_source_url 推广原链接
     * @param $res 推广素材
     * @param $is_mobile_request 判断是手机端还是pc端
     * @return string 组装后的Html
     */
    public static function ShowQcodeHtml($promuser_id,$image_code_path,$short_url,$promuser_source_url,$res,$is_mobile_request){
        $qcodeHtml="<div class=\"yxzx01_left\"><span>我的推广二维码</span><img src=\"".$image_code_path."\" /><a href=\"".UrlRecombination("tgmarket/down_qrcode",array("qrcodeimg"=>ReturnPromuserQrcode($promuser_id)))."\" target=\"_blank\">下载二维码</a></div>";
        $qcodeHtml.="
		<div class=\"yxzx01_right\"><span style=\"padding-top:9px;\">我的推广链接</span>
		<p style=\"padding: 15px 0 10px;\" id=\"short_url_id\" need_short_url=\"-1\" \">".$short_url."</p>";
        //判断是否手机端访问，因为手机端和pc端使用的短链复制功能是不同的，由李鹏飞于2017-03-01添加
        if($is_mobile_request){
            //手机端
            $qcodeHtml.="<a style=\"display:inline-block;\" href=\"javascript:;\" onclick=\"CopyContentTxt('short_url_id')\">复制短链</a>";
            $qcodeHtml.="<a style=\"display:none;\" tgurl=\"".$short_url."\"  href=\"javascript:;\" id=\"copy_btn_short_url\">复制短链</a>";
        }else{
            //pc端
            $qcodeHtml.="<a style=\"display:none;\" href=\"javascript:;\" onclick=\"CopyContentTxt('short_url_id')\">复制短链</a>";
            $qcodeHtml.="<a style=\"display:inline-block;\" tgurl=\"".$short_url."\"  href=\"javascript:;\" id=\"copy_btn_short_url\">复制短链</a>";
        }
        $qcodeHtml.="<p id=\"source_url_id\" style=\"padding: 15px 0 10px;\">".$promuser_source_url."</p>";
        //判断是否手机端访问，因为手机端和pc端使用的原链复制功能是不同的，由李鹏飞于2017-03-01添加
        if($is_mobile_request){
            //手机端
            $qcodeHtml.="<a style=\"display:inline-block;\" href=\"javascript:;\" onclick=\"CopyContentTxt('source_url_id')\">复制原链</a>";
            $qcodeHtml.="<a style=\"display:none;\" tgurl=\"".$promuser_source_url."\"  href=\"javascript:;\" id=\"copy_btn\">复制原链</a>";
        }else{
            //pc端
            $qcodeHtml.="<a style=\"display:none;\" href=\"javascript:;\" onclick=\"CopyContentTxt('source_url_id')\">复制原链</a>";
            $qcodeHtml.="<a style=\"display:inline-block;\" tgurl=\"".$promuser_source_url."\"  href=\"javascript:;\" id=\"copy_btn\">复制原链</a>";
        }
        $qcodeHtml.="&#12288;&#12288;&#12288;&#12288;<a style=\"display:inline-block;\" href=\"https://market.x7sy.com/help/xr\" target=\"_blank\">如何信任开发者</a>";
        $qcodeHtml.="&#12288;&#12288;&#12288;&#12288;<a style=\"display:inline-block;\" href=".ReturnAndroidIOSMarketUrl($promuser_id,1)." target=\"_blank\">获取IOS推广包</a>";
        $qcodeHtml.="&#12288;&#12288;&#12288;&#12288;<a style=\"display:inline-block;\" href=".ReturnAndroidIOSMarketUrl($promuser_id,2)." target=\"_blank\">获取安卓推广包</a>";
        if(!$res){
            $qcodeHtml.="</div>";
        }else {
            $qcodeHtml .= "&#12288;&#12288;&#12288;&#12288;<a style=\"display:inline-block;\" href=\"" . ReturnImageResourceCdn($res['resource_url']) . "\" target=\"_blank\">获取推广素材</a></div>";
        }
        return $qcodeHtml;
    }
}
