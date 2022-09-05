<script>
  import { backend } from "../../config/config.backend";
  import { onMount } from "svelte";

  let baseURL = backend.baseurl;
  import jQuery from "jquery";
  let username;
  let password;

  onMount(async () => {
    if (!window.jQuery) window.jQuery = jQuery;

    const res = await fetch(
      baseURL +
        "modules/SDK/src/modules/Webservices/boardBackend.php?action=getCredentials",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
      }
    );
    let credentials = [...(await res.json())];
    username = credentials[0].username;
    password = credentials[0].password;

    jQuery("#loginvte").ready(function () {
      setTimeout(showCalendarFrame, 3000);
    });
  });

  function showCalendarFrame() {
    jQuery("#wdCalendar").attr(
      "src",
      "https://viglezio.vtenext.ch/index.php?module=Calendar&action=index&hide_menus=true&skip_vte_footer=true&fastmode=1"
    );
    jQuery("#loginvte").attr("style", "display:none;");
    jQuery(".hidelogo").attr("style", "position:absolute;");
    jQuery(".hidelogo2").attr("style", "position:absolute;");
    jQuery("#wdCalendar").attr("style", "height: 814px;display:block;");
  }
</script>

<svelte:head>
  <title>Calendar</title>
</svelte:head>
<div class="wrapper">
  <!-- https://viglezio.vtenext.ch/index.php?module=Calendar&action=index&hide_menus=true&skip_vte_footer=true&fastmode=1&username={username}&password={password}&board=true-->
  <iframe
    id="loginvte"
    title="loginvte"
    name="loginvte"
    src="https://viglezio.vtenext.ch?username={username}&password={password}&board=true"
    width="100%"
    frameborder="0"
    scrolling="auto"
    style="height: 814px;"
  />
  <div class="hidelogo" />
  <div class="hidelogo2" />
  <iframe
    id="wdCalendar"
    title="wdCalendar"
    name="wdCalendar"
    src=""
    width="100%"
    frameborder="0"
    scrolling="auto"
    style="height: 814px;display:none;"
  />
</div>

<style></style>
