<script>
  import Keypad from "./Keypad.svelte";
  import Modal from "./Modal.svelte";
  import { goto } from "@sapper/app";
  import { onMount } from "svelte";
  import { backend } from "../config/config.backend";
  import { _ } from 'svelte-i18n';

  let baseURL = backend.baseurl;
  let topbarcol;
  let topbartext;
  let currentUser;
  let modalData = [];
  let showModal = false;
  let showModal2 = false;
  let showModal3 = false;
  let showModal4 = false;
  let showOtherPinPad = false;
  let otherActivities = false;
  let pin;
  let pin2;

  async function getTodayEntryLines(authentication) {
    let data = {
      authentication: authentication,
    };
    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=getTodayEntryLines",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    showModal4 = false;
    let result = await res.json();

    modalData = result;

    showModal2 = true;
    return result;
  }

  const handleSettings = async () => {
    let data = {
      cardId: pin,
      isAdmin: true,
    };

    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=checkLogin",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify(data),
      }
    );

    const res = await response.json();

    if (res.status == "success" && res.employee_id != "admin") {
      bootbox.alert({
        message: "<p align='center'>" + $_('backend.LBL_WRONG_LOGIN') + "</p>",
        animate: true,
        size: "small",
        backdrop: true,
        centerVertical: true,
        className: "animate__animated animate__shakeX",
      });
    } else {
      showModal = false;
      pin = "";
      goto("/garage/settings");
    }
  };

  async function fetchServices() {
    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=getOtherServices",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    const json = await res.json();
    otherActivities = json.data;
  }

  async function createOtherTimesheet(pin, service) {
    let data = {
      cardId: pin,
      service_id: service[0],
      servicename: service[1],
      isAdmin: true,
    };

    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=createOtherTimesheet",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );

    const result = await res.json();

    showOtherPinPad = false;
    localStorage.setItem("refresh", "true");
    var bootboxalert = bootbox.alert({
      message: $_('backend.' + result.message),
      size: "small",
    });
    setTimeout(function () {
      bootboxalert.modal("hide");
    }, backend.popupAutocloseTime);
  }

  onMount(async () => {
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
    let settings = await res.json();
    topbarcol = settings.topbarcol;
    topbartext = settings.topbartoggle == 1 ? "#000" : "#fff";

    currentUser = settings.username;
  });

  async function createEntryLine(type, authentication) {
    let data = {
      authentication: authentication,
      type: type,
    };
    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=createEntryLine",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    showModal3 = false;
    let res = await response.json();
    localStorage.setItem("refresh", "true");
    var bootboxalert = bootbox.alert({
      message: $_('backend.' + res.message),
      size: "small",
    });
    setTimeout(function () {
      bootboxalert.modal("hide");
    }, backend.popupAutocloseTime);
  }
</script>

<nav
  class="navbar navbar-expand-md navbar-light bg-light fixed-topbar"
  style="background-color:{topbarcol}!important;"
>
  <a class="navbar-brand" href="." onclick="location.reload();"
    ><img
      alt=""
      src="images/logo-dark.png"
      style="margin-right:20px;height:2.5em;"
    />
  </a>
  <button
    class="navbar-toggler"
    type="button"
    data-toggle="collapse"
    data-target="#navbarsExampleDefault"
    aria-controls="navbarsExampleDefault"
    aria-expanded="false"
    aria-label="Toggle navigation"
  >
    <span class="navbar-toggler-icon" />
  </button>

  <div class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="garage/home" style="color:{topbartext};"
          ><i class="fad fa-cars" /> {$_('nav.home')}
          <span class="sr-only">(current)</span></a
        >
      </li>
    </ul>

    <form class="form-inline my-2 my-lg-0 mr-auto">
      <a href="." on:click|preventDefault={() => fetchServices()}
        ><button class="btn btn-warning my-2 my-sm-0">
          <i class="fad fa-folder-open" /> {$_('nav.LBL_BTN_OTHER_TIMESHEETS')}</button
        ></a
      >&nbsp;
      <a
        href="."
        on:click|preventDefault={() => {
          showModal4 = true;
          pin2 = "";
        }}
        ><button class="btn btn-info my-2 my-sm-0">
          <i class="fad fa-folder-open" /> {$_('nav.LBL_BTN_PRESENCES')}</button
        ></a
      >
    </form>

    <form class="form-inline my-2 my-lg-0">
      <a
        href="."
        on:click|preventDefault={() => {
          showModal3 = "Entrance";
          pin2 = "";
        }}
        ><button class="btn btn-success my-2 my-sm-0">
          <i class="fad fa-id-badge" /> {$_('nav.LBL_BTN_ENTRY')}</button
        ></a
      >&nbsp;
      <a
        href="."
        on:click|preventDefault={() => {
          showModal3 = "Leaving";
          pin2 = "";
        }}
        ><button class="btn btn-danger my-2 my-sm-0">
          <i class="fas fa-id-badge" /> {$_('nav.LBL_BTN_EXIT')}</button
        ></a
      >&nbsp;
    </form>
    <a href="." on:click|preventDefault={() => (showModal = true)}
      ><button class="btn btn my-2 my-sm-0">
        <i class="fad fa-user-cog" /> {currentUser}</button
      ></a
    >
  </div>
</nav>
{#if showModal}
  <Modal on:close={() => (showModal = false)}>
    <Keypad bind:value={pin} on:submit={handleSettings} />
  </Modal>
{/if}

{#if showModal2}
  <Modal on:close={() => (showModal2 = false)}>
    {#await modalData}
      <p />
    {:then result}
      {#if !result.data}
        <p style="text-align:center;font-size:26px;">{$_('backend.' + result.error)}</p>
      {:else}
        <div
          class="list-group mb-3 text-left"
          style="max-height:30rem;overflow-y:auto;"
        >
          {#each result.data as data}
            {#each data.lines as line}
              {#if line.type == "Entrance"}
                <a
                  href="."
                  class="list-group-item list-group-item-action"
                  style="pointer-events: none;background-color:rgb(41 167 68 / 30%);"
                >
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">
                      <i class="fas fa-user" />&nbsp;{data.employee_id}&nbsp;
                    </h5>
                    <h6>
                      {$_('nav.LBL_BTN_ENTRY')} - {new Date(line.hour * 1000)
                        .toISOString()
                        .substr(11, 8)}
                    </h6>
                  </div>
                </a>
              {:else if line.type == "Leaving"}
                <a
                  href="."
                  class="list-group-item list-group-item-action"
                  style="pointer-events: none;background-color:rgb(220 53 69 / 30%);"
                >
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">
                      <i class="fas fa-user" />&nbsp;{data.employee_id}&nbsp;
                    </h5>
                    <h6>
                      {$_('nav.LBL_BTN_EXIT')} - {new Date(line.hour * 1000)
                        .toISOString()
                        .substr(11, 8)}
                    </h6>
                  </div>
                </a>
              {:else}
                <a
                  href="."
                  class="list-group-item list-group-item-action"
                  style="pointer-events: none;"
                >
                  <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">
                      &nbsp;{line.timesheet_name}&nbsp;
                    </h5>
                    <h6>
                      {line.servicename} - {line.total_hours}
                    </h6>
                  </div>
                </a>
              {/if}
            {/each}
          {/each}
        </div>
      {/if}
    {/await}
    <div style="text-align:right;" slot="footer">
    </div>
  </Modal>
{/if}

{#if showModal3 != false}
  <Modal on:close={() => (showModal3 = false)}>
    <Keypad bind:value={pin2} on:submit={createEntryLine(showModal3, pin2)} />
  </Modal>
{/if}
{#if showModal4}
  <Modal on:close={() => (showModal4 = false)}>
    <Keypad bind:value={pin2} on:submit={() => getTodayEntryLines(pin2)} />
  </Modal>
{/if}
{#if showOtherPinPad}
  <Modal on:close={() => (showOtherPinPad = false)}>
    <Keypad
      bind:value={pin2}
      on:submit={() => createOtherTimesheet(pin2, showOtherPinPad)}
    />
  </Modal>
{/if}

{#if otherActivities}
  <Modal on:close={() => (otherActivities = false)}>
    <div slot="header" style="text-align:center;">
      <h1>{$_('nav.LBL_OTHER_TIMESHEETS_TITLE')}</h1>
    </div>
    {#await otherActivities}
      <p />
    {:then result}
      <div class="list-group mb-3">
        {#each result as data}
          <div
            class="list-group-item list-group-item-action p1-3 pt2"
            on:click={() => {
              otherActivities = false;
              showOtherPinPad = [data.id, data.servicename];
              pin2 = "";
            }}
          >
            {data.servicename}
          </div>
        {/each}
      </div>
    {/await}
    <div style="text-align:right;" slot="footer" />
  </Modal>
{/if}

<style>
  .fixed-topbar {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 200;
  }
</style>
