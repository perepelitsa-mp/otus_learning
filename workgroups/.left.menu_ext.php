<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

if (SITE_TEMPLATE_ID === "bitrix24")
{
	$aMenuLinks = array();
	return;
}

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/intranet/public/workgroups/.left.menu_ext.php");

global $APPLICATION;

// You can change this url templates
$strGroupSubjectLinkTemplate = COption::GetOptionString("socialnetwork", "subject_path_template", SITE_DIR."workgroups/group/search/#subject_id#/", SITE_ID);
$strGroupLinkTemplate = COption::GetOptionString("socialnetwork", "group_path_template", SITE_DIR."workgroups/group/#group_id#/", SITE_ID);

if (CModule::IncludeModule("socialnetwork"))
{
	if (!function_exists("__CheckPath4Template"))
	{
		function __CheckPath4Template($pageTemplate, $currentPageUrl, &$arVariables)
		{
			$pageTemplateReg = preg_replace("'#[^#]+?#'", "([^/]+?)", $pageTemplate);

			$arValues = array();
			if (preg_match("'^".$pageTemplateReg."'", $currentPageUrl, $arValues))
			{
				$arMatches = array();
				if (preg_match_all("'#([^#]+?)#'", $pageTemplate, $arMatches))
				{
					for ($i = 0, $cnt = count($arMatches[1]); $i < $cnt; $i++)
						$arVariables[$arMatches[1][$i]] = $arValues[$i + 1];
				}
				return True;
			}

			return False;
		}
	}

	$arGroup = false;
	$arVariables = array();
	$componentPage = __CheckPath4Template($strGroupLinkTemplate, $_SERVER["REQUEST_URI"], $arVariables);
	if ($componentPage && intval($arVariables["group_id"]) > 0)
		$arGroup = CSocNetGroup::GetByID(intval($arVariables["group_id"]));

	$dbGroupSubjects = CSocNetGroupSubject::GetList(
		array("SORT" => "ASC", "NAME" => "ASC"),
		array("SITE_ID" => SITE_ID),
		false,
		false,
		array("ID", "NAME")
	);

	$aMenuLinksAdd = array();
	while ($arGroupSubject = $dbGroupSubjects->GetNext())
	{
		$arLinks = array();
		if ($arGroup && $arGroup["SUBJECT_ID"] == $arGroupSubject["ID"])
			$arLinks = array($_SERVER["REQUEST_URI"]);

		$aMenuLinksAdd[] = array(
			$arGroupSubject["NAME"],
			str_replace("#subject_id#", $arGroupSubject["ID"], $strGroupSubjectLinkTemplate),
			$arLinks,
			array(),
			""
		);
	}

	$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksAdd);

	$aMenuLinks[] = array(GetMessage("WORKGROUPS_MENU_ARCHIVE"), str_replace("#subject_id#", -1, $strGroupSubjectLinkTemplate), array(), array(), "");
}