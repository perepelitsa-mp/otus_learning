<?php
namespace MyCompany\MyCustomModule;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Component\ParameterSigner;

class EventHandlers
{
    public static function onEntityDetailsTabsInitialized(Event $event)
    {
        $params = $event->getParameters();
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/event_debug.txt', print_r($params, true));
        $entityId = $params['entityID'] ?? null; // Получаем ID сущности
        $entityTypeID = isset($params['entityTypeID']) ? $params['entityTypeID'] : null;
        $tabs = &$params['tabs'];

        if (!$entityId) {
            return new EventResult(EventResult::ERROR, 'ENTITY_ID not found');
        }
        $componentParams = [
            'ENTITY_ID' => $entityId,
        ];
        $signedParams = ParameterSigner::signParameters('mycompany:my.custom.list', $componentParams);
        if ($entityTypeID == \CCrmOwnerType::Contact) {
            array_unshift($tabs, [
                'id' => 'my_custom_tab',
                'name' => Loc::getMessage('MY_CUSTOM_TAB_NAME') ?: 'Моя вкладка для ДЗ',
                'loader' => [
                    'serviceUrl' => '/bitrix/components/mycompany/my.custom.list/ajax.php?' . http_build_query([
                            'site_id' => SITE_ID,
                            'sessid' => bitrix_sessid(),
                            'signedParameters' => $signedParams,
                        ]),
                    'componentData' => [
                        'componentName' => 'mycompany:my.custom.list',
                        'template' => '',
                        'signedParameters' => $signedParams,
                    ],
                ],
            ]);

            return new EventResult(EventResult::SUCCESS, [
                'tabs' => $tabs,
            ]);
        }
    }
}
