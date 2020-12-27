<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
if (!function_exists("showFilePropertyField"))
{
	function showFilePropertyField($name, $property_fields, $values, $max_file_size_show=50000)
	{
		$res = "";

		if (!is_array($values) || empty($values))
			$values = array(
				"n0" => 0,
			);

		if ($property_fields["MULTIPLE"] == "N")
		{
			$res = "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
		}
		else
		{
			$res = '
			<script type="text/javascript">
				function addControl(item)
				{
					var current_name = item.id.split("[")[0],
						current_id = item.id.split("[")[1].replace("[", "").replace("]", ""),
						next_id = parseInt(current_id) + 1;

					var newInput = document.createElement("input");
					newInput.type = "file";
					newInput.name = current_name + "[" + next_id + "]";
					newInput.id = current_name + "[" + next_id + "]";
					newInput.onchange = function() { addControl(this); };

					var br = document.createElement("br");
					var br2 = document.createElement("br");

					BX(item.id).parentNode.appendChild(br);
					BX(item.id).parentNode.appendChild(br2);
					BX(item.id).parentNode.appendChild(newInput);
				}
			</script>
			';

			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[0]\" id=\"".$name."[0]\"></label>";
			$res .= "<br/><br/>";
			$res .= "<label for=\"\"><input type=\"file\" size=\"".$max_file_size_show."\" value=\"".$property_fields["VALUE"]."\" name=\"".$name."[1]\" id=\"".$name."[1]\" onChange=\"javascript:addControl(this);\"></label>";
		}

		return $res;
	}
}

if (!function_exists("PrintPropsForm"))
{
	function PrintPropsForm($arSource = array(), $locationTemplate = ".default")
	{
		if (!empty($arSource)) {
            global $USER;
            $arUser = CUser::GetByID($USER->GetID())->Fetch();
		    foreach ($arSource as $arProperties) {

					if($arProperties["CODE"] == 'LOGICTIM_ADD_BONUS' || $arProperties["CODE"] == 'LOGICTIM_PAYMENT_BONUS') {
					    continue;
					} ?>
					<div class="form-item" data-property-id-row="<?=intval(intval($arProperties["ID"]))?>">
                        <?switch ($arProperties["TYPE"]) {
                            case 'CHECKBOX':
                                ?>
                                <label for="<?=$arProperties["FIELD_NAME"]?><?=$arProperties['REQUIRED'] == 'Y' ? '*' : ''?>"
                                       class="inp-field checkbox-box">
                                    <input type="checkbox"
                                           data-type="checkbox"
                                           name="<?=$arProperties["FIELD_NAME"]?>"
                                           <?if ($arUser['UF_TMP_USER'] != 'Y' && $arProperties['CODE'] == 'IS_BONUS_SYSTEM') {?>
                                               disabled="disabled"
                                               checked="checked"
                                           <?} else {?>
                                                <?=$arProperties["CHECKED"]=="Y" ? " checked" : ''?>
                                            <?}?>
                                           data-code="<?=$arProperties['CODE']?>"
                                           id="<?=$arProperties["FIELD_NAME"]?>"
                                           value="<?=$arProperties["CHECKED"]?>">

                                    <span class="checkmark"></span>
                                    <?=$arProperties["NAME"]?>
                                </label>
                                <?
                                break;
                            case 'TEXT':
                                $type = 'text';
                                $dataType = 'default';
                                if (strpos($arProperties['CODE'], 'EMAIL') !== false) {
                                    $type = 'email';
                                    $dataType = 'email';
                                } elseif (strpos($arProperties['CODE'], 'LOCATION') !== false) {
                                    $dataType = 'location';
                                }

                                $value = $arProperties["VALUE"];

                                if (strpos($arProperties["VALUE"], 'tmp_') !== false) {
                                    $value = '';
                                }
                                if(strpos(strtolower($arProperties["VALUE"]), 'ез имени') !== false) {
                                    $value = '';
                                }

                                ?>
                                <input class="inp-field <?=$arProperties['REQUIRED'] == 'Y' ? 'required' : ''?>"
                                       type="<?=$type?>"
                                       data-type="<?=$dataType?>"
                                       data-code="<?=$arProperties['CODE']?>"
                                       size="<?=$arProperties["SIZE1"]?>"
                                       value="<?=$value?>"
                                       name="<?=$arProperties["FIELD_NAME"]?>"
                                       placeholder="<?=$arProperties["NAME"]?><?=$arProperties['REQUIRED'] == 'Y' ? '*' : ''?>"
                                       id="<?=$arProperties["FIELD_NAME"]?>">
                                <?
                                break;
                            case 'RADIO':
                                ?>
                                <?foreach($arProperties["VARIANTS"] as $arVariants) { ?>
                                    <label for="<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>" class="radio-box">
                                        <input type="radio"
                                               class="inp-field"
                                               name="<?=$arProperties["FIELD_NAME"]?>"
                                               id=<?= $arProperties["FIELD_NAME"] ?>_<?= $arVariants["VALUE"] ?>
                                               <?=$arVariants["CHECKED"] == "Y" ? 'checked="checked"' :''?>
                                                <span class="checkmark"></span>
                                        <?= $arVariants["NAME"] ?>
                                    </label>
                                <?}
                                break;
                            case 'TEXTAREA':
                                $rows = ($arProperties["SIZE2"] > 10) ? 4 : $arProperties["SIZE2"];
                                ?>
                                <div class="title"><?=$arProperties['NAME']?><?=$arProperties['REQUIRED'] == 'Y' ? '*' : ''?></div>
                                <div class="form-item">
                                    <textarea name="<?=$arProperties["FIELD_NAME"]?>"
                                              id="<?=$arProperties["FIELD_NAME"]?>"
                                              cols="<?=$arProperties["SIZE1"]?>"
                                              class="inp-field <?=$arProperties['REQUIRED'] == 'Y' ? 'required' : ''?>"
                                              data-type="textarea"
                                              rows="<?=$rows?>"><?=$arProperties["VALUE"]?></textarea>
                                </div>
                                <?
                                break;
                            case 'LOCATION':
                                $value = 0;
                                if (is_array($arProperties["VARIANTS"]) && count($arProperties["VARIANTS"]) > 0) {
                                    foreach ($arProperties["VARIANTS"] as $arVariant) {
                                        if ($arVariant["SELECTED"] == "Y") {
                                            $value = $arVariant["ID"];
                                            break;
                                        }
                                    }
                                }
                                if(CSaleLocation::isLocationProMigrated()) {
                                    $locationTemplateP = $locationTemplate == 'popup' ? 'search' : 'steps';
                                    $locationTemplateP = $_REQUEST['PERMANENT_MODE_STEPS'] == 1 ? 'steps' : $locationTemplateP; // force to "steps"
                                }
                                if ($locationTemplateP == 'steps') {
                                    ?>
                                    <input type="hidden"
                                           id="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]"
                                           name="LOCATION_ALT_PROP_DISPLAY_MANUAL[<?=intval($arProperties["ID"])?>]"
                                           value="<?=($_REQUEST['LOCATION_ALT_PROP_DISPLAY_MANUAL'][intval($arProperties["ID"])] ? '1' : '0')?>">
                                    <?
                                }
                                CSaleLocation::proxySaleAjaxLocationsComponent(array(
                                    "AJAX_CALL" => "N",
                                    "COUNTRY_INPUT_NAME" => "COUNTRY",
                                    "REGION_INPUT_NAME" => "REGION",
                                    "CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
                                    "CITY_OUT_LOCATION" => "Y",
                                    "LOCATION_VALUE" => $value,
                                    "ORDER_PROPS_ID" => $arProperties["ID"],
                                    "ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
                                    "SIZE1" => $arProperties["SIZE1"],
                                ),
                                    array(
                                        "ID" => $value,
                                        "CODE" => "location",
                                        "SHOW_DEFAULT_LOCATIONS" => "Y",
                                        "JS_CALLBACK" => "submitFormProxy",
                                        "JS_CONTROL_DEFERRED_INIT" => intval($arProperties["ID"]),
                                        "JS_CONTROL_GLOBAL_ID" => intval($arProperties["ID"]),
                                        "DISABLE_KEYBOARD_INPUT" => "Y",
                                        "PRECACHE_LAST_LEVEL" => "Y",
                                        "PRESELECT_TREE_TRUNK" => "Y",
                                        "SUPPRESS_ERRORS" => "Y"
                                    ),
                                    $locationTemplateP,
                                    true,
                                    'location-block-wrapper'
                                );
                                break;
                            case 'SELECT':
                                ?>
                            <div class="title"><?=$arProperties['NAME']?><?=$arProperties['REQUIRED'] == 'Y' ? '*' : ''?></div>
                            <div class="form-item">
                                <select class="select-field" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                                    <?foreach($arProperties["VARIANTS"] as $arVariants) {?>
                                        <option value="<?=$arVariants["VALUE"]?>"<?=$arVariants["SELECTED"] == "Y" ? " selected" : ''?>><?=$arVariants["NAME"]?></option>
                                    <?}?>
                                </select>
                            </div>
                                <?
                                break;
                            case 'MULTISELECT':
                                ?>
                                <select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
                                    <?foreach($arProperties["VARIANTS"] as $arVariants):?>
                                        <option value="<?=$arVariants["VALUE"]?>"<?=$arVariants["SELECTED"] == "Y" ? " selected" : ''?>><?=$arVariants["NAME"]?></option>
                                    <?endforeach?>
                                </select>
                                <?
                                break;
                            case 'FILE':
                                ?>
                                <?=showFilePropertyField("ORDER_PROP_".$arProperties["ID"], $arProperties, $arProperties["VALUE"], $arProperties["SIZE1"])?>
                                <?if (strlen(trim($arProperties["DESCRIPTION"])) > 0) {?>
                                    <div class="bx_description"><?=$arProperties["DESCRIPTION"]?></div>
                                <?}?>
                                <?
                                break;
                            case 'DATE':
                                global $APPLICATION;

                                $APPLICATION->IncludeComponent('bitrix:main.calendar', '', array(
                                'SHOW_INPUT' => 'Y',
                                'INPUT_NAME' => "ORDER_PROP_".$arProperties["ID"],
                                'INPUT_VALUE' => $arProperties["VALUE"],
                                'SHOW_TIME' => 'N'
                                ), null, array('HIDE_ICONS' => 'N'));
                                break;
                        }

                        if(CSaleLocation::isLocationProEnabled()) {
                            $propertyAttributes = array(
                                'type' => $arProperties["TYPE"],
                                'valueSource' => $arProperties['SOURCE'] == 'DEFAULT' ? 'default' : 'form' // value taken from property DEFAULT_VALUE or it`s a user-typed value?
                            );

                            if(intval($arProperties['IS_ALTERNATE_LOCATION_FOR']))
                                $propertyAttributes['isAltLocationFor'] = intval($arProperties['IS_ALTERNATE_LOCATION_FOR']);

                            if(intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']))
                                $propertyAttributes['altLocationPropId'] = intval($arProperties['CAN_HAVE_ALTERNATE_LOCATION']);

                            if($arProperties['IS_ZIP'] == 'Y')
                                $propertyAttributes['isZip'] = true;
                        ?>

						<script>
							<?// add property info to have client-side control on it?>
							(window.top.BX || BX).saleOrderAjax.addPropertyDesc(<?=CUtil::PhpToJSObject(array(
									'id' => intval($arProperties["ID"]),
									'attributes' => $propertyAttributes
								))?>);
						</script>
					<?}?>
                </div>

                <?
				}
		}
	}
}
?>