<?php
/**
 * Created by PhpStorm.
 * User: gzq
 * Date: 2019/4/13
 * Time: 11:52
 * 话题内容显示
 */
namespace Promoting\Notice;
class Notice{
    //1：存文本、2：富文本、4、自定义格式
    public static function showGameNotice($show_type,$data){
        $out_html = $data;
        switch ($show_type){
            case 1:
                $out_html = "<pre style='white-space:pre-wrap; word-wrap:break-word;line-height: 1.4em;font-family: Microsoft Yahei'>{$data}</pre>";
                break;
            case 2:
                $out_html = '<p>'. self::textCrack(str_replace("http://image.x7sy.com","//image.x7sy.com",htmlspecialchars_decode($data))) .'</p>';
                break;
            case 3://批量导入生成的价目表
                $out_html = <<<EOF
		<style>
			table thead th{color:#999;}
			table th,table td{width:200px; text-align:center;}
			table{margin: 20px auto 5px;}
		</style>
		<table>
		    <thead>
			  <tr>
				<th>VIP等级</th>
				<th>原价</th>
				<th>小7折扣价</th>
			  </tr>
		    </thead>
			
		    <tbody>
EOF;
			  foreach($data as $v) {
                  $out_html .= <<<EOF
			  <tr>
				<td><strong>{$v['vip_no']}</strong></td>
				<td><strong>{$v['source_price']}</strong></td>
				<td><strong>{$v['discount_price']}</strong></td>
			  </tr>
EOF;
              }
              $out_html .= <<<EOF
			  <tr>
				<td colspan="3" style="padding: 10px;">vip价目表仅供参考，以游戏内为准</td>
			  </tr>
		    </tbody>			
		</table>
EOF;
                break;
            case 4:
                //对新版内容做处理
                $is_compile_content = json_decode($data, true);
                if (json_last_error() == JSON_ERROR_NONE && is_array($is_compile_content)) {
                    //完善图片、视频返回信息
                    foreach ($is_compile_content as $k => $v) {
                        unset($v['jump_data']);
                        foreach ($v['plate_content'] as $k1 => $v1) {
                            if($v1['type'] == 'video'){
                                continue;
                            }
                            switch ($v1['type']) {
                                case "image":
                                    $is_compile_content[$k]['plate_content'][$k1]['value'] = FormatAtlasMarketSizeOne($v1['value']['source_image']);
                                    //为图片补充缩放信息
                                    break;
                                default:
                                    //为使得value字段返回客户端都为 数组格式，特意将黑色文本、红色文本转为以v1为键名的数组
                                    $is_compile_content[$k]['plate_content'][$k1]['value'] = $v1["value"];
                                    break;
                            }
                        }
                    }
                    $data = "";
                    foreach ($is_compile_content as $key => $value){
                        $data .= "<p><strong>{$value['title']}</strong></p>";
                        foreach ($value['plate_content'] as $key1 => $value1){
                            switch ($value1['type']){
                                case 'black_txt':
                                    $data .= self::textCrack($value1['value']);
                                    break;
                                case 'red_txt':
                                    $data .= self::textCrack($value1['value'],1);
                                    break;
                                case 'image':
                                    $data .= "<img src='{$value1['value']['source_image']}' class='ht_img' width='300'>";
                            }
                        }
                    }
                    $out_html = $data;
                }
                break;
            default:
                $out_html = '';
                break;
        }
        return $out_html;
    }

    /**
     * 文档信息转换
     * @param string $value
     * @param int $is_color
     * @return string
     */
    public static function textCrack($value,$is_color = 0){
        $style = empty($is_color) ? "" : "style='color: red'";
        $valueText = str_replace("\n\n","",$value);
        return "<p {$style}>".str_replace("\n","</p><p>",$valueText)."</p>";
    }
}
