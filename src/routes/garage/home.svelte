<script>
  import Box from "../../components/Box.svelte";
  import ModalDetails from "../../components/ModalDetails.svelte";
  import Modal from "../../components/Modal.svelte";
  import { onMount } from "svelte";
  import Keypad from "../../components/Keypad.svelte";
  import jQuery from "jquery";
  import { backend } from "../../config/config.backend";
  import { _ } from "svelte-i18n";

  let baseURL = backend.baseurl;
  let seconds = 0;
  let promise = [];
  let promise2 = [];
  let closeOrderModal = false;
  let createDeposit = false;
  let showTimesheetsModal = false;
  let timesheetData = [];
  let showTimeSheetModal = false;
  let showAdminTimeSheetModal = false;
  let showOtherTimesheetsModal = false;
  let showModalTimesheetsUser = "";
  let showModalDetails = false;
  let pin;
  let isAdmin;
  let settings;
  let currentColor;
  let orders;
  let jobs;

  function closeOrder(crmid) {
    closeSalesOrder(crmid).then((res) => {
      var bootboxalert = bootbox.alert({
        message: res.error,
        size: "small",
      });
      setTimeout(function () {
        bootboxalert.modal("hide");
      }, backend.popupAutocloseTime);
    });
    closeOrderModal = false;
  }

  function resetIntervals() {
    clearInterval(jobs);
    clearInterval(orders);
    jobs = setInterval(fetchData2, 60000);
    orders = setInterval(fetchData, 60000);
    fetchData();
    fetchData2();
  }

  async function closeSalesOrder(crmid) {
    let data = {
      crmid: crmid,
    };
    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=closeSalesOrder",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    return response.json();
  }

  async function closeOtherTimesheet(crmid, cardId, isAdmin) {
    let data = {
      crmid: crmid,
      cardId: cardId,
      isAdmin: isAdmin,
    };

    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=closeOtherTimesheet",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    showOtherTimesheetsModal = false;
    let res = await response.json();

    resetIntervals();
    var bootboxalert = bootbox.alert({
      message: $_("backend." + res.message),
      size: "small",
    });
    setTimeout(function () {
      bootboxalert.modal("hide");
    }, backend.popupAutocloseTime);
  }

  async function checkEmployeeCard(cardId, isAdmin) {
    let data = {
      cardId: cardId,
      isAdmin: isAdmin,
    };
    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=checkLogin",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    return response.json();
  }

  function openDepositModal(crmid, veicoliid, accountid) {
    showModalDetails = false;
    pin = "";
    createDeposit = {
      crmid: crmid,
      veicoliid: veicoliid,
      accountid: accountid,
    };
  }

  function promptCardShowTimesheets(data, pin, admin) {
    checkEmployeeCard(pin, admin).then((res) => {
      if (res.status == "success") {
        showTimesheetsModal = false;
        if (res.employee_id == null) {
          showModalTimesheetsUser = "none";
          showTimeSheetModal = true;
          timesheetData = [];
        } else {
          showModalTimesheetsUser = res.employee_id;
          showTimeSheetModal = true;
          timesheetData = data;
        }
        isAdmin = false;
        showModalDetails = false;
      }
    });
  }

  async function closeTimesheetAsAdmin(crmid) {
    let data = {
      crmid: crmid,
    };
    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=closeAsAdmin",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    showAdminTimeSheetModal = false;
    let res = await response.json();

    resetIntervals();
    var bootboxalert = bootbox.alert({
      message: $_("backend." + res.message),
      size: "small",
    });
    setTimeout(function () {
      bootboxalert.modal("hide");
    }, backend.popupAutocloseTime);
  }

  async function updateInventoryRow(
    dataValues,
    authentication,
    admin,
    autocloseproject
  ) {
    if (admin) {
      checkEmployeeCard(pin, admin).then((res) => {
        if (res.status == "success") {
          showTimesheetsModal = false;
          if (res.employee_id == null) {
            updateInventoryModal = false;
          } else {
            updateInventoryModal = false;
            isAdmin = false;
            showModalTimesheetsUser = dataValues[1];
            showAdminTimeSheetModal = true;
            timesheetData = dataValues[2];
          }
          showModalDetails = false;
        }
      });
    } else {
      let data = {
        authentication: authentication,
        crmid: dataValues[0],
        lineItemId: dataValues[1],
        isAdmin: admin,
        autoClose: autocloseproject,
      };

      const response = await fetch(
        baseURL +
          "modules/SDK/src/modules/Webservices/backend.php?action=updateInventoryRow",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(data),
        }
      );
      resetIntervals();
      updateInventoryModal = false;
      let res = await response.json();
      pin = "";
      var bootboxalert = bootbox.alert({
        message: $_(res),
        size: "small",
      });
      setTimeout(function () {
        bootboxalert.modal("hide");
      }, backend.popupAutocloseTime);
    }
  }

  async function fetchData() {
    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=getOrders",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    seconds = 0;
    const json = await res.json();
    promise = json.data;
  }

  async function fetchData2() {
    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=getJobs",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    seconds = 0;
    const json = await res.json();

    promise2 = json.data;
  }

  onMount(async () => {
    if (window && window.sessionStorage) {
      let token = sessionStorage.getItem("token");

      if (!token) {
        window.location.href = "/";
      }
    }

    if (window && window.localStorage) {
      var originalSetItem = localStorage.setItem;
      localStorage.setItem = function (key, value) {
        var event = new Event("itemInserted");

        event.value = value; // Optional..
        event.key = key; // Optional..

        document.dispatchEvent(event);

        originalSetItem.apply(this, arguments);
      };

      var localStorageSetHandler = function (e) {
        if (e.key == "refresh" && e.value == "true") {
          window.localStorage.removeItem("refresh");
          //localStorage.setItem("refresh", "false");
          resetIntervals();
        }
      };

      document.addEventListener("itemInserted", localStorageSetHandler, false);
    }

    if (!window.jQuery) window.jQuery = jQuery;

    var readyState = document.readyState;
    if (readyState === "complete" || readyState === "loaded") {
      window.jQuery(function () {
        window.jQuery('[data-toggle="tooltip"]').tooltip();
        window.jQuery("#test").tooltip("show");
      });
    }

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

    orders = setInterval(fetchData, 60000);
    jobs = setInterval(fetchData2, 60000);
    setInterval(liveSeconds, 1000);
    fetchData();
    fetchData2();

    return () => clearInterval(interval);
  });

  function getWorkTime(worktime, enddate = null) {
    var timeStart = new Date(worktime).getTime();

    if (enddate != null) var now = new Date(enddate).getTime();
    else var now = new Date().getTime();

    var hourDiff = now - timeStart; //in mscr
    var secDiff = hourDiff / 1000; //in s
    var minDiff = hourDiff / 60 / 1000; //in minutes
    var hDiff = hourDiff / 3600 / 1000; //in hours
    var humanReadable = {};
    humanReadable.hours = Math.floor(hDiff);
    humanReadable.minutes = minDiff - 60 * humanReadable.hours;
    humanReadable.seconds = secDiff % 60;

    if (humanReadable.hours < 1 && humanReadable.minutes > 1)
      return parseInt(humanReadable.minutes) + "min";
    else
      return (
        parseInt(humanReadable.hours) +
        "h " +
        parseInt(humanReadable.minutes) +
        "min "
      );
  }

  function liveSeconds() {
    seconds += 1;
    if (seconds == 60) seconds = 0;
  }

  function getTotalWorkTime(timesheets) {
    var i;
    var startTimes = [];
    for (i = 0; i < timesheets.length; i++) {
      if (timesheets[i].timesheet_status == "In Progress")
        startTimes.push(
          timesheets[i].timesheet_date +
            " " +
            new Date(timesheets[i].start_time * 1000)
              .toISOString()
              .substr(11, 8)
        );
    }

    var orderedDates = startTimes.sort(function (a, b) {
      return Date.parse(a) - Date.parse(b);
    });
    if (orderedDates[0]) return getWorkTime(orderedDates[0]);
    else return "";
  }

  function getTimesheetsTotalHours(data, employee) {
    let total = 0;

    for (let i = 0; i < data.length; i++) {
      if (data[i].employee_id == employee || employee == "admin")
        total += parseFloat(data[i].total_hours);
    }

    var decimalTime = parseFloat(total);
    decimalTime = decimalTime * 60 * 60;
    var hours = Math.floor(decimalTime / (60 * 60));
    decimalTime = decimalTime - hours * 60 * 60;
    var minutes = Math.round(decimalTime / 60);
    decimalTime = decimalTime - minutes * 60;
    var seconds = Math.round(decimalTime);

    if (hours < 1 && minutes > 1) return parseInt(minutes) + "min";
    else return parseInt(hours) + "h " + parseInt(minutes) + "min ";
  }

  function currentDayIconColor(duedate, type) {
    let iconColor;

    if (duedate) {
      let dateParts = duedate.split("-");
      let currentDate = new Date(
        +dateParts[2],
        dateParts[1] - 1,
        +dateParts[0]
      );
      let currentDay = currentDate.getDay();

      switch (currentDay) {
        case 0:
          currentColor = settings.sundaycol;
          iconColor = settings.sundaytoggle == 1 ? "dark" : "white";
          break;
        case 1:
          currentColor = settings.mondaycol;
          iconColor = settings.mondaytoggle == 1 ? "dark" : "white";
          break;
        case 2:
          currentColor = settings.tuesdaycol;
          iconColor = settings.tuesdaytoggle == 1 ? "dark" : "white";
          break;
        case 3:
          currentColor = settings.wednesdaycol;
          iconColor = settings.wednesdaytoggle == 1 ? "dark" : "white";
          break;
        case 4:
          currentColor = settings.thursdaycol;
          iconColor = settings.thursdaytoggle == 1 ? "dark" : "white";
          break;
        case 5:
          currentColor = settings.fridaycol;
          iconColor = settings.fridaytoggle == 1 ? "dark" : "white";
          break;
        case 6:
          currentColor = settings.saturdaycol;
          iconColor = settings.saturdaytoggle == 1 ? "dark" : "white";
          break;
      }
    } else {
      currentColor = "#ffffff";
      iconColor = "dark";
    }

    if (type == "icon") return iconColor;
    else return currentColor;
  }
</script>

<svelte:head>
  <title>{$_("LBL_APP_TITLE")}</title>
</svelte:head>
<main role="main" class="container-fluid">
  <div class="row">
    {#await promise then result}
      <div class="col-10" style="overflow-y: scroll;">
        <div class="text-center">
          <h3>{$_("home.LBL_MAIN_TITLE")}</h3>
        </div>
        <div class="card-columns masonry">
          {#if result}
            {#each result as data, i (data.id)}
              <Box pulse={data.sostatus} insurance_image={data.insurance_image}>
                <div
                  slot="body"
                  on:click|preventDefault={() => (showModalDetails = data)}
                >
                  <div class="card-text" style="line-height:1;">
                    <div
                      class="row mb-2"
                      style="background:rgb(255 255 255 / 85%);"
                    >
                      <div class="col pr-0 pl-0 mb-2 mt-1">
                        {#if getTotalWorkTime(data.timesheets) != ""}
                          <h6 style="color:red;" class="mb-0">
                            {getTotalWorkTime(data.timesheets)}{seconds}s
                          </h6>
                          <div
                            class="text-right mt-2 mr-3"
                            style="float:right;position:absolute;top:0;right:0;"
                          >
                            {#if settings.showcloseorder == 1}
                              <a
                                href="."
                                class="btn-sm btn-success"
                                on:click|preventDefault|stopPropagation={() =>
                                  (closeOrderModal = data.id)}
                                ><i class="fas fa-check" /></a
                              >
                            {/if}
                          </div>
                        {:else}
                          <div
                            class="text-right mt-2 mr-3"
                            style="float:right;position:absolute;top:0;right:0;"
                          />
                        {/if}
                        <p
                          style="font-size:20px;"
                          class="card-text text-dark text-nowrap"
                        >
                          {data.salesorder_no}
                        </p>
                        <p
                          style="font-size:20px;"
                          class="card-text text-dark text-nowrap"
                        >
                          {data.plate}<br />
                          {data.model}
                        </p>
                      </div>
                    </div>
                    <div
                      class="list-group mb-3"
                      style="height:120px;overflow-y:auto;"
                    >
                      {#each data.timesheets as timesheet, key}
                        {#if timesheet.timesheet_status == "In Progress"}
                          <div
                            class="list-group-item list-group-item-action pl-3 pt-2"
                            style="height:40px;background:rgb(255 255 255 / 85%);"
                          >
                            <small
                              ><i
                                class="fas fa-user"
                              />&nbsp;{timesheet.employeename}
                              <p
                                class="pt-1"
                                style="float:right;color:red;font-weight:500;"
                              >
                                {getWorkTime(
                                  timesheet.timesheet_date +
                                    " " +
                                    new Date(timesheet.start_time * 1000)
                                      .toISOString()
                                      .substr(11, 8)
                                )}
                              </p>
                            </small>
                          </div>
                        {/if}
                      {/each}
                    </div>
                  </div>
                </div>
              </Box>
            {:else}
              <div class="header">
                <div class="inner-header flex">
                  <span
                    style="align-self:center;margin-top:50px;position:absolute;"
                  >
                    <img alt="" src="images/logo-dark.png" width="400" />
                    <div>
                      <div class="car">
                        <div class="strike" />
                        <div class="strike strike2" />
                        <div class="strike strike3" />
                        <div class="strike strike4" />
                        <div class="strike strike5" />
                        <div class="car-detail spoiler" />
                        <div class="car-detail back" />
                        <div class="car-detail center" />
                        <div class="car-detail center1" />
                        <div class="car-detail front" />
                        <div class="car-detail wheel" />
                        <div class="car-detail wheel wheel2" />
                      </div>
                      <div
                        style="font-size:3em;font-weight:800;padding-left:100px;color:white;padding-top:20px;"
                      >
                        <h1>{$_("home.LBL_LOADING")}</h1>
                      </div>
                    </div>
                  </span>
                </div>

                <!--Waves Container-->
                <div>
                  <svg
                    class="waves"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 24 150 28"
                    preserveAspectRatio="none"
                    shape-rendering="auto"
                  >
                    <defs>
                      <path
                        id="gentle-wave"
                        d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"
                      />
                    </defs>
                    <g class="parallax">
                      <use
                        xlink:href="#gentle-wave"
                        x="48"
                        y="0"
                        fill="rgba(255,255,255,0.7"
                      />
                      <use
                        xlink:href="#gentle-wave"
                        x="48"
                        y="3"
                        fill="rgba(255,255,255,0.5)"
                      />
                      <use
                        xlink:href="#gentle-wave"
                        x="48"
                        y="5"
                        fill="rgba(255,255,255,0.3)"
                      />
                      <use xlink:href="#gentle-wave" x="48" y="7" fill="#fff" />
                    </g>
                  </svg>
                </div>
                <!--Waves end-->
              </div>
              <!--Header ends-->

              <!--Content starts-->
              <div class="content flex">
                <p class="footer-text">
                  <img
                    alt=""
                    src="images/logo_75x75.png"
                    width="45"
                  />&nbsp;softCodex | 2021
                </p>
              </div>
            {/each}
          {/if}
        </div>
      </div>
    {/await}
    {#await promise2}
      &nbsp;
    {:then result}
      <div class="col text-center border-left mr-0 pr-1 pl-1">
        <h3>{$_("home.LBL_OTHER_TITLE")}</h3>
        <div class="list-group mb-3 text-left">
          {#each result as data, i (data.id)}
            <div
              class="list-group-item list-group-item-action"
              on:click|preventDefault={() => {
                pin = "";
                showOtherTimesheetsModal = data.id;
              }}
            >
              <div class="row">
                <div class="col-4 text-left pl-1">
                  {data.servicename}
                </div>
                <div class="col-8 text-right align-middle pr-1">
                  <i class="fas fa-user" />&nbsp;{data.employeename}<br />
                  <h6 style="color:red;">
                    {getWorkTime(
                      data.timesheet_date +
                        " " +
                        new Date(data.start_time * 1000)
                          .toISOString()
                          .substr(11, 8)
                    )}
                  </h6>
                </div>
              </div>
            </div>
          {:else}
            <p>&nbsp;</p>
          {/each}
        </div>
      </div>
    {/await}
  </div>

  {#if closeOrderModal != false}
    <Modal on:close={() => (closeOrderModal = false)}>
      <Keypad bind:value={pin} on:submit={closeOrder} />
    </Modal>
  {/if}

  {#if createDeposit != false}
    <ModalDetails on:close={() => (createDeposit = false)}>
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_BTN_CREATE_DEPOSIT")}
      </h2>
      <div class="row">
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            Nome Deposito: <input type="text" />
            <input
              type="hidden"
              name="vcf_4_2"
              value={createDeposit.veicoliid}
            />
            <input
              type="hidden"
              name="vcf_4_4"
              value={createDeposit.accountid}
            />
          </div>
          <div class="d-flex justify-content-between mb-1">
            Quantità: <input type="text" name="vcf_4_13" />
          </div>
          <div class="d-flex justify-content-between mb-1">
            Prodotto Manuale: <input type="text" name="vcf_4_7" />
          </div>
          <div class="d-flex justify-content-between mb-1">
            Cerchi: <input type="text" name="vcf_4_22" />
          </div>
          <div class="d-flex justify-content-between mb-1">
            % di Consumo Posteriore: <input type="text" name="vcf_4_11" />
          </div>
        </div>
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            Stato: <input type="text" name="vcf_4_5" />
          </div>
          <div class="d-flex justify-content-between mb-1">
            Prodotto: <input type="text" name="vcf_4_6" />
          </div>
          <div class="d-flex justify-content-between mb-1">
            Stagione: <input type="text" name="vcf_4_8" />
          </div>
          <div class="d-flex justify-content-between mb-1">
            % di Consumo Anteriore: <input type="text" name="vcf_4_10" />
          </div>
          <div class="d-flex justify-content-between mb-1">
            Data del Deposito: <input type="text" name="vcf_4_14" />
          </div>
        </div>
      </div>
      <div slot="footer" style="text-align:right;">
        <a
          href="."
          class="btn btn-success"
          on:click|preventDefault|stopPropagation={() => {
            closeTimesheetAsAdmin(data.id);
          }}>{$_("home.LBL_BTN_CLOSE")}<i class="fas fa-check" /></a
        >
      </div>
    </ModalDetails>
  {/if}

  {#if showTimesheetsModal != false}
    <Modal on:close={() => (showTimesheetsModal = false)}>
      <Keypad
        adminCheck="true"
        bind:value={pin}
        bind:isAdmin
        on:submit={promptCardShowTimesheets(showTimesheetsModal, pin, isAdmin)}
      />
    </Modal>
  {/if}

  {#if showOtherTimesheetsModal != false}
    <Modal on:close={() => (showOtherTimesheetsModal = false)}>
      <Keypad
        adminCheck="true"
        bind:value={pin}
        bind:isAdmin
        on:submit={closeOtherTimesheet(showOtherTimesheetsModal, pin, isAdmin)}
      />
    </Modal>
  {/if}

  {#if showAdminTimeSheetModal != false}
    <Modal on:close={() => (showAdminTimeSheetModal = false)}>
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_TIMESHEETS_LIST")}
      </h2>
      <div
        class="list-group mb-3 text-left"
        style="max-height:30rem;overflow-y:auto;"
      >
        {#each timesheetData as data}
          {#if data.timesheet_status == "In Progress" && showModalTimesheetsUser == data.salesorder_lineid}
            <div class="list-group-item list-group-item-action">
              <div class="row">
                <div class="col-4 text-left pl-1">
                  {data.servicename}
                </div>
                <div class="col-4 text-right align-middle pr-1">
                  <i class="fas fa-user" />&nbsp;{data.employeename}<br />
                  {#if data.timesheet_status == "In Progress"}
                    <h6 style="color:red;">
                      {getWorkTime(
                        data.timesheet_date +
                          " " +
                          new Date(data.start_time * 1000)
                            .toISOString()
                            .substr(11, 8)
                      )}
                    </h6>
                  {:else}
                    <h6>
                      {getWorkTime(
                        data.timesheet_date +
                          " " +
                          new Date(data.start_time * 1000)
                            .toISOString()
                            .substr(11, 8),
                        data.timesheet_date +
                          " " +
                          new Date(data.end_time * 1000)
                            .toISOString()
                            .substr(11, 8)
                      )}
                    </h6>
                  {/if}
                </div>
                <div class="col-4 text-right align-middle pr-1">
                  <a
                    href="."
                    class="btn btn-success"
                    on:click|preventDefault|stopPropagation={() => {
                      closeTimesheetAsAdmin(data.id);
                    }}>{$_("home.LBL_BTN_CLOSE")}<i class="fas fa-check" /></a
                  >
                </div>
              </div>
            </div>
          {/if}
        {:else}
          <h5 style="text-align:center;">{$_("home.LBL_EMPTY_TIMESHEETS")}</h5>
        {/each}
      </div></Modal
    >
  {/if}

  {#if showTimeSheetModal != false}
    <Modal on:close={() => (showTimeSheetModal = false)}>
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_TIMESHEETS_LIST")}
      </h2>
      <div
        class="list-group mb-3 text-left"
        style="max-height:30rem;overflow-y:auto;"
      >
        {#each timesheetData as data}
          {#if data.employee_id == showModalTimesheetsUser || showModalTimesheetsUser == "admin"}
            <div class="list-group-item list-group-item-action">
              <div class="row">
                <div class="col-4 text-left pl-1">
                  {data.servicename}
                </div>
                <div class="col-8 text-right align-middle pr-1">
                  <i class="fas fa-user" />&nbsp;{data.employeename}<br />
                  {#if data.timesheet_status == "In Progress"}
                    <h6 style="color:red;">
                      {getWorkTime(
                        data.timesheet_date +
                          " " +
                          new Date(data.start_time * 1000)
                            .toISOString()
                            .substr(11, 8)
                      )}
                    </h6>
                  {:else}
                    <h6>
                      {getWorkTime(
                        data.timesheet_date +
                          " " +
                          new Date(data.start_time * 1000)
                            .toISOString()
                            .substr(11, 8),
                        data.timesheet_date +
                          " " +
                          new Date(data.end_time * 1000)
                            .toISOString()
                            .substr(11, 8)
                      )}
                    </h6>
                  {/if}
                </div>
              </div>
            </div>
          {/if}
        {:else}
          <h5 style="text-align:center;">{$_("home.LBL_EMPTY_TIMESHEETS")}</h5>
        {/each}
      </div>
      <div slot="footer" style="text-align:right;">
        <b>{$_("home.LBL_TOTAL_HOURS")}:</b>
        {getTimesheetsTotalHours(timesheetData, showModalTimesheetsUser)}
      </div></Modal
    >
  {/if}

  {#if showModalDetails != false}
    <ModalDetails on:close={() => (showModalDetails = false)}>
      <div slot="header">
        <div class="row">
          <div
            class="col mt-1"
            style="display:flex;justify-content:left;align-items:center;"
          >
            {#if showModalDetails.cf_8pb_1197 == "In Corso"}
              <a
                href="."
                class="btn btn-warning"
                style="border-top-right-radius: 0;border-bottom-right-radius:0;"
                on:click|preventDefault={() =>
                  console.log(showModalDetails.id)}
                >{$_("home.LBL_BTN_STOP_WORK")}</a
              >
              <a
                href="."
                style="border-top-left-radius: 0;border-bottom-left-radius: 0;"
                class="btn btn-success"
                on:click|preventDefault={() =>
                  console.log(showModalDetails.id)}
                ><i class="fas fa-plus" /></a
              >
            {:else}
              <a
                href="."
                class="btn btn-success"
                on:click|preventDefault={() =>
                  console.log(showModalDetails.id)}
                >{$_("home.LBL_BTN_START_WORK")}</a
              >
            {/if}
          </div>
          <div
            class="col text-center"
            style="display:flex;justify-content:center;align-items:center;"
          >
            <h2 style="color:red">
              {#if getTotalWorkTime(showModalDetails.timesheets) != ""}
                {getTotalWorkTime(showModalDetails.timesheets)}
              {:else}
                0h 0min
              {/if}
            </h2>
          </div>
          <div
            class="col text-right"
            style="display:flex;justify-content:right;align-items:center;"
          >
            {#if settings.showcloseorder == 1}
              <a
                href="."
                class="btn btn-danger"
                on:click|preventDefault|stopPropagation={closeOrder(
                  showModalDetails.id
                )}>{$_("home.LBL_BTN_CLOSE")} <i class="fas fa-times" /></a
              >
            {/if}
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <b>{$_("home.subject")}:</b>
          {showModalDetails.subject} <br />
          <b>{$_("home.order_no")}:</b>
          {showModalDetails.salesorder_no}<br />
          <b>{$_("home.account")}:</b>
          {showModalDetails.account_name}<br />
          {#if showModalDetails.contact_name != ""}
            <b>{$_("home.contact")}:</b>
            {showModalDetails.contact_name}
          {/if}
        </div>
        <div class="col-4">
          <b>{$_("home.plate")}:</b>
          {showModalDetails.plate} <br />
          <b>{$_("home.vehicle")}:</b>
          {showModalDetails.model}<br />
          <b>{$_("home.chassis_no")}:</b>
          {showModalDetails.chassis}<br />
          <b>{$_("home.kilometers")}:</b>
          {showModalDetails.cf_8pb_1203}<br />
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <b>{$_("home.description")}:</b>
          {showModalDetails.description}<br />
          <br />
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <div style="max-height: 600px;overflow-y: auto;">
            {#each showModalDetails.product_block.products as product, key}
              {#if product.raw.no_garage != 1}
                <div class="list-group-item list-group-item-action">
                  <div class="d-flex w-100">
                    <div class="mr-2">
                      <i class="fal fa-clipboard-list-check" />
                    </div>
                    <div class="mr-2">
                      <h5>{product.qty}x</h5>
                    </div>
                    <div>
                      <h5 class="mb-1">
                        {product.productName}
                      </h5>
                      <p class="mb-1">{@html product.productDescription}</p>
                      {#if product.comment != ""}
                        <small
                          ><i
                            class="fal fa-comment-lines"
                          />&nbsp;{product.comment}</small
                        >
                      {/if}
                    </div>
                    <div
                      class="text-right"
                      style="width:250px;max-height:72px;overflow-y: auto;"
                    >
                      {#each showModalDetails.timesheets as timesheet, key}
                        {#if product.raw.work_in_progress == 1 && product.raw.timesheet_id == timesheet.salesorder_lineid && timesheet.timesheet_status == "In Progress"}
                          <small>
                            <span style="color:red;font-weight:500;">
                              {getWorkTime(
                                timesheet.timesheet_date +
                                  " " +
                                  new Date(timesheet.start_time * 1000)
                                    .toISOString()
                                    .substr(11, 8)
                              )}
                            </span>
                            &nbsp;<i
                              class="fas fa-user"
                            />&nbsp;{timesheet.employeename}
                          </small>
                          <br />
                        {/if}
                      {/each}
                    </div>
                  </div>
                </div>
              {/if}
            {/each}
          </div>
        </div>
      </div>
      <div slot="footer" class="d-flex justify-content-between">
        <div>
          {#if showModalDetails.cf_8pb_1314 == 1}
            <a
              href="."
              class="btn btn-success"
              on:click|preventDefault|stopPropagation={() => {
                openDepositModal(
                  showModalDetails.id,
                  showModalDetails.veicoliid,
                  showModalDetails.account_id
                );
              }}>Visualizza Deposito&nbsp;</a
            >
          {:else}
            <a
              href="."
              class="btn btn-success"
              on:click|preventDefault|stopPropagation={() => {
                console.log(showModalDetails.veicoliid);
                openDepositModal(
                  showModalDetails.id,
                  showModalDetails.veicoliid,
                  showModalDetails.account_id
                );
              }}
              >{$_("home.LBL_BTN_CREATE_DEPOSIT")}&nbsp;<i
                class="fas fa-plus"
              /></a
            >
          {/if}
        </div>
        <div>
          {#if showModalDetails.timesheets.length > 0}
            <button
              type="button"
              class="btn btn-info"
              on:click|preventDefault={() => {
                showTimesheetsModal = showModalDetails.timesheets;
                pin = "";
                showModalDetails = false;
              }}>{$_("home.LBL_SHOW_TIMESHEETS")}</button
            >
          {/if}
        </div>
      </div>
    </ModalDetails>
  {/if}
</main>

<style>
  .masonry {
    display: flex;
    flex-flow: row wrap;
    margin-left: -8px; /* Adjustment for the gutter */
    width: 100%;
  }
</style>
