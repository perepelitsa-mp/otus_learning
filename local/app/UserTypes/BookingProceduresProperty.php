<?php

namespace UserTypes;

use Bitrix\Main\Loader;
use Bitrix\Iblock\PropertyTable;

class BookingProceduresProperty
{
    public static function GetUserTypeDescription()
    {
        return array(
            'PROPERTY_TYPE'        => PropertyTable::TYPE_STRING,
            'USER_TYPE'            => 'booking_procedures', // уникальный код типа пользовательского свойства
            'DESCRIPTION'          => 'Запись на процедуры', // название типа пользовательского свойства
            'GetPropertyFieldHtml' => array(self::class, 'GetPropertyFieldHtml'), // метод отображения свойства в форме редактирования
            'GetPublicViewHTML'    => array(self::class, 'GetPublicViewHTML'), // метод отображения значения на публичной части
            'GetAdminListViewHTML' => array(self::class, 'GetAdminListViewHTML'),  // метод отображения значения в списке элементов
        );
    }

    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        $doctorId = $_REQUEST['ID'] ?? 0;
        $procedures = [];

        return 'idL ' . $doctorId;

        if ($doctorId && Loader::includeModule('iblock'))
        {
            // Получаем связанные процедуры с врачом
            $res = \CIBlockElement::GetProperty(29, $doctorId, array(), array("CODE" => "PROCEDURES"));
            while ($ob = $res->GetNext())
            {
                $procedures[] = $ob['VALUE'];
            }

            // Формируем HTML для вывода процедур
            $html = '<ul>';
            foreach ($procedures as $procedureId)
            {
                $procedure = \CIBlockElement::GetByID($procedureId)->GetNext();
                if ($procedure)
                {
                    $html .= '<li><a href="#" class="procedure-link" data-procedure-id="' . $procedureId . '">' . htmlspecialchars($procedure['NAME']) . '</a></li>';
                }
            }
            $html .= '</ul>';

            // Подключаем JavaScript для обработки кликов по процедурам
            $html .= '
            <script>
            BX.ready(function() {
                var procedureLinks = document.querySelectorAll(".procedure-link");
                procedureLinks.forEach(function(link) {
                    link.addEventListener("click", function(e) {
                        e.preventDefault();
                        var procedureId = this.getAttribute("data-procedure-id");

                        BX.PopupWindowManager.create("bookingPopup", null, {
                            content: \'<form id="bookingForm">' +
                '<label>ФИО пациента: <input type="text" name="patient_name"></label><br>\' +
                                     \'<label>Время записи: <input type="datetime-local" name="appointment_time"></label><br>\' +
                                     \'<input type="hidden" name="procedure_id" value="\' + procedureId + \'">\'+
                                     \'<button type="submit">Записаться</button>\' +
                                     \'</form>\',
                            titleBar: {content: BX.create("span", {html: "Запись на процедуру"})},
                            closeIcon: {right: "20px", top: "10px"},
                            width: 400,
                            height: 300,
                            overlay: {backgroundColor: "black", opacity: "80"}
                        }).show();

                        // Обработка отправки формы
                        BX.bind(BX("bookingForm"), "submit", function(event) {
                            event.preventDefault();
                            var formData = new FormData(this);

                            BX.ajax({
                                url: "/local/ajax/booking.php",
                                data: formData,
                                method: "POST",
                                dataType: "json",
                                processData: false,
                                contentType: false,
                                onsuccess: function(response) {
                                    if(response.status == "success") {
                                        alert("Запись успешно создана");
                                        BX.PopupWindowManager.getCurrentPopup().close();
                                    } else {
                                        alert(response.message);
                                    }
                                }
                            });
                        });
                    });
                });
            });
            </script>
            ';

            return $html;
        }

        return 'Процедуры не найдены.';
    }

    public static function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
    {
        // Здесь можно реализовать аналогичный вывод для публичной части, если это необходимо
        return self::GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName);
    }

    public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName)
    {
        return 'Запись на процедуры';
    }
}
