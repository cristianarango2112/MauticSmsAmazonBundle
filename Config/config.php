<?php

/*
 * @copyright   2017 Atlas Global Solutions. All rights reserved
 * @author      Atlas Global Solutions
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

echo( debug_backtrace() );
die();
return [
	'name'        => 'Amazon SMS',
    'description' => 'Enables integration with Mautic and Amazon SMS',
    'version'     => '1.0',
    'author'      => 'Atlas Globlal Solutions, Inc.',
    'services' => [
        'events' => [
            'mautic.smsAmazon.campaignbundle.subscriber' => [
                'class'     => 'MauticPlugin\MauticSmsAmazonBundle\EventListener\CampaignSubscriber',
                'arguments' => [
                    'mautic.helper.core_parameters',
                    'mautic.sms.model.sms',
                ],
            ],
            /*'mautic.sms.configbundle.subscriber' => [
                'class' => 'MauticPlugin\MauticSmsAmazonBundle\EventListener\ConfigSubscriber',
            ],
            'mautic.sms.smsbundle.subscriber' => [
                'class'     => 'MauticPlugin\MauticSmsAmazonBundle\EventListener\SmsSubscriber',
                'arguments' => [
                    'mautic.core.model.auditlog',
                    'mautic.page.model.trackable',
                    'mautic.page.helper.token',
                    'mautic.asset.helper.token',
                ],
            ],
            'mautic.sms.channel.subscriber' => [
                'class' => \MauticPlugin\MauticSmsAmazonBundle\EventListener\ChannelSubscriber::class,
            ],
            'mautic.sms.message_queue.subscriber' => [
                'class'     => \MauticPlugin\MauticSmsAmazonBundle\EventListener\MessageQueueSubscriber::class,
                'arguments' => [
                    'mautic.sms.model.sms',
                ],
            ],
            'mautic.sms.stats.subscriber' => [
                'class'     => \MauticPlugin\MauticSmsAmazonBundle\EventListener\StatsSubscriber::class,
                'arguments' => [
                    'doctrine.orm.entity_manager',
                ],
            ],*/
        ],
        'forms' => [
            'mautic.form.type.sms' => [
                'class'     => 'MauticPlugin\MauticSmsAmazonBundle\Form\Type\SmsType',
                'arguments' => 'mautic.factory',
                'alias'     => 'sms',
            ],
            'mautic.form.type.smsconfig' => [
                'class' => 'MauticPlugin\MauticSmsAmazonBundle\Form\Type\ConfigType',
                'alias' => 'smsconfig',
            ],
            'mautic.form.type.smssend_list' => [
                'class'     => 'MauticPlugin\MauticSmsAmazonBundle\Form\Type\SmsSendType',
                'arguments' => 'router',
                'alias'     => 'smssend_list',
            ],
            'mautic.form.type.sms_list' => [
                'class' => 'MauticPlugin\MauticSmsAmazonBundle\Form\Type\SmsListType',
                'alias' => 'sms_list',
            ],
        ],
        'helpers' => [
            'mautic.helper.sms' => [
                'class'     => 'MauticPlugin\MauticSmsAmazonBundle\Helper\SmsHelper',
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
                'class'     => 'MauticPlugin\MauticSmsAmazonBundle\Api\AmazonApi',
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.amazon.service',
                    'mautic.helper.phone_number',
                    '%mautic.sms_amazon_sending_phone_number%',
                    'monolog.logger.mautic',
                ],
                'alias' => 'sms_api',
            ],
            'mautic.amazon.service' => [
                'class'     => 'Services_Amazon',
                'arguments' => [
                    '%mautic.sms_username%',
                    '%mautic.sms_password%',
                ],
                'alias' => 'amazon_service',
            ],
        ],
        'models' => [
            'mautic.sms.model.sms' => [
                'class'     => 'MauticPlugin\MauticSmsAmazonBundle\Model\SmsAmazonModel',
                'arguments' => [
                    'mautic.page.model.trackable',
                    'mautic.lead.model.lead',
                    'mautic.channel.model.queue',
                    'mautic.sms.api',
                ],
            ],
        ],
    ],
    'routes' => [
        'main' => [
            'mautic_sms_amazon_index' => [
                'path'       => '/sms/{page}',
                'controller' => 'MauticSmsAmazonBundle:Sms:index',
            ],
            'mautic_sms_amazon_action' => [
                'path'       => '/sms/{objectAction}/{objectId}',
                'controller' => 'MauticSmsAmazonBundle:Sms:execute',
            ],
            'mautic_sms_amazon_contacts' => [
                'path'       => '/sms/view/{objectId}/contact/{page}',
                'controller' => 'MauticSmsAmazonBundle:Sms:contacts',
            ],
        ],
        'public' => [
            'mautic_receive_sms_amazon' => [
                'path'       => '/sms/receive',
                'controller' => 'MauticSmsAmazonBundle:Api\SmsApi:receive',
            ],
        ],
        'api' => [
            'mautic_api_sms_amazon_esstandard' => [
                'standard_entity' => true,
                'name'            => 'smses',
                'path'            => '/smses',
                'controller'      => 'MauticAmazonSmsBundle:Api\SmsApi',
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'items' => [
                'mautic.smsamazon.smses' => [
                    'route'  => 'mautic_sms_amazon_index',
                    'access' => ['sms:smses:viewown', 'sms:smses:viewother'],
                    'parent' => 'mautic.core.channels',
                    'checks' => [
                        'parameters' => [
                            'sms_enabled' => true,
                        ],
                    ],
                    'priority' => 70,
                ],
            ],
        ],
    ],
    'parameters' => [
        'sms_enabled'              => true,
        'sms_username'             => null,
        'sms_password'             => null,
        'sms_sending_phone_number' => null,
        'sms_frequency_number'     => null,
        'sms_frequency_time'       => null,
    ]
];

?>