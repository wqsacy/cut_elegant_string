<?php
	
	namespace Wangqs\ElegantCut;
	
	use Wangqs\ElegantCut\Lib\Factory;
	use Wangqs\ElegantCut\Lib\Utils;
	
	/**
	 *  优雅的切分字符串
	 * Created by wangqs.
	 * Date: 2022/5/20  9:04 上午
	 */
	class StringCut
	{
		private $paragraph , $endMark , $length , $offset , $result;
		
		public function __construct ( $config = [
			'paragraph' => false ,
			'endMark'   => [ '。' , '？' , '！' ] ,
			'length'    => 120 ,
			'offset'    => 0.2 ,
		] ) {
			$this->paragraph = $config['paragraph'];
			$this->endMark   = $config['endMark'];
			$this->length    = $config['length'];
			$this->offset    = $config['offset'];
			$this->result    = [];
		}
		
		public static function init () {
			return new self();
		}
		
		public function get ( $str ) {
			$factory = new Factory( $this->paragraph , $this->endMark , $this->length , $this->offset );
			
			$this->result = $factory->get( $str );
			
			return $this;
		}
		
		public function toArray () {
			return $this->result;
		}
		
		public function toJson () {
			return Utils::toJson( $this->result );
		}
		
		//段落处理 true则直接无视段落，处理为长字符串
		public function paragraph ( $bool ) {
			$this->paragraph = $bool;
			
			return $this;
		}
		
		//配置结束句标识
		public function endMark ( $mark ) {
			if ( !is_array( $mark ) ) {
				$this->endMark = [ $mark ];
			} else {
				$this->endMark = $mark;
			}
			
			return $this;
		}
		
		//截取长度
		public function length ( $num ) {
			$this->length = $num;
			
			return $this;
		}
		
		//截取偏移量
		public function offset ( $num ) {
			$this->offset = $num;
			
			return $this;
		}
		
		//多余字符串处理方式  丢弃：del  独立保留：normal  拼接到上一句：spl
		public function remain ( $type ) {
			$norm = [
				'del' ,
				'normal' ,
				'spl' ,
			];
			
			if ( !in_array( $type , $norm ) ) {
				$this->remain = 'normal';
			} else {
				$this->remain = $type;
			}
			
			return $this;
		}
		
		
	}