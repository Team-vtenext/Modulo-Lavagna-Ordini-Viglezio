<script>
  import { goto, stores } from "@sapper/app";
  import { backend } from "../config/config.backend";
  import { _, isLoading} from 'svelte-i18n';

  const { session } = stores();

  function handleKeydown(event) {
    if (event.key == "Enter" || event.keyCode == 13) {
      login();
    }
  }

  async function login() {
    let data = {
      cardId: value,
      isAdmin: 1,
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
    let res = await response.json();

    if (res.status == "success" && res.employee_id != "admin") {
      bootbox.alert({
        message: "<p align='center'>" + $_('backend.LBL_WRONG_LOGIN') + "</p>",
        animate: true,
        size: "small",
        backdrop: true,
        centerVertical: true,
        className: "animate__animated animate__shakeX",
      });
      value = "";
    } else {
      sessionStorage.setItem("token", "14332417FAF5AVG54");
      $session.token = "14332417FAF5AVG54";
      goto("/garage/home");
    }
  }

  let baseURL = backend.baseurl;
  let value = "";

  const select = (num) => () => (value += num);
  const clear = () => (value = "");
</script>
{#if $isLoading}
<p></p>
{:else}
<main role="main" class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-sm-6 col-md-4  text-center">
      <h1>{$_('login.LBL_LOGIN_TITLE')}</h1>
      <p>
        <i class="fal fa-hand-pointer" /> {$_('login.LBL_LOGIN_SUBTITLE')}
      </p>
      <div class="row">
        <div class="col-12 text-center">
          <div class="form-group row">
            <div class="col-sm-12">
              <!-- svelte-ignore a11y-autofocus -->
              <input
                type="password"
                class="form-control form-control-lg text-center"
                id="inputcard2"
                autofocus
                on:keypress={handleKeydown}
                bind:value
              />
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12 text-center">
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(1)}><h1>1</h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(2)}><h1>2</h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(3)}><h1>3</h1></button
          >
          <br />
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(4)}><h1>4</h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(5)}><h1>5</h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(6)}><h1>6</h1></button
          >
          <br />
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(7)}><h1>7</h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(8)}><h1>8</h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(9)}><h1>9</h1></button
          >
          <br />
          <button
            type="button"
            class="btn btn-squared-default btn-danger btn-lg mb-1"
            disabled={!value}
            on:click={clear}
            ><h1><i class="fas fa-backspace text-white" /></h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-dark btn-lg mb-1"
            on:click={select(0)}><h1>0</h1></button
          >
          <button
            type="button"
            class="btn btn-squared-default btn-success btn-lg mb-1"
            disabled={!value}
            on:click={login}
            ><h1><i class="fas fa-check text-white" /></h1></button
          >
        </div>
      </div>
    </div>
  </div>
</main>
{/if}
<style>
  @media (min-width: 768px) {
  }

  .btn-squared-default {
    width: 100px !important;
    height: 100px !important;
  }
</style>
