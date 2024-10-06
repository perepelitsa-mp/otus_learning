<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

// Отключаем вывод ошибок
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_log("booking.php started");

// Отладочный вывод
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/booking_debug.log', print_r($_POST, true), FILE_APPEND);

header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['patient_name'], $_POST['appointment_time'], $_POST['procedure_id'], $_POST['doctor_id']))
{
    $patientName = htmlspecialchars($_POST['patient_name']);
    $appointmentTime = htmlspecialchars($_POST['appointment_time']);
    $procedureId = intval($_POST['procedure_id']);
    $doctorId = intval($_POST['doctor_id']);

    if(CModule::IncludeModule("iblock"))
    {
        // Форматируем время
        $appointmentTimeFormatted = date('d.m.Y H:i:s', strtotime($appointmentTime));
        $procedureName = '';
        $resProcedure = CIBlockElement::GetByID($procedureId);
        if($arProcedure = $resProcedure->GetNext())
        {
            $procedureName = $arProcedure['NAME'];
        }
        else
        {
            echo json_encode(array('status' => 'error', 'message' => 'Процедура не найдена.'));
            exit;
        }

        $arFilter = Array(
            "IBLOCK_ID"=>28,
            "PROPERTY_VREMYA"=>$appointmentTimeFormatted,
            "PROPERTY_PROTSEDURA"=>$procedureName,
            "PROPERTY_VRACH"=>$doctorId,
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, array("ID"));
        if($res->SelectedRowsCount() > 0)
        {
            echo json_encode(array('status' => 'error', 'message' => 'На это время уже запланирована процедура.'));
            exit;
        }

        $el = new CIBlockElement;
        $arLoadProductArray = Array(
            "IBLOCK_ID"      => 28,
            "NAME"           => $patientName,
            "PROPERTY_VALUES"=> array(
                "FIO" => $patientName,
                "VREMYA" => $appointmentTimeFormatted,
                "PROTSEDURA"   => $procedureName,
                "VRACH" => $doctorId,
            ),
        );

        if($PRODUCT_ID = $el->Add($arLoadProductArray))
            echo json_encode(array('status' => 'success'));
        else
            echo json_encode(array('status' => 'error', 'message' => $el->LAST_ERROR));
    }
    else
    {
        echo json_encode(array('status' => 'error', 'message' => 'Не удалось подключить модуль iblock.'));
    }
}
else
{
    echo json_encode(array('status' => 'error', 'message' => 'Некорректные данные.'));
}
?>
