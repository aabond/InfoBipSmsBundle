<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return [
    'services' => [
        'events' => [
            'mautic.sms.campaignbundle.subscriber' => [
                'class'     => 'MauticPlugin\InfoBipSmsBundle\EventListener\CampaignSubscriber',
                'arguments' => [
                    'mautic.helper.core_parameters',
                    'mautic.sms.model.sms',
                ]
            ],
        	'mautic.sms.formbundle.subscriber' => [
        		'class' => 'MauticPlugin\InfoBipSmsBundle\EventListener\FormSubscriber',
        		'arguments' => [
        			'mautic.helper.core_parameters',
        			'mautic.lead.model.lead',
        			'mautic.sms.model.sms',
        			'mautic.sms.api',
        			'mautic.helper.sms',
        		],
        	],
            'mautic.sms.configbundle.subscriber' => [
                'class' => 'Mautic\SmsBundle\EventListener\ConfigSubscriber',
            ],
            'mautic.sms.smsbundle.subscriber' => [
                'class'     => 'Mautic\SmsBundle\EventListener\SmsSubscriber',
                'arguments' => [
                    'mautic.core.model.auditlog',
                    'mautic.page.model.trackable',
                    'mautic.page.helper.token',
                    'mautic.asset.helper.token',
                ],
            ],
            'mautic.sms.channel.subscriber' => [
                'class' => \Mautic\SmsBundle\EventListener\ChannelSubscriber::class,
            ],
            'mautic.sms.stats.subscriber' => [
                'class'     => \Mautic\SmsBundle\EventListener\StatsSubscriber::class,
                'arguments' => [
                    'doctrine.orm.entity_manager',
                ],
            ],
        ],
        'forms' => [
            'mautic.form.type.sms' => [
                'class'     => 'Mautic\SmsBundle\Form\Type\SmsType',
                'arguments' => 'mautic.factory',
                'alias'     => 'sms',
            ],
            'mautic.form.type.smsconfig' => [
                'class' => 'MauticPlugin\InfoBipSmsBundle\Form\Type\ConfigType',
                'alias' => 'smsconfig',
            ],
            'mautic.form.type.smssend_list' => [
                'class'     => 'Mautic\SmsBundle\Form\Type\SmsSendType',
                'arguments' => 'router',
                'alias'     => 'smssend_list',
            ],
            'mautic.form.type.sms_list' => [
                'class'     => 'Mautic\SmsBundle\Form\Type\SmsListType',
                'arguments' => 'mautic.factory',
                'alias'     => 'sms_list',
            ],
        ],
        'helpers' => [
            'mautic.helper.sms' => [
                'class'     => 'MauticPlugin\InfoBipSmsBundle\Helper\SmsHelper',
                'arguments' => [
                    'doctrine.orm.entity_manager',
                    'mautic.lead.model.lead',
                    'mautic.helper.phone_number',
                    'mautic.sms.model.sms',
                    '%mautic.sms_frequency_number%',
                ],
                'alias' => 'sms_helper',
            ],
        ],
    		
    	'other' => [ 
    		'mautic.sms.api' => [ 
    				'class' => 'MauticPlugin\InfoBipSmsBundle\Api\SmsInfoBipApi', 
    				'arguments' => [ 
    						'mautic.page.model.trackable',
    						'mautic.factory', 
    						'mautic.helper.phone_number', 
    						'%mautic.sms_sending_phone_number%',
    						'%mautic.sms_username%',
    						'%mautic.sms_password%'
    				], 
    				'alias' => 'sms_api'
    		],
        ],
        'models' => [
            'mautic.sms.model.sms' => [
                'class'     => 'MauticPlugin\InfoBipSmsBundle\Model\SmsModel',
                'arguments' => [
                    'mautic.page.model.trackable',
                	'mautic.lead.model.lead',
                	'mautic.channel.model.queue',
                	'mautic.sms.api'
                ],
            ],
        ],
    ],
    'routes' => [
        'main' => [
            'mautic_sms_index' => [
                'path'       => '/sms/{page}',
                'controller' => 'MauticSmsBundle:Sms:index',
            ],
            'mautic_sms_action' => [
                'path'       => '/sms/{objectAction}/{objectId}',
                'controller' => 'MauticSmsBundle:Sms:execute',
            ],
            'mautic_sms_contacts' => [
                'path'       => '/sms/view/{objectId}/contact/{page}',
                'controller' => 'MauticSmsBundle:Sms:contacts',
            ],
        ],
        'public' => [
            'mautic_receive_sms' => [
                'path'       => '/sms/receive',
                'controller' => 'MauticSmsBundle:Api\SmsApi:receive',
            ],
        ],
        'api' => [
            'mautic_api_smsesstandard' => [
                'standard_entity' => true,
                'name'            => 'smses',
                'path'            => '/smses',
                'controller'      => 'MauticSmsBundle:Api\SmsApi',
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'items' => [
                'mautic.sms.smses' => [
                    'route'  => 'mautic_sms_index',
                    'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
                    'parent' => 'mautic.core.channels',
                    'checks' => [
                        'parameters' => [
                            'sms_enabled' => true,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
