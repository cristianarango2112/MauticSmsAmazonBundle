<?php

/*
 * @copyright   2016 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\MauticSmsAmazonBundle\EventListener;


//use Mautic\CampaignBundle\Event as Events;
use Mautic\CampaignBundle\CampaignEvents;
use Mautic\CampaignBundle\Event\CampaignBuilderEvent;
use Mautic\CampaignBundle\Event\CampaignExecutionEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
//use Mautic\CoreBundle\Helper\CoreParametersHelper;
//use Mautic\MauticSmsAmazonBundle\Model\SmsAmazonModel;
use Mautic\MauticSmsAmazonBundle\SmsAmazonEvents;

/**
 * Class CampaignSubscriber.
 */
class CampaignSubscriber extends CommonSubscriber
{
    /**
     * @var CoreParametersHelper
     */
    //protected $coreParametersHelper;

    /**
     * @var SmsModel
     */
    //protected $smsModel;

    /**
     * CampaignSubscriber constructor.
     *
     * @param CoreParametersHelper $coreParametersHelper
     * @param SmsModel             $smsModel
     */
    public function __construct(
        //CoreParametersHelper $coreParametersHelper,
        //SmsModel $smsModel
    ) {
        //$this->coreParametersHelper = $coreParametersHelper;
        //$this->smsModel             = $smsModel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            CampaignEvents::CAMPAIGN_ON_BUILD     => ['onCampaignBuild', 0],
            SmsEvents::ON_CAMPAIGN_TRIGGER_ACTION => ['onCampaignTriggerAction', 0],
        ];
    }

    /**
     * @param CampaignBuilderEvent $event
     */
    public function onCampaignBuild(CampaignBuilderEvent $event)
    {
        //if ($this->coreParametersHelper->getParameter('sms_enabled')) {
            $event->addAction(
                'sms.send_text_sms_amazon',
                [
                    'label'            => 'mautic.campaign.sms.send_text_sms',
                    'description'      => 'mautic.campaign.sms.send_text_sms.tooltip',
                    'eventName'        => SmsAmazonEvents::ON_CAMPAIGN_TRIGGER_ACTION,
                    'formType'         => 'smssend_list',
                    'formTypeOptions'  => ['update_select' => 'campaignevent_properties_sms'],
                    'formTheme'        => 'MauticSmsAmazonBundle:FormTheme\SmsAmazonSendList',
                    'timelineTemplate' => 'MauticSmsAmazonBundle:SubscribedEvents\Timeline:index.html.php',
                    'channel'          => 'sms',
                    'channelIdField'   => 'sms',
                ]
                /*array(
                'eventName'       => SmsAmazonEvents::ON_CAMPAIGN_TRIGGER_ACTION,
                'label'           => 'TEST ACTION label',
                'description'     => 'TEST ACTION Description',
                // Set custom parameters to configure the decision
                'formType'        => 'helloworld_worlds',
                // Set a custom formTheme to customize the layout of elements in formType
                'formTheme'       => 'MauticSmsBundle:FormTheme\SmsSendList',
                // Set custom options to pass to the form type, if applicable
                'formTypeOptions' => array(
                    'world' => 'mars'
                )*/
            );
        //}
    }

    /**
     * @param CampaignExecutionEvent $event
     */
    public function onCampaignTriggerAction(CampaignExecutionEvent $event)
    {
    	return $event->setResult(TRUE);
        /*$lead  = $event->getLead();
        $smsId = (int) $event->getConfig()['sms'];
        $sms   = $this->smsModel->getEntity($smsId);

        if (!$sms) {
            return $event->setFailed('mautic.sms.campaign.failed.missing_entity');
        }

        $result = $this->smsModel->sendSms($sms, $lead, ['channel' => ['campaign.event', $event->getEvent()['id']]])[$lead->getId()];

        if ('Authenticate' === $result['status']) {
            // Don't fail the event but reschedule it for later
            return $event->setResult(false);
        }

        if (!empty($result['sent'])) {
            $event->setChannel('sms', $sms->getId());
            $event->setResult($result);
        } else {
            $result['failed'] = true;
            $result['reason'] = $result['status'];
            $event->setResult($result);
        }*/
    }
}
