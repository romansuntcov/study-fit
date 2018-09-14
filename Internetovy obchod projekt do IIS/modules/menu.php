<?php

class MenuModule extends AbstractModule {
	public function getContent() {
		return $this->parseView('vmenu');
	}
}