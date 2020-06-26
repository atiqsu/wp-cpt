<?php

namespace XS\CPT;


interface Cpt_Contract {

	public function post_type();

	public function register(array $conf = []);

	public function uninstall();
}