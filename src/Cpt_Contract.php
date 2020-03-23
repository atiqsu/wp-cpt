<?php
/**
 * Created by  Md. Atiqur Rahman <atiqur.su@gmail.com>
 *
 * Date: 3/19/2020 - 6:44 PM
 */

namespace XS\CPT;


interface Cpt_Contract {

	public function post_type();

	public function register(array $conf);
}