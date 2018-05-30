<?php

namespace Dcux\Core;

interface Epibolic {
	/**
	 * 接收原料
	 * @return boolean
	 */
	public function receive(Customizale $customizale = null);
	/**
	 * 生产
	 */
	public function produce();
	/**
	 * 交付
	 */
	public function delivery();
}