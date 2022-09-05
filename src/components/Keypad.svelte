<script>
  import { createEventDispatcher, onMount } from "svelte";
  import jQuery from "jquery";
  import { _ } from 'svelte-i18n';

  export let value = "";
  let forgotCard = false;
  export let isAdmin = false;
  export let adminCheck = false;

  let inputCard;

  onMount(async () => {
    if (!window.jQuery) window.jQuery = jQuery;

    var readyState = document.readyState;
    if (readyState === "complete" || readyState === "loaded") {
      window.jQuery(function () {
        window.jQuery("#pintoggle").bootstrapToggle();
        window.jQuery("#admintoggle").bootstrapToggle();

        window.jQuery("#admintoggle").change(function () {
          isAdmin = !isAdmin;
        });

        window.jQuery("#pintoggle").change(function () {
          forgotCard = !forgotCard;
        });
      });
    }

    inputCard.focus();
  });

  function handleKeydown(event) {
    if (event.key == "Enter" || event.keyCode == 13) {
      submit();
    }
  }

  const dispatch = createEventDispatcher();

  const select = (num) => () => (value += num);
  const clear = () => (value = "");
  const submit = () => dispatch("submit");
</script>

<main class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-sm-12 col-md-8  text-center">
      <h1>{$_('keypad.LBL_KEYPAD_TITLE')}</h1>
      <p><i class="fal fa-hand-pointer" /> {$_('keypad.LBL_INSERT_PIN')}</p>
      <div class="row">
        <div class="col-12 text-center">
          <div class="form-group row">
            <div class="col-sm-12">
              <input
                type="password"
                class="form-control form-control-lg text-center"
                id="inputcard"
                bind:this={inputCard}
                on:keypress={handleKeydown}
                bind:value
              />
              <div class="d-flex justify-content-between mt-2">
                <p>{$_('keypad.LBL_FORGOT_CARD')}:</p>
                <div on:click={() => inputCard.focus()}>
                <input
                  type="checkbox"
                  bind:checked={forgotCard}
                  data-toggle="toggle"
                  id="pintoggle"
                  data-onstyle="dark"
                  data-on="{$_('keypad.LBL_YES')}"
                  data-off="{$_('keypad.LBL_NO')}"
                  data-offstyle="light"
                  data-style="border"
                />
                </div>
              </div>
              {#if adminCheck}
                <div class="d-flex justify-content-between">
                  <p>{$_('keypad.LBL_ADMIN')}:</p>
                  <div on:click={() => inputCard.focus()}>
                  <input
                    type="checkbox"
                    bind:checked={isAdmin}
                    data-toggle="toggle"
                    id="admintoggle"
                    data-onstyle="dark"
                    data-on="Si"
                    data-off="No"
                    data-offstyle="light"
                    data-style="border"
                  />
                </div>
                </div>
              {/if}
            </div>
          </div>
        </div>
      </div>
      <div
        class="row"
        style="display:{forgotCard ? 'block' : 'none'};"
        id="test"
      >
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
            on:click={submit}
            ><h1><i class="fas fa-check text-white" /></h1></button
          >
        </div>
      </div>
    </div>
  </div>
</main>

<style>
  @media (min-width: 768px) {
  }

  .btn-squared-default {
    width: 100px !important;
    height: 100px !important;
  }
</style>
