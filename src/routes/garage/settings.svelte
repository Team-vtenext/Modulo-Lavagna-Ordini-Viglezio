<script>

  import { onMount } from "svelte";
  import { goto } from "@sapper/app";
  import jQuery from "jquery";
  import { backend } from "../../config/config.backend"; 
  import { _, locale, locales } from 'svelte-i18n';
  import { setCookie, getCookie } from '../../config/cookie.js';

  let baseURL = backend.baseurl;
  let spectrum;
  let settings = [""];

  onMount(async () => {
    if (!window.jQuery) window.jQuery = jQuery;

    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=getSettings",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    settings = await res.json();

    settings.showcloseorder = settings.showcloseorder == 1 ? true : false;
    settings.pinrequired = settings.pinrequired == 1 ? true : false;
    settings.topbartoggle = settings.topbartoggle == 1 ? true : false;
    settings.mondaytoggle = settings.mondaytoggle == 1 ? true : false;
    settings.tuesdaytoggle = settings.tuesdaytoggle == 1 ? true : false;
    settings.wednesdaytoggle = settings.wednesdaytoggle == 1 ? true : false;
    settings.thursdaytoggle = settings.thursdaytoggle == 1 ? true : false;
    settings.fridaytoggle = settings.fridaytoggle == 1 ? true : false;
    settings.saturdaytoggle = settings.saturdaytoggle == 1 ? true : false;
    settings.sundaytoggle = settings.sundaytoggle == 1 ? true : false;

    var readyState = document.readyState;

    if (readyState === "complete" || readyState === "loaded") {
      window.jQuery(function () {
        jQuery("#topbartoggle").prop("checked", settings.topbartoggle);
        jQuery("#mondaytoggle").prop("checked", settings.mondaytoggle);
        jQuery("#tuesdaytoggle").prop("checked", settings.tuesdaytoggle);
        jQuery("#wednesdaytoggle").prop("checked", settings.wednesdaytoggle);
        jQuery("#thursdaytoggle").prop("checked", settings.thursdaytoggle);
        jQuery("#fridaytoggle").prop("checked", settings.fridaytoggle);
        jQuery("#saturdaytoggle").prop("checked", settings.saturdaytoggle);
        jQuery("#sundaytoggle").prop("checked", settings.sundaytoggle);

        window.jQuery("#mondaytoggle").bootstrapToggle();
        window.jQuery("#tuesdaytoggle").bootstrapToggle();
        window.jQuery("#wednesdaytoggle").bootstrapToggle();
        window.jQuery("#thursdaytoggle").bootstrapToggle();
        window.jQuery("#fridaytoggle").bootstrapToggle();
        window.jQuery("#saturdaytoggle").bootstrapToggle();
        window.jQuery("#sundaytoggle").bootstrapToggle();
        window.jQuery("#topbartoggle").bootstrapToggle();
      });
    }
    const p = await import("spectrum-colorpicker2");
    spectrum = p;



    jQuery("#topbarcol").val(settings.topbarcol);
    jQuery("#mondaycol").val(settings.mondaycol);
    jQuery("#tuesdaycol").val(settings.tuesdaycol);
    jQuery("#wednesdaycol").val(settings.wednesdaycol);
    jQuery("#thursdaycol").val(settings.thursdaycol);
    jQuery("#fridaycol").val(settings.fridaycol);
    jQuery("#saturdaycol").val(settings.saturdaycol);
    jQuery("#sundaycol").val(settings.sundaycol);

    jQuery("#topbarcol").spectrum({ type: "text" });
    jQuery("#mondaycol").spectrum({ type: "text" });
    jQuery("#tuesdaycol").spectrum({ type: "text" });
    jQuery("#wednesdaycol").spectrum({ type: "text" });
    jQuery("#thursdaycol").spectrum({ type: "text" });
    jQuery("#fridaycol").spectrum({ type: "text" });
    jQuery("#saturdaycol").spectrum({ type: "text" });
    jQuery("#sundaycol").spectrum({ type: "text" });
  });

  async function handleSubmit(event) {
    let data = {
      username: event.target.username.value,
      accesskey: event.target.accesskey.value,
      showcloseorder: event.target.showcloseorder.checked,
      pinrequired: event.target.pinrequired.checked,
      topbarcol: event.target.topbarcol.value,
      mondaycol: event.target.mondaycol.value,
      tuesdaycol: event.target.tuesdaycol.value,
      wednesdaycol: event.target.wednesdaycol.value,
      thursdaycol: event.target.thursdaycol.value,
      fridaycol: event.target.fridaycol.value,
      saturdaycol: event.target.saturdaycol.value,
      sundaycol: event.target.sundaycol.value,
      topbartoggle: event.target.topbartoggle.checked,
      mondaytoggle: event.target.mondaytoggle.checked,
      tuesdaytoggle: event.target.tuesdaytoggle.checked,
      wednesdaytoggle: event.target.wednesdaytoggle.checked,
      thursdaytoggle: event.target.thursdaytoggle.checked,
      fridaytoggle: event.target.fridaytoggle.checked,
      saturdaytoggle: event.target.saturdaytoggle.checked,
      sundaytoggle: event.target.sundaytoggle.checked,
      language: event.target.language.value,
    };
    
    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=saveSettings",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );

    const json = await res.json();

    if (json.success == "true") {
      $locale = data['language'];
      setCookie('locale',  data['language']);
      var bootboxalert = bootbox.alert({
        message: $_('settings.LBL_SAVE_SETTINGS'),
        size: "small",
      });
      setTimeout(function () {
        bootboxalert.modal("hide");
        goto("/garage/home");
      }, 2000);
    }
  }

  function handleFactoryReset() {
    bootbox.confirm($_('settings.LBL_RESET_CONFIRM'),
      function (result) {
        if (result !== null && result != "") {
          factoryReset();
        }
      }
    );
  }
  async function factoryReset() {
    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=factoryReset",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    const json = await res.json();
    goto("/");
  }

  const handleLogout = async () => {
    const response = await fetch("/logout", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
      },
    });

    const parsed = await response.json();
    goto("/");
  };
</script>

<main role="main" class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-sm-6 col-md-4  text-center">
      <h1>{$_('settings.LBL_SETTINGS_TITLE')}</h1>

      <form on:submit|preventDefault={handleSubmit}>
        <div class="form-group text-left">
          <label for="language"><i class="far fa-globe-americas" /> {$_('settings.LBL_LANGUAGE')}</label>
          <select id="language" bind:value={settings.language} class="form-control form-control-lg">
            {#each $locales as item}
            <option value="{item}">{$_('languages.' + item)}</option>
          {/each}            
          </select>
        </div>
        <div class="form-group text-left">
          <label for="username"><i class="far fa-user" /> {$_('settings.LBL_USERNAME')}</label>
          <input
            type="text"
            class="form-control form-control-lg"
            id="username"
            readonly
            bind:value={settings.username}
            placeholder="Username"
          />
          <a
            href="."
            class="btn btn-danger"
            on:click|preventDefault={() => handleLogout()}
            ><i class="fas fa-check" /> {$_('settings.LBL_LOGOUT')}</a
          >
        </div>
        <div class="form-group text-left">
          <label for="accesskey"><i class="far fa-key" /> {$_('settings.LBL_ACCESS_KEY')}</label>
          <input
            type="text"
            class="form-control form-control-lg"
            id="accesskey"
            readonly
            placeholder="123456789"
            bind:value={settings.accesskey}
          />
        </div>
        <p />
        <div class="form-check text-left">
          <input
            class="form-check-input"
            type="checkbox"
            id="showcloseorder"
            bind:checked={settings.showcloseorder}
          />
          <label class="form-check-label" for="showcloseorder">
            {$_('settings.LBL_SHOW_CLOSE_BUTTON')}
          </label>
        </div>
        <p />
        <div class="form-check text-left">
          <input
            class="form-check-input"
            type="checkbox"
            id="pinrequired"
            bind:checked={settings.pinrequired}
          />
          <label class="form-check-label" for="pinrequired">
            {$_('settings.LBL_CLOSE_PIN_REQUIRED')}
          </label>
        </div>
        <p />
        <a
          href="."
          class="btn btn-danger"
          on:click|preventDefault={() => handleFactoryReset()}
          ><i class="fas fa-power-off" /> {$_('settings.FACTORY_RESET')}</a
        >

        <p />
        <h3>{$_('settings.LBL_COLOR_SELECTION')}</h3>

        <div class="row">
          <div class="col-12">
            <div class="form-group text-left">
              <label for="topbarcol"
                ><i class="far fa-fill" /> {$_('settings.NAVIGATION_BAR')}</label
              >
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="topbarcol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                data-toggle="toggle"
                id="topbartoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group text-left">
              <label for="mondaycol"><i class="far fa-fill" /> {$_('settings.MONDAY')}</label>
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="mondaycol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                bind:checked={settings.mondaytoggle}
                data-toggle="toggle"
                id="mondaytoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group text-left">
              <label for="tuesdaycol"><i class="far fa-fill" /> {$_('settings.TUESDAY')}</label>
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="tuesdaycol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                bind:checked={settings.tuesdaytoggle}
                data-toggle="toggle"
                id="tuesdaytoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group text-left">
              <label for="wednesdaycol"
                ><i class="far fa-fill" /> {$_('settings.WEDNESDAY')}</label
              >
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="wednesdaycol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                bind:checked={settings.wednesdaytoggle}
                data-toggle="toggle"
                id="wednesdaytoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group text-left">
              <label for="thursdaycol"><i class="far fa-fill" /> {$_('settings.THURSDAY')}</label>
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="thursdaycol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                bind:checked={settings.thursdaytoggle}
                data-toggle="toggle"
                id="thursdaytoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group text-left">
              <label for="fridaycol"><i class="far fa-fill" /> {$_('settings.FRIDAY')}</label>
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="fridaycol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                bind:checked={settings.fridaytoggle}
                data-toggle="toggle"
                id="fridaytoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group text-left">
              <label for="saturdaycol"><i class="far fa-fill" /> {$_('settings.SATURDAY')}</label>
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="saturdaycol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                bind:checked={settings.saturdaytoggle}
                data-toggle="toggle"
                id="saturdaytoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
          <div class="col-6">
            <div class="form-group text-left">
              <label for="sundaycol"><i class="far fa-fill" /> {$_('settings.SUNDAY')}</label>
              <input
                type="button"
                class="form-control form-control-lg"
                readonly
                id="sundaycol"
                placeholder="#000000;"
              />
              <input
                type="checkbox"
                bind:checked={settings.sundaytoggle}
                data-toggle="toggle"
                id="sundaytoggle"
                data-onstyle="dark"
                data-on="{$_('settings.LBL_DARK')}"
                data-off="{$_('settings.LBL_LIGHT')}"
                data-offstyle="light"
                data-style="border"
              />
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg btn-block"
          ><i class="fal fa-save" /> {$_('settings.LBL_BTN_SAVE')}</button
        >
        <br />
      </form>
    </div>
  </div>
</main>
