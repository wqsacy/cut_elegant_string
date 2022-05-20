<?php
	
	namespace Wangqs\ElegantCut\Lib;
	
	
	class Utils
	{
		
		//去除 html 标签
		public static function formatString ( $str , $br = false ) {
			$str = htmlspecialchars_decode( $str ); //把一些预定义的 HTML 实体转换为字符
			
			$str = str_replace( "&nbsp;" , "" , $str ); //将空格替换成空
			$str = strip_tags( $str ); //函数剥去字符串中的 HTML、XML 以及 PHP 的标签,获取纯文本内容
			
			if ( $br !== ' ' ) {
				$str = str_replace( [ "\n" , "\r\n" , "\r" , '<br>' ] , $br , $str );
			} else {
				$str = str_replace( [ "\n" , "\r\n" , "\r" , '<br>' ] , $br , ' ' );
			}
			
			$preg = "/<script[\s\S]*?<\/script>/i";
			//剥离JS代码
			
			return preg_replace( $preg , "" , $str , -1 );
		}
		
		//按段落拆分字符串
		public static function cutStrByParagraph ( $str ) {
			$str = self::formatString( $str , "\n" );
			
			return explode( "\n" , $str );
		}
		
		//字符数统计
		public static function length ( $value , $encoding = null ) {
			if ( $encoding ) {
				return mb_strlen( $value , $encoding );
			}
			
			return mb_strlen( $value );
		}
		
		//格式化为 json
		public static function toJson ( $data ) {
			if ( !is_array( $data ) ) {
				$data = [ $data ];
			}
			
			return json_encode( $data , JSON_UNESCAPED_UNICODE );
		}
		
	}