<?php

    class GeneratorMenu
    {
        public static function GeneratorMainMenu()
        {
            if (Yii::app()->user->isGuest) {
                $menus = array(
                    array(
                        'label'       => Yii::t('web/header', 'agencies'),
                        'link'        => array('router' => 'site/agency', array()),
                        'htmlOptions' => array(
                            'title' => Yii::t('web/header', 'agencies'),
                        ),
                    ),
                    array(
                        'label'       => Yii::t('web/header', 'advertisers'),
                        'link'        => array('router' => 'site/advertiser', array()),
                        'htmlOptions' => array(
                            'title' => Yii::t('web/header', 'advertisers'),
                        ),
                    ),
                    array(
                        'label'       => Yii::t('web/header', 'news'),
                        'link'        => array('router' => 'news/index', array()),
                        'htmlOptions' => array(
                            'title' => Yii::t('web/header', 'news'),
                        ),
                    ),
                    array(
                        'label'       => Yii::t('web/header', 'contact'),
                        'link'        => array('router' => 'site/contact'),
                        'htmlOptions' => array(
                            'title' => Yii::t('web/header', 'contact'),
                        ),
                    ),
                );
            } else {
                $menus = array(
                    array(
                        'label'       => Yii::t('web/header', 'news'),
                        'link'        => array('router' => 'news/index', array()),
                        'htmlOptions' => array(
                            'title' => Yii::t('web/header', 'news'),
                        ),
                    ),
                    array(
                        'label'       => Yii::t('web/header', 'contact'),
                        'link'        => array('router' => 'site/contact'),
                        'htmlOptions' => array(
                            'title' => Yii::t('web/header', 'contact'),
                        ),
                    ),
                );
            }
            echo CHtml::openTag('ul',
                array(
                    'id'    => '',
                    'class' => 'nav navbar-nav',
                )
            );
            foreach ($menus as $menu) {
                echo CHtml::tag(
                    'li',
                    $menu['htmlOptions'],
                    CHtml::link($menu['label'], self::getUrl($menu['link']['router'], (isset($menu['link']['params']) && $menu['link']['params'] != '' ? $menu['link']['params'] : '')), $menu['htmlOptions'])
                );
            }
            echo "</ul>\n";
        }

        public static function getUrl($route, $params = array())
        {
            //return Yii::app()->controller->createUrl($route.'/'.$params['id'].'/'.$params['alias']);
            if ($params)
                return Yii::app()->controller->createUrl($route, $params);

            return '';
        }
    }