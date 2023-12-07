<?php

namespace DesignPattern\ShapeFactory\Processor\Creating;

interface DrawableShapeInterface extends ShapeInterface
{
	/**
	 * @return string
	 */
	public function draw() :string;

}
