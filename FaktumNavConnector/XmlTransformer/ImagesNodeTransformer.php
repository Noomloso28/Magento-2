<?php
/**
 * XML images node transformer.
 *
 * @package Immerce\FaktumNavConnector\XmlTransformer
 * @version 1.0.0
 * @license GPL 2.0+
 */

declare(strict_types=1);

namespace Immerce\FaktumNavConnector\XmlTransformer;

/**
 * XML images node transformer class.
 *
 * @package Immerce\FaktumNavConnector\XmlTransformer
 * @since   1.0.0
 */
class ImagesNodeTransformer implements XmlNodeTransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform(array $nodeData): array
    {
        $images = $nodeData['image'] ?? [];
        $result = array();
        $name = '';
        $attribute = [];
        $productAttributes = [];

        foreach ($images as $key => $image){

            if(is_numeric($key)){

                /** check in case image key is 0, 1, 2 .... */
                //generate image
                $name = $image['_value'] ?? $image;
                $attribute = $image['_attribute'] ?? null;

                $productAttributes[] = [
                    'url' => $name,
                    'attribute' => $attribute
                ];

            }else{
                //generate image
                if($key == '_value'){
                    $name = $image;
                }
                if($key == '_attribute'){
                    $attribute = $image;
                }

                if (is_array($image)){
                    //check name for the _value varible.
                    if(array_key_exists('_value', $image)){
                        $name = $image['_value'];
                    }
                }
                $productAttributes = [
                    'url' => $name,
                    'attribute' => $attribute
                ];
            }

        }
        /** summary result */
        $result[] = $productAttributes;

        return $result;

//         $image_data = array_map(function ($image) {
//            if (is_array($image)) {
//                return array_merge(
//                    [
//                        'url' => $image['_value'] ?? '',
//                    ],
//                    $image['_attribute'] ?? []
//                );
//            }
//
//            return [
//
//                'url' => $image,
//            ];
//        }, $images);
//
//        echo '<pre>';
//        print_r($image_data);
//        echo '</pre>';
//        echo 'end <hr>';
//
//        return $image_data;
        /**
         *
        [images] => Array(
            [image] => Array(
                [0] => Array(
                    [_value] => 16226.png
                    [_attribute] => Array(
                        [role] => base,small,thumbnail
                    )
                )
                [1] => 16226_1.jpg
                [2] => 16226_2.png
                [3] => Array(
                    [_value] => 16226_3.jpg
                    [_attribute] => Array(
                        [hidden] => true
                    )
                )
            )
        )

        'images' => [
            [
               'url' => '16226.png',
               'role' => 'base,small,thumbnail'
            ],
         ]
         */
        // TODO: Implement transform() method.
    }
}
