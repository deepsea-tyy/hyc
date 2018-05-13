<?php
return [
            'class' => 'yii\web\AssetManager',
            'linkAssets' => true,
            'bundles'=>[
                'yii\web\JqueryAsset' => [
                    'sourcePath' => '@m47',
                    'js' => [
                        'global/plugins/jquery.min.js',
                    ],
                ],
                'yii\web\YiiAsset' => [
                    'depends' => [
                        'common\assets\JqueryAsset',
                    ],
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'depends' => [],
                ],
                'yii\widgets\ActiveFormAsset' => [
                    'depends' => [],
                ],
                'kartik\base\WidgetAsset' => [
                    'depends' => [],
                ],
                'kartik\select2\ThemeKrajeeAsset' => [
                    'depends' => [],
                ],
                'kartik\select2\Select2Asset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridViewAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridToggleDataAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridExportAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridResizeStoreAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridResizeColumnsAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridFloatHeadAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridPerfectScrollbarAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\CheckboxColumnAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\EditableColumnAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\ExpandRowColumnAsset' => [
                    'depends' => [],
                ],
                'kartik\grid\GridGroupAsset' => [
                    'depends' => [],
                ],
                'kartik\widgets\AssetBundle' => [
                    'depends' => [],
                ],
                'kartik\widgets\WidgetAsset' => [
                    'depends' => [],
                ],
                'kartik\dialog\DialogBootstrapAsset' => [
                    'depends' => [],
                ],
                'kartik\editable\EditableAsset' => [
                    'depends' => [],
                ],
                'kartik\editable\EditablePjaxAsset' => [
                    'depends' => [],
                ],
                'kartik\popover\PopoverXAsset' => [
                    'depends' => [],
                ],


            ]

        ];