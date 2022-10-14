<script>
  import Box from "../../components/Box.svelte";
  import ModalDetails from "../../components/ModalDetails.svelte";
  import ModalProducts from "../../components/ModalProducts.svelte";
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
  let showUserListModal = false;
  let userListData = [];
  let editVehicleModal = false;
  let createDeposit = false;
  let newDepositData = [];
  let oldDepositData = [];
  let showTimesheetsModal = false;
  let timesheetData = [];
  let showTimeSheetModal = false;
  let showAdminTimeSheetModal = false;
  let showOtherTimesheetsModal = false;
  let showDepositModal = false;
  let productsList = false;
  let showModalTimesheetsUser = "";
  let showModalDetails = false;
  let showProductsList = false;
  let pin;
  let isAdmin;
  let settings;
  let currentColor;
  let orders;
  let jobs;

  function closeOrder(crmid) {
    closeSalesOrder(crmid).then((res) => {
      var bootboxalert = bootbox.alert({
        message: $_("backend." + res.error),
        size: "small",
      });
      setTimeout(function () {
        bootboxalert.modal("hide");
      }, backend.popupAutocloseTime);
      showModalDetails = false;
      resetIntervals();
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

  async function startWork(data, mode, salesorder_id) {
    showUserListModal = data;
    showModalDetails = false;
    userListData = [];

    let element = {
      mode: mode,
      salesorder_id: salesorder_id,
    };

    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=getEmployeeList",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(element),
      }
    );
    let res = await response.json();

    userListData = res;
  }

  async function showWork(mode, salesorder_id) {
    let element = {
      mode: mode,
      salesorder_id: salesorder_id,
    };

    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=getEmployeeList",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(element),
      }
    );
    let res = await response.json();

    userListData = res;
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

  async function closeOtherTimesheet(crmid) {
    let data = {
      crmid: crmid,
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

  async function deferredLoadProducts() {
    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=getProducts",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );

    productsList = await response.json();
  }

  function openDepositModal(data) {
    showModalDetails = false;
    pin = "";
    createDeposit = data;
    oldDepositData = JSON.parse(JSON.stringify(data));
  }

  async function showDeposit(data) {
    showDepositModal = data;
    oldDepositData = JSON.parse(JSON.stringify(data));
    showModalDetails = false;
  }

  async function fetchProducts() {
    window.jQuery(function () {
      if (window.jQuery.fn.dataTable.isDataTable("#products_table")) {
        //window.jQuery("#products_table").DataTable().clear();
        //window.jQuery("#products_table").DataTable().draw();
        //window.jQuery("#products_table").DataTable().ajax.reload();
      } else {
        var table = window.jQuery("#products_table").DataTable({
          paging: true,
          ordering: true,
          bLengthChange: false,
          data: productsList,
          language: {
            url: "https://cdn.datatables.net/plug-ins/1.12.1/i18n/it-IT.json",
          },
          initComplete: function () {
            // Apply the search
            this.api()
              .columns()
              .every(function () {
                var that = this;

                window
                  .jQuery("input", this.footer())
                  .on("keyup change clear", function () {
                    if (that.search() !== this.value) {
                      that.search(this.value).draw();
                    }
                  });
              });
            window.jQuery("#products_table").show();
          },
          /*ajax: {
          url:
            baseURL +
            "modules/SDK/src/modules/Webservices/backend.php?action=getProducts",
          type: "GET",
        },*/
          /*columns: [
            { title: "Nome Prodotto" },
            { title: "Codice Prodotto / SKU" },
            { title: "Match Code" },
            { title: "Stagione" },
            { title: "id" },
          ],*/
          columnDefs: [
            {
              target: 4,
              visible: false,
              searchable: false,
            },
          ],
        });

        window.jQuery("#products_table tbody").on("click", "tr", function () {
          var data = table.row(this).data();
          showProductsList.cf_8pb_1309 = data[4];
          showProductsList.cf_8pb_1309_display = data[0];
          showDepositModal = showProductsList;

          showProductsList = false;
        });

        window.jQuery("#products_table tfoot th").each(function () {
          var title = window.jQuery(this).text();
          window
            .jQuery(this)
            .html('<input type="text" placeholder="' + title + '" />');
        });
      }
    });
  }

  async function updateVehicleInformation(crmid) {
    let data = {
      id: crmid,
      plate: document.getElementById("plate").value,
      chassis: document.getElementById("chassis").value,
      cf_8pb_1203: document.getElementById("cf_8pb_1203").value,
    };

    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=updateVehicleInformation",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    let res = await response.json();

    editVehicleModal = { ...editVehicleModal, ...res.data };

    var bootboxalert = bootbox.alert({
      message: $_(res.message),
      size: "small",
    });
    setTimeout(function () {
      bootboxalert.modal("hide");
    }, backend.popupAutocloseTime);
    showModalDetails = editVehicleModal;
    editVehicleModal = false;
  }

  function copyDepositData(newDeposit) {
    newDeposit.cf_8pb_1305 = document.getElementById("cf_8pb_1305").value;
    newDeposit.cf_8pb_1306 = document.getElementById("cf_8pb_1306").value;
    newDeposit.cf_8pb_1307 = document.getElementById("cf_8pb_1307").value;
    newDeposit.cf_8pb_1308 = document.getElementById("cf_8pb_1308").value;
    newDeposit.cf_8pb_1309 = document.getElementById("cf_8pb_1309").value;
    newDeposit.cf_8pb_1310 = document.getElementById("cf_8pb_1310").value;
    newDeposit.cf_xpr_1684 = document.getElementById("cf_xpr_1684").value;
    newDeposit.cf_8pb_1313 = document.getElementById("cf_8pb_1313").value;
    newDeposit.cf_wym_1645 = document.getElementById("cf_wym_1645").value;
  }

  async function saveDeposit(crmid) {
    let data = {
      id: crmid,
      cf_8pb_1314: 1,
      cf_8pb_1305: document.getElementById("cf_8pb_1305").value,
      cf_8pb_1306: document.getElementById("cf_8pb_1306").value,
      cf_8pb_1307: document.getElementById("cf_8pb_1307").value,
      cf_8pb_1308: document.getElementById("cf_8pb_1308").value,
      cf_8pb_1309: document.getElementById("cf_8pb_1309").value,
      cf_8pb_1310: document.getElementById("cf_8pb_1310").value,
      cf_xpr_1684: document.getElementById("cf_xpr_1684").value,
      cf_8pb_1313: document.getElementById("cf_8pb_1313").value,
      cf_wym_1645: document.getElementById("cf_wym_1645").value,
    };

    const response = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/backend.php?action=saveDeposit",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      }
    );
    let res = await response.json();

    delete res.message.assigned_user_id;
    showDepositModal = { ...showDepositModal, ...res.data };

    var bootboxalert = bootbox.alert({
      message: $_(res.message),
      size: "small",
    });
    setTimeout(function () {
      bootboxalert.modal("hide");
    }, backend.popupAutocloseTime);
    showModalDetails = showDepositModal;
    showDepositModal = false;
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

  async function updateInventoryRow(crmid, salesorder_no) {
    let employees = [...document.getElementsByName("employees")];
    employees.forEach(async (child) => {
      if (child.checked) {
        let data = {
          crmid: crmid,
          salesorder_no: salesorder_no,
          userid: child.value,
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
        showUserListModal = false;
        let res = await response.json();
        pin = "";
        var bootboxalert = bootbox.alert({
          message: $_("backend." + res),
          size: "small",
        });
        setTimeout(function () {
          bootboxalert.modal("hide");
        }, backend.popupAutocloseTime);
      }
    });
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
                  on:click|preventDefault={() => {
                    deferredLoadProducts();
                    showModalDetails = data;
                  }}
                >
                  <div class="card-text" style="line-height:1;">
                    <div
                      class="mb-2"
                      style="background:rgb(255 255 255 / 85%);"
                    >
                      <div class="col pr-0 pl-0 mb-2 mt-1">
                        {#if getTotalWorkTime(data.timesheets) != ""}
                          <h6 style="color:red;" class="mb-0">
                            {getTotalWorkTime(data.timesheets)}{seconds}s
                          </h6>
                          <div
                            class="text-right"
                            style="float:right;position:absolute;top:0;right:0;"
                          >
                            {#if settings.showcloseorder == 1}
                              <a
                                href="."
                                class="btn-sm btn-success"
                                on:click|preventDefault|stopPropagation={() =>
                                  closeOrder(data.id)}
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
                          class="card-text text-dark text-nowrap mb-0"
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
                      style="max-height:7.5rem;overflow-y:auto;"
                    >
                      {#each data.timesheets as timesheet, key}
                        {#if timesheet.timesheet_status == "In Progress"}
                          <div
                            class="list-group-item list-group-item-action pl-2 pt-1 pr-2 pb-0"
                            style="min-height:40px;background:rgb(255 255 255 / 85%);"
                          >
                            <div style="float:left;">
                              <small><i class="fas fa-user" /> </small>
                            </div>
                            <div
                              style="width:65%;float:left;margin-left:0.25rem;"
                            >
                              <small>{timesheet.employeename} </small>
                            </div>
                            <div style="text-align: right;">
                              <small>
                                <p
                                  class="pt-1"
                                  style="color:red;font-weight:500;"
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
                closeOtherTimesheet(data.id);
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

  {#if showUserListModal != false}
    <ModalDetails on:close={() => (showUserListModal = false)}>
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_SELECT_EMPLOYEES")}
      </h2>
      {#each userListData as users, key}
        <p
          style="margin-bottom:0px;font-size:24px;{users.status
            ? 'color:#ccc'
            : ''}"
        >
          <input
            type="checkbox"
            id="employees{key}"
            name="employees"
            disabled={users.status}
            value={users.id}
          /><label for="employees{key}"
            >&nbsp;{users.firstname}&nbsp;{users.lastname}</label
          >
        </p>
      {:else}
        <div style="text-align:center">
          <i class="fas fa-tire fa-spin fa-5x" />
        </div>
      {/each}
      <div slot="footer" class="d-flex justify-content-between mb-1">
        <a
          href="."
          class="btn btn-danger"
          on:click|preventDefault|stopPropagation={() => {
            showModalDetails = showUserListModal;
            showUserListModal = false;
          }}
          >{$_("deposit.LBL_BTN_CANCEL")}&nbsp;<i
            class="fas fa-arrow-left"
          /></a
        >
        <a
          href="."
          class="btn btn-success"
          on:click|preventDefault|stopPropagation={() => {
            updateInventoryRow(
              showUserListModal.id,
              showUserListModal.salesorder_no
            );
          }}>{$_("deposit.LBL_BTN_SAVE")}&nbsp;<i class="fas fa-check" /></a
        >
      </div>
    </ModalDetails>
  {/if}

  {#if closeOrderModal != false}
    <Modal on:close={() => (closeOrderModal = false)}>
      <Keypad bind:value={pin} on:submit={closeOrder} />
    </Modal>
  {/if}
  {#if showDepositModal != false}
    <ModalDetails on:close={() => (showDepositModal = false)}>
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_BTN_SHOW_DEPOSIT")}
      </h2>
      <div class="row">
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_QUANTITY")}
            <input
              type="text"
              id="cf_8pb_1305"
              style="width:50%;height:30px;"
              value={showDepositModal.cf_8pb_1305}
            />
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_FRONT_CONSUMPTION")}
            <select
              id="cf_8pb_1307"
              required
              style="width:50%;height:30px;"
              value={showDepositModal.cf_8pb_1307}
            >
              <option value="" selected="">--Prego Selezionare--</option>
              <option value="0">0</option>
              <option value="5 Necessaria Sostituzione Immediata!"
                >5 Necessaria Sostituzione Immediata!</option
              >
              <option value="10 Necessaria Sostituzione Immediata!"
                >10 Necessaria Sostituzione Immediata!</option
              >
              <option value="15 Necessaria Sostituzione Immediata!"
                >15 Necessaria Sostituzione Immediata!</option
              >
              <option value="20 Necessaria Sostituzione Immediata!"
                >20 Necessaria Sostituzione Immediata!</option
              >
              <option value="25 Necessaria Sostituzione Immediata!"
                >25 Necessaria Sostituzione Immediata!</option
              >
              <option value="30 Necessaria Sostituzione Immediata!"
                >30 Necessaria Sostituzione Immediata!</option
              >
              <option value="35 Necessaria Sostituzione a Breve!"
                >35 Necessaria Sostituzione a Breve!</option
              >
              <option value="40 Necessaria Sostituzione a Breve!"
                >40 Necessaria Sostituzione a Breve!</option
              >
              <option value="45 Necessaria Sostituzione a Breve!"
                >45 Necessaria Sostituzione a Breve!</option
              >
              <option value="50 Usura Media">50 Usura Media</option>
              <option value="55 Usura Media">55 Usura Media</option>
              <option value="60 Usura Media">60 Usura Media</option>
              <option value="65 Condizioni Discrete"
                >65 Condizioni Discrete</option
              >
              <option value="70 Condizioni Discrete"
                >70 Condizioni Discrete</option
              >
              <option value="75 Condizioni Buone">75 Condizioni Buone</option>
              <option value="80 Condizioni Buone">80 Condizioni Buone</option>
              <option value="85 Condizioni Buone">85 Condizioni Buone</option>
              <option value="90 Condizioni Ottime">90 Condizioni Ottime</option>
              <option value="95 Condizioni Ottime">95 Condizioni Ottime</option>
              <option value="99 Condizioni Ottime">99 Condizioni Ottime</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_PRODUCT")}
            <div style="width:50%;">
              <input
                type="hidden"
                id="cf_8pb_1309"
                value={showDepositModal.cf_8pb_1309}
              />
              <input
                type="text"
                id="cf_8pb_1309_display"
                style="width:85%;height:30px;"
                on:click={() => {
                  copyDepositData(showDepositModal);
                  fetchProducts();
                  showProductsList = showDepositModal;
                  showDepositModal = false;
                }}
                value={showDepositModal.cf_8pb_1309_display}
              />&nbsp;<i
                class="fas fa-list fa-lg"
                on:click={() => {
                  copyDepositData(showDepositModal);
                  fetchProducts();
                  showProductsList = showDepositModal;
                  showDepositModal = false;
                }}
              />
            </div>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_SECTOR")}
            <select
              id="cf_xpr_1684"
              name="cf_xpr_1684"
              tabindex=""
              style="width:50%;height:30px;"
              value={showDepositModal.cf_xpr_1684}
            >
              <option value="" selected="" />
              <option value="1 A">1 A</option>
              <option value="2 A">2 A</option>
              <option value="3 A">3 A</option>
              <option value="4 A">4 A</option>
              <option value="5 A">5 A</option>
              <option value="6 A">6 A</option>
              <option value="7 A">7 A</option>
              <option value="8 A">8 A</option>
              <option value="9 A">9 A</option>
              <option value="10 A">10 A</option>
              <option value="11 A">11 A</option>
              <option value="12 A">12 A</option>
              <option value="13 A">13 A</option>
              <option value="14 A">14 A</option>
              <option value="15 A">15 A</option>
              <option value="16 A">16 A</option>
              <option value="17 A">17 A</option>
              <option value="18 A">18 A</option>
              <option value="19 A">19 A</option>
              <option value="1 B">1 B</option>
              <option value="2 B">2 B</option>
              <option value="3 B">3 B</option>
              <option value="4 B">4 B</option>
              <option value="5 B">5 B</option>
              <option value="6 B">6 B</option>
              <option value="7 B">7 B</option>
              <option value="8 B">8 B</option>
              <option value="9 B">9 B</option>
              <option value="10 B">10 B</option>
              <option value="11 B">11 B</option>
              <option value="12 B">12 B</option>
              <option value="13 B">13 B</option>
              <option value="14 B">14 B</option>
              <option value="15 B">15 B</option>
              <option value="16 B">16 B</option>
              <option value="17 B">17 B</option>
              <option value="18 B">18 B</option>
              <option value="19 B">19 B</option>
              <option value="M 2">M 2</option>
              <option value="M 3">M 3</option>
              <option value="M 3 A">M 3 A</option>
              <option value="M 5">M 5</option>
              <option value="M 6">M 6</option>
              <option value="M 7">M 7</option>
              <option value="M 8">M 8</option>
              <option value="M 9">M 9</option>
              <option value="M 10">M 10</option>
              <option value="M 11">M 11</option>
              <option value="M 13">M 13</option>
              <option value="M 16">M 16</option>
              <option value="M 17">M 17</option>
              <option value="M 18">M 18</option>
              <option value="M 19">M 19</option>
              <option value="M 20">M 20</option>
              <option value="M 21">M 21</option>
              <option value="M 22">M 22</option>
              <option value="M 23">M 23</option>
              <option value="M 24">M 24</option>
              <option value="M 24 25">M 24 25</option>
              <option value="M 25">M 25</option>
              <option value="M 26">M 26</option>
              <option value="M 27">M 27</option>
              <option value="TUNNEL">TUNNEL</option>
            </select>
          </div>
        </div>
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_CAR_RIMS")}
            <select
              id="cf_8pb_1306"
              required
              style="width:50%;height:30px;"
              value={showDepositModal.cf_8pb_1306}
            >
              <option value="">--Prego Selezionare--</option>
              <option value="Si">Si</option>
              <option value="No">No</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_REAR_CONSUMPTION")}
            <select
              id="cf_8pb_1308"
              required
              style="width:50%;height:30px;"
              value={showDepositModal.cf_8pb_1308}
            >
              <option value="" selected="">--Prego Selezionare--</option>
              <option value="0">0</option>
              <option value="5 Necessaria Sostituzione Immediata!"
                >5 Necessaria Sostituzione Immediata!</option
              >
              <option value="10 Necessaria Sostituzione Immediata!"
                >10 Necessaria Sostituzione Immediata!</option
              >
              <option value="15 Necessaria Sostituzione Immediata!"
                >15 Necessaria Sostituzione Immediata!</option
              >
              <option value="20 Necessaria Sostituzione Immediata!"
                >20 Necessaria Sostituzione Immediata!</option
              >
              <option value="25 Necessaria Sostituzione Immediata!"
                >25 Necessaria Sostituzione Immediata!</option
              >
              <option value="30 Necessaria Sostituzione Immediata!"
                >30 Necessaria Sostituzione Immediata!</option
              >
              <option value="35 Necessaria Sostituzione a Breve!"
                >35 Necessaria Sostituzione a Breve!</option
              >
              <option value="40 Necessaria Sostituzione a Breve!"
                >40 Necessaria Sostituzione a Breve!</option
              >
              <option value="45 Necessaria Sostituzione a Breve!"
                >45 Necessaria Sostituzione a Breve!</option
              >
              <option value="50 Usura Media">50 Usura Media</option>
              <option value="55 Usura Media">55 Usura Media</option>
              <option value="60 Usura Media">60 Usura Media</option>
              <option value="65 Condizioni Discrete"
                >65 Condizioni Discrete</option
              >
              <option value="70 Condizioni Discrete"
                >70 Condizioni Discrete</option
              >
              <option value="75 Condizioni Buone">75 Condizioni Buone</option>
              <option value="80 Condizioni Buone">80 Condizioni Buone</option>
              <option value="85 Condizioni Buone">85 Condizioni Buone</option>
              <option value="90 Condizioni Ottime">90 Condizioni Ottime</option>
              <option value="95 Condizioni Ottime">95 Condizioni Ottime</option>
              <option value="99 Condizioni Ottime">99 Condizioni Ottime</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_MANUAL_PRODUCT")}
            <input
              type="text"
              id="cf_8pb_1310"
              style="width:50%;height:30px;"
              value={showDepositModal.cf_8pb_1310}
            />
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_SEASON")}
            <select
              id="cf_8pb_1313"
              required
              style="width:50%;height:30px;"
              value={showDepositModal.cf_8pb_1313}
            >
              <option value="">--Prego Selezionare--</option>
              <option value="IN">IN</option>
              <option value="ES">ES</option>
              <option value="4S">4S</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_DIFFERENT_AXIS_MEASURE")}
            <input
              type="text"
              id="cf_wym_1645"
              style="width:50%;height:30px;"
              value={showDepositModal.cf_wym_1645}
            />
          </div>
        </div>
      </div>
      <div slot="footer" class="d-flex justify-content-between mb-1">
        <a
          href="."
          class="btn btn-danger"
          on:click|preventDefault|stopPropagation={() => {
            showModalDetails = oldDepositData;
            showDepositModal = false;
          }}
          >{$_("deposit.LBL_BTN_CANCEL")}&nbsp;<i
            class="fas fa-arrow-left"
          /></a
        >
        <a
          href="."
          class="btn btn-success"
          on:click|preventDefault|stopPropagation={() => {
            saveDeposit(showDepositModal.id);
          }}>{$_("deposit.LBL_BTN_SAVE")}&nbsp;<i class="fas fa-check" /></a
        >
      </div>
    </ModalDetails>
  {/if}

  {#if editVehicleModal != false}
    <ModalDetails
      on:close={() => {
        showModalDetails = editVehicleModal;
        editVehicleModal = false;
      }}
    >
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_MODIFY_VEHICLE")}
      </h2>
      <div class="row">
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            {$_("home.plate")}
            <input
              type="text"
              id="plate"
              style="width:50%;"
              value={editVehicleModal.plate == undefined
                ? ""
                : editVehicleModal.plate}
            />
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("home.kilometers")}
            <input
              type="text"
              id="cf_8pb_1203"
              style="width:50%;"
              value={editVehicleModal.cf_8pb_1203 == undefined
                ? ""
                : editVehicleModal.cf_8pb_1203}
            />
          </div>
        </div>
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            {$_("home.chassis_no")}
            <input
              type="text"
              id="chassis"
              style="width:50%;"
              value={editVehicleModal.chassis == undefined
                ? ""
                : editVehicleModal.chassis}
            />
          </div>
        </div>
      </div>
      <div slot="footer" class="d-flex justify-content-between mb-1">
        <a
          href="."
          class="btn btn-danger"
          on:click|preventDefault|stopPropagation={() => {
            showModalDetails = editVehicleModal;
            editVehicleModal = false;
          }}
          >{$_("deposit.LBL_BTN_CANCEL")}&nbsp;<i
            class="fas fa-arrow-left"
          /></a
        >
        <a
          href="."
          class="btn btn-success"
          on:click|preventDefault|stopPropagation={() => {
            updateVehicleInformation(editVehicleModal.id);
          }}>{$_("deposit.LBL_BTN_SAVE")}&nbsp;<i class="fas fa-check" /></a
        >
      </div>
    </ModalDetails>
  {/if}

  {#if createDeposit != false}
    <ModalDetails on:close={() => (createDeposit = false)}>
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_BTN_CREATE_DEPOSIT")}
      </h2>
      <div class="row">
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_QUANTITY")}
            <input
              type="text"
              id="cf_8pb_1305"
              style="width:50%;height:30px;"
            />
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_FRONT_CONSUMPTION")}
            <select id="cf_8pb_1307" required style="width:50%;height:30px;">
              <option value="" selected="">--Prego Selezionare--</option>
              <option value="0">0</option>
              <option value="5 Necessaria Sostituzione Immediata!"
                >5 Necessaria Sostituzione Immediata!</option
              >
              <option value="10 Necessaria Sostituzione Immediata!"
                >10 Necessaria Sostituzione Immediata!</option
              >
              <option value="15 Necessaria Sostituzione Immediata!"
                >15 Necessaria Sostituzione Immediata!</option
              >
              <option value="20 Necessaria Sostituzione Immediata!"
                >20 Necessaria Sostituzione Immediata!</option
              >
              <option value="25 Necessaria Sostituzione Immediata!"
                >25 Necessaria Sostituzione Immediata!</option
              >
              <option value="30 Necessaria Sostituzione Immediata!"
                >30 Necessaria Sostituzione Immediata!</option
              >
              <option value="35 Necessaria Sostituzione a Breve!"
                >35 Necessaria Sostituzione a Breve!</option
              >
              <option value="40 Necessaria Sostituzione a Breve!"
                >40 Necessaria Sostituzione a Breve!</option
              >
              <option value="45 Necessaria Sostituzione a Breve!"
                >45 Necessaria Sostituzione a Breve!</option
              >
              <option value="50 Usura Media">50 Usura Media</option>
              <option value="55 Usura Media">55 Usura Media</option>
              <option value="60 Usura Media">60 Usura Media</option>
              <option value="65 Condizioni Discrete"
                >65 Condizioni Discrete</option
              >
              <option value="70 Condizioni Discrete"
                >70 Condizioni Discrete</option
              >
              <option value="75 Condizioni Buone">75 Condizioni Buone</option>
              <option value="80 Condizioni Buone">80 Condizioni Buone</option>
              <option value="85 Condizioni Buone">85 Condizioni Buone</option>
              <option value="90 Condizioni Ottime">90 Condizioni Ottime</option>
              <option value="95 Condizioni Ottime">95 Condizioni Ottime</option>
              <option value="99 Condizioni Ottime">99 Condizioni Ottime</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_PRODUCT")}
            <div style="width:50%;">
              <input type="hidden" id="cf_8pb_1309" />
              <input
                type="text"
                id="cf_8pb_1309_display"
                style="width:85%;height:30px;"
                on:click={() => {
                  fetchProducts();
                  showProductsList = createDeposit;
                  createDeposit = false;
                }}
              />&nbsp;<i
                class="fas fa-list fa-lg"
                on:click={() => {
                  fetchProducts();
                  showProductsList = createDeposit;
                  createDeposit = false;
                }}
              />
            </div>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_SECTOR")}
            <select
              id="cf_xpr_1684"
              name="cf_xpr_1684"
              tabindex=""
              style="width:50%;height:30px;"
            >
              <option value="" selected="" />
              <option value="1 A">1 A</option>
              <option value="2 A">2 A</option>
              <option value="3 A">3 A</option>
              <option value="4 A">4 A</option>
              <option value="5 A">5 A</option>
              <option value="6 A">6 A</option>
              <option value="7 A">7 A</option>
              <option value="8 A">8 A</option>
              <option value="9 A">9 A</option>
              <option value="10 A">10 A</option>
              <option value="11 A">11 A</option>
              <option value="12 A">12 A</option>
              <option value="13 A">13 A</option>
              <option value="14 A">14 A</option>
              <option value="15 A">15 A</option>
              <option value="16 A">16 A</option>
              <option value="17 A">17 A</option>
              <option value="18 A">18 A</option>
              <option value="19 A">19 A</option>
              <option value="1 B">1 B</option>
              <option value="2 B">2 B</option>
              <option value="3 B">3 B</option>
              <option value="4 B">4 B</option>
              <option value="5 B">5 B</option>
              <option value="6 B">6 B</option>
              <option value="7 B">7 B</option>
              <option value="8 B">8 B</option>
              <option value="9 B">9 B</option>
              <option value="10 B">10 B</option>
              <option value="11 B">11 B</option>
              <option value="12 B">12 B</option>
              <option value="13 B">13 B</option>
              <option value="14 B">14 B</option>
              <option value="15 B">15 B</option>
              <option value="16 B">16 B</option>
              <option value="17 B">17 B</option>
              <option value="18 B">18 B</option>
              <option value="19 B">19 B</option>
              <option value="M 2">M 2</option>
              <option value="M 3">M 3</option>
              <option value="M 3 A">M 3 A</option>
              <option value="M 5">M 5</option>
              <option value="M 6">M 6</option>
              <option value="M 7">M 7</option>
              <option value="M 8">M 8</option>
              <option value="M 9">M 9</option>
              <option value="M 10">M 10</option>
              <option value="M 11">M 11</option>
              <option value="M 13">M 13</option>
              <option value="M 16">M 16</option>
              <option value="M 17">M 17</option>
              <option value="M 18">M 18</option>
              <option value="M 19">M 19</option>
              <option value="M 20">M 20</option>
              <option value="M 21">M 21</option>
              <option value="M 22">M 22</option>
              <option value="M 23">M 23</option>
              <option value="M 24">M 24</option>
              <option value="M 24 25">M 24 25</option>
              <option value="M 25">M 25</option>
              <option value="M 26">M 26</option>
              <option value="M 27">M 27</option>
              <option value="TUNNEL">TUNNEL</option>
            </select>
          </div>
        </div>
        <div class="col">
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_CAR_RIMS")}
            <select id="cf_8pb_1306" required style="width:50%;height:30px;">
              <option value="">--Prego Selezionare--</option>
              <option value="Si">Si</option>
              <option value="No">No</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_REAR_CONSUMPTION")}
            <select id="cf_8pb_1308" required style="width:50%;height:30px;">
              <option value="" selected="">--Prego Selezionare--</option>
              <option value="0">0</option>
              <option value="5 Necessaria Sostituzione Immediata!"
                >5 Necessaria Sostituzione Immediata!</option
              >
              <option value="10 Necessaria Sostituzione Immediata!"
                >10 Necessaria Sostituzione Immediata!</option
              >
              <option value="15 Necessaria Sostituzione Immediata!"
                >15 Necessaria Sostituzione Immediata!</option
              >
              <option value="20 Necessaria Sostituzione Immediata!"
                >20 Necessaria Sostituzione Immediata!</option
              >
              <option value="25 Necessaria Sostituzione Immediata!"
                >25 Necessaria Sostituzione Immediata!</option
              >
              <option value="30 Necessaria Sostituzione Immediata!"
                >30 Necessaria Sostituzione Immediata!</option
              >
              <option value="35 Necessaria Sostituzione a Breve!"
                >35 Necessaria Sostituzione a Breve!</option
              >
              <option value="40 Necessaria Sostituzione a Breve!"
                >40 Necessaria Sostituzione a Breve!</option
              >
              <option value="45 Necessaria Sostituzione a Breve!"
                >45 Necessaria Sostituzione a Breve!</option
              >
              <option value="50 Usura Media">50 Usura Media</option>
              <option value="55 Usura Media">55 Usura Media</option>
              <option value="60 Usura Media">60 Usura Media</option>
              <option value="65 Condizioni Discrete"
                >65 Condizioni Discrete</option
              >
              <option value="70 Condizioni Discrete"
                >70 Condizioni Discrete</option
              >
              <option value="75 Condizioni Buone">75 Condizioni Buone</option>
              <option value="80 Condizioni Buone">80 Condizioni Buone</option>
              <option value="85 Condizioni Buone">85 Condizioni Buone</option>
              <option value="90 Condizioni Ottime">90 Condizioni Ottime</option>
              <option value="95 Condizioni Ottime">95 Condizioni Ottime</option>
              <option value="99 Condizioni Ottime">99 Condizioni Ottime</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_MANUAL_PRODUCT")}
            <input
              type="text"
              id="cf_8pb_1310"
              style="width:50%;height:30px;"
            />
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_SEASON")}
            <select id="cf_8pb_1313" required style="width:50%;height:30px;">
              <option value="">--Prego Selezionare--</option>
              <option value="IN">IN</option>
              <option value="ES">ES</option>
              <option value="4S">4S</option>
            </select>
          </div>
          <div class="d-flex justify-content-between mb-1">
            {$_("deposit.LBL_DIFFERENT_AXIS_MEASURE")}
            <input
              type="text"
              id="cf_wym_1645"
              style="width:50%;height:30px;"
            />
          </div>
        </div>
      </div>
      <div slot="footer" class="d-flex justify-content-between mb-1">
        <a
          href="."
          class="btn btn-danger"
          on:click|preventDefault|stopPropagation={() => {
            showModalDetails = oldDepositData;
            createDeposit = false;
          }}
          >{$_("deposit.LBL_BTN_CANCEL")}&nbsp;<i
            class="fas fa-arrow-left"
          /></a
        >
        <a
          href="."
          class="btn btn-success"
          on:click|preventDefault|stopPropagation={() => {
            saveDeposit(createDeposit.id);
          }}>{$_("deposit.LBL_BTN_SAVE")}&nbsp;<i class="fas fa-check" /></a
        >
      </div>
    </ModalDetails>
  {/if}

  {#if showTimesheetsModal != false}
    <ModalDetails on:close={() => (showTimesheetsModal = false)}>
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_SELECT_EMPLOYEES")}
      </h2>
      {#each userListData as users}
        <p
          style="margin-bottom:0px;font-size:24px;{users.status
            ? 'color:#ccc'
            : ''}"
        >
          <input
            type="radio"
            name="employees"
            disabled={users.status}
            value={users.id}
          />&nbsp;{users.firstname}&nbsp;{users.lastname}
        </p>
      {:else}
        <div style="text-align:center">
          <i class="fas fa-tire fa-spin fa-5x" />
        </div>
      {/each}
      <div slot="footer" class="d-flex justify-content-between mb-1">
        <a
          href="."
          class="btn btn-danger"
          on:click|preventDefault|stopPropagation={() => {
            showModalDetails = showTimesheetsModal;
            showTimesheetsModal = false;
          }}
          >{$_("deposit.LBL_BTN_CANCEL")}&nbsp;<i
            class="fas fa-arrow-left"
          /></a
        >
        <a
          href="."
          class="btn btn-success"
          on:click|preventDefault|stopPropagation={() => {
            showModalTimesheetsUser = document.querySelector(
              'input[name="employees"]:checked'
            ).value;
            showTimeSheetModal = showTimesheetsModal;
            showTimesheetsModal = false;
          }}>{$_("deposit.LBL_BTN_SAVE")}&nbsp;<i class="fas fa-check" /></a
        >
      </div>
    </ModalDetails>
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

  {#if showProductsList != false}
    <ModalProducts
      on:close={() => {
        showDepositModal = showProductsList;
        showProductsList = false;
      }}
    >
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_SELECT_PRODUCT")}
      </h2>
      {#if productsList != false}
        {void fetchProducts() ?? ""}
        <table
          id="products_table"
          class="display"
          style="display:none"
          width="100%"
        >
          <thead>
            <tr>
              <th>Nome Prodotto</th>
              <th>Codice Prodotto / SKU</th>
              <th>Match Code</th>
              <th>Stagione</th>
              <th>id</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>Nome Prodotto</th>
              <th>Codice Prodotto / SKU</th>
              <th>Match Code</th>
              <th>Stagione</th>
              <th>id</th>
            </tr>
          </tfoot>
        </table>
      {:else}
        <div style="text-align:center">
          <i class="fas fa-tire fa-spin fa-5x" />
        </div>
      {/if}
    </ModalProducts>
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
    <Modal
      on:close={() => {
        showModalDetails = showTimeSheetModal;
        showTimeSheetModal = false;
      }}
    >
      <h2 slot="header" style="text-align:center;">
        {$_("home.LBL_TIMESHEETS_LIST")}
      </h2>
      <div
        class="list-group mb-3 text-left"
        style="max-height:30rem;overflow-y:auto;"
      >
        {#each showTimeSheetModal.timesheets as data}
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
        {getTimesheetsTotalHours(
          showTimeSheetModal.timesheets,
          showModalTimesheetsUser
        )}
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
                  startWork(showModalDetails, "stop", showModalDetails.id)}
                >{$_("home.LBL_BTN_STOP_WORK")}</a
              >
              <a
                href="."
                style="border-top-left-radius: 0;border-bottom-left-radius: 0;"
                class="btn btn-success"
                on:click|preventDefault={() =>
                  startWork(showModalDetails, "start", showModalDetails.id)}
                ><i class="fas fa-plus" /></a
              >
            {:else}
              <a
                href="."
                class="btn btn-success"
                on:click|preventDefault={() =>
                  startWork(showModalDetails, "start", showModalDetails.id)}
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
          <b>{$_("home.assigned_to")}:</b>
          {showModalDetails.assigned_user_id}<br />
        </div>
        <div class="col-5">
          <b>{$_("home.plate")}:</b>
          {showModalDetails.plate} <br />
          <b>{$_("home.vehicle")}:</b>
          {showModalDetails.model}<br />
          <b>{$_("home.chassis_no")}:</b>
          {showModalDetails.chassis}<br />
          <b>{$_("home.kilometers")}:</b>
          {showModalDetails.cf_8pb_1203}<br />
          <b>{$_("home.deposit_withdrawn")}:</b>
          {showModalDetails.depositData}<br />
        </div>
        <div class="col-1" style="text-align:right;">
          <i
            class="fas fa-pen text-success"
            on:click={() => {
              editVehicleModal = showModalDetails;
              showModalDetails = false;
            }}
          />
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
        <div class="col-9">
          <div style="max-height: 600px;overflow-y: auto;">
            {#each showModalDetails.product_block.products as product, key}
              {#if product.raw.no_garage != 1}
                <div class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div class="mr-2">
                      <i class="fal fa-clipboard-list-check" />
                    </div>
                    <div class="mr-2">
                      <h5>{product.qty}x</h5>
                    </div>
                    <div>
                      {#if product.entityType == "Products"}
                        <h5 class="mb-0">
                          {product.productName}
                        </h5>
                      {:else}
                        <h5 class="mb-1">
                          {product.productName}
                        </h5>
                      {/if}
                      {#if product.entityType == "Products"}
                        <p class="mb-1">
                          <b
                            >{$_("home.product_code")}: {product.hdnProductcode}</b
                          >
                        </p>
                      {/if}
                      <p class="mb-1">{@html product.productDescription}</p>
                      {#if product.comment != ""}
                        <small
                          ><i
                            class="fal fa-comment-lines"
                          />&nbsp;{product.comment}</small
                        >
                      {/if}
                    </div>
                  </div>
                </div>
              {/if}
            {/each}
          </div>
        </div>
        <div class="col-3 pl-0" style="height:100%;">
          <div class="list-group mb-3" style="overflow-y:auto;">
            {#each showModalDetails.timesheets as timesheet, key}
              {#if timesheet.timesheet_status == "In Progress"}
                <div
                  class="list-group-item list-group-item-action pl-2 pt-1 pr-2 pb-0"
                  style="min-height:40px;background:rgb(255 255 255 / 85%);"
                >
                  <div style="float:left;">
                    <small><i class="fas fa-user" /> </small>
                  </div>
                  <div style="width:65%;float:left;margin-left:0.25rem;">
                    <small>{timesheet.employeename} </small>
                  </div>
                  <div style="text-align: right;">
                    <small>
                      <p class="pt-1" style="color:red;font-weight:500;">
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
                showDeposit(showModalDetails);
              }}>{$_("home.LBL_BTN_SHOW_DEPOSIT")}&nbsp;</a
            >
          {:else}
            <a
              href="."
              class="btn btn-success"
              on:click|preventDefault|stopPropagation={() => {
                openDepositModal(showModalDetails);
              }}
              >{$_("home.LBL_BTN_CREATE_DEPOSIT")}&nbsp;<i
                class="fas fa-plus"
              /></a
            >
          {/if}
        </div>
        <div>
          <!--{#if showModalDetails.timesheets.length > 0}
            <button
              type="button"
              class="btn btn-info"
              on:click|preventDefault={() => {
                showWork(showModalDetails, "ALL", showModalDetails.id);
                showTimesheetsModal = showModalDetails;
                pin = "";
                showModalDetails = false;
              }}>{$_("home.LBL_SHOW_TIMESHEETS")}</button
            >
          {/if}-->
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
