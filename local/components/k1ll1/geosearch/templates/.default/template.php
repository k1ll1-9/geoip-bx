<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadLanguageFile(__FILE__);
?>

<div id="app" data-params="<?= $this->getComponent()->getSignedParameters() ?>" ref="root">
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-4">
                <div class="card bg-light mb-3 mw-100 card-container" style="max-width: 18rem;">
                    <div class="card-header"><h5 class="card-title text-center"><?= Loc::getMessage("TITLE"); ?></h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="fetchUserData"
                              class="card-form d-flex flex-column justify-content-around align-items-center">
                            <div class="form-group text-center">
                                <label for="ip"><?= Loc::getMessage("LABEL"); ?></label>
                                <input type="text" class="form-control card-text-input" id="ip">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <?= Loc::getMessage("SUBMIT"); ?>
                            </button>
                        </form>
                        <div v-if="error" class="alert alert-danger text-center" role="alert">
                            {{error}}
                        </div>
                        <div v-else-if="Object.keys(res).length" class="alert-info p-3" role="alert">
                            <p class="m-0 text-center">Страна : {{res.UF_COUNTRY}}</p>
                            <p class="m-0 text-center">Город : {{res.UF_CITY}}</p>
                            <p class="m-0 text-center">Широта: {{res.UF_LAT}}</p>
                            <p class="m-0 text-center">Долгота: {{res.UF_LON}}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

