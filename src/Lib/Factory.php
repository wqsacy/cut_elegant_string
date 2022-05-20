<?php
	
	namespace Wangqs\ElegantCut\Lib;
	
	
	class Factory
	{
		
		private $paragraph , $endMark , $length , $offset;
		
		public function __construct ( $paragraph , $endMark , $length , $offset ) {
			$this->paragraph = $paragraph;
			$this->endMark   = $endMark;
			$this->length    = $length;
			$this->offset    = $offset;
		}
		
		public function get ( $str ) {
			
			//是否分段
			switch ( $this->paragraph ) {
				case true:
					$str    = Utils::cutStrByParagraph( $str );
					$strArr = [];
					if ( is_array( $str ) ) {
						foreach ( $str as $val ) {
							$strArr[] = $this->strCut( $val );
						}
					}
					break;
				
				default:
					$str    = Utils::formatString( $str );
					$strArr = $this->strCut( $str );
			}
			
			//计算偏移量
			$num = intval( $this->length * $this->offset );
			
			$offset = $this->length - $num;
			
			return $this->splice( $strArr , $offset );
		}
		
		
		//按偏移量拼接字符串，返回符合条件的字符串和剩余字符串
		private function splice ( $strArr , $offset ) {
			$result = [];
			
			switch ( $this->paragraph ) {
				case true:      //如果保留段落,双层循环
					foreach ( $strArr as $val ) {
						$result[] = $this->dealWith( $val , $offset );
					}
					break;
				
				default:
					$result = $this->dealWith( $strArr , $offset );
			}
			
			return $result;
		}
		
		
		private function dealWith ( $strArr , $offset ) {
			$result = [];
			
			$splStr = '';
			
			if ( is_array( $strArr ) ) {
				foreach ( $strArr as $key => $val ) {
					$tmpStr = $val;
					
					//如果满足了条件
					if ( Utils::length( $splStr ) >= $offset && $this->endMark( $tmpStr ) ) {
						$result[] = $splStr . $tmpStr;
						
						$splStr = '';
					} else {
						$splStr .= $tmpStr;
					}
					
					//清理已经处理的字符串
					unset( $strArr[$key] );
				}
			}
			
			//如果都循环完成了，但是并未有结果,则说明文本较少，不满足拆分条件
			if ( !count( $result ) ) {
				$result[] = $splStr;
			}
			
			return $result;
		}
		
		//判断结束标识
		private function endMark ( $tmpStr ) {
			
			foreach ( (array) $this->endMark as $needle ) {
				if ( $needle !== '' && mb_strpos( $tmpStr , $needle ) !== false ) {
					return true;
				}
			}
			
			return false;
		}
		
		//中英文混合字符串按最小单位拆分
		private function strCut ( $str ) {
			return preg_split( '~(?<!\p{Latin})(?=\p{Latin})|(?<!\p{Han})(?=\p{Han})|(?<!\p{P})(?=\p{P})|(?<![0-9])(?=[0-9])~u' , $str , -1 , PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );;
		}
		
	}